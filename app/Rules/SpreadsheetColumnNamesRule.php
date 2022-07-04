<?php

namespace App\Rules;

use App\Models\Spreadsheet;
use App\Models\SpreadsheetField;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class SpreadsheetColumnNamesRule implements Rule
{
    private $message = '';
    private $default = 'wildberries';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        self::checkBaseRequirement($value);

        //
        $spreadsheet_name = func_get_arg(2)[0] ?? null;//TODO

        /** @var Spreadsheet $spreadsheet*/
        $spreadsheet = Spreadsheet::with('fields')
            ->whereCode(config("spreadsheets.{$spreadsheet_name}", $this->default))
            ->first();

        /** @var Collection $value*/
        $do_columns_exist = self::checkRequiredColumns($spreadsheet, $value->get('columns'));
        if(!$do_columns_exist) return false;

        $are_columns_valid = self::validateRequiredColumns($spreadsheet, $value);
        if(!$are_columns_valid) return false;

        $are_column_values_valid = self::validateRequiredColumns($spreadsheet, $value);
        if(!$are_column_values_valid) return false;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }

    private function validateRequiredColumns(Spreadsheet $spreadsheet, Collection $value): bool
    {
        /** @var Collection $columns*/
        $columns = $value->get('columns');

        /** @var Collection $rows*/
        $rows = $value->get('rows');

        $data = $spreadsheet->fields->map(function (SpreadsheetField $field, $key) use($columns, $rows){
            $idx = $columns->search($field->name);
            $row = $rows->pluck($idx)->toArray();

            return [
                $row, ['*' => "bail|min:1|{$field->validation_type}"]
            ];
        });

        //
        try {
            $data->each(function ($item) {
                \Validator::make(...$item)->validate();
            });
        }
        catch (ValidationException $t) {
            $this->message = $t->validator->errors()->first();
            return false;
        }

        return true;
    }

    private function checkRequiredColumns(Spreadsheet $spreadsheet, Collection $columns): bool
    {
        $required_fields = $spreadsheet->fields->pluck('name', 'code');

        $missing = $required_fields->search(function ($val) use($columns) {
            return $columns->doesntContain($val);
        });

        if($missing) {
            $this->message = "spreadsheet :attribute doesn't have required column {$missing}";//TODO
            return false;
        }

        return true;
    }

    private function checkBaseRequirement($value): bool
    {
        if(!($value instanceof Collection)) {
            $this->message = 'attribute :attribute must be collection';
            return false;
        }

        if(!$value->has('columns')) {
            $this->message = 'attribute :attribute doesnt have columns attribute';
            return false;
        }

        if(!$value->has('rows')) {
            $this->message = 'attribute :attribute doesnt have rows attribute';
            return false;
        }

        return true;
    }
}
