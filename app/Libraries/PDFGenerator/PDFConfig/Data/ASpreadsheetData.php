<?php

namespace App\Libraries\PDFGenerator\PDFConfig\Data;


use App\Models\SpreadsheetField;
use Illuminate\Support\Collection;

abstract class ASpreadsheetData extends AData
{
    abstract public function setExcepted(array $excepted_keys);

    protected function parseSpreadsheet(array $data): Collection
    {
        //
        //split columns data and keys
        $column_names = collect($data['columns']);
        $rows = collect($data['rows']);

        //get required fields definition
        $fields = $this->fields;

        //replace column names with codes
        $column_names = $column_names->map(function ($name) use($fields) {
            /** @var SpreadsheetField $field */
            $field_id = $fields->search(fn($field) => $field->name === $name);
            return $field_id !== false ? $fields->get($field_id)->code : $name;
        });

        //split spreadsheet columns on category
        $data = $rows->map(function ($row) use ($column_names, $fields) {

            $keyed_row = $column_names->combine($row);

            //all required columns
            $all_required = $keyed_row->only(
                $fields->pluck('code')->toArray()
            );

            //required non-special columns
            $required = $keyed_row->only(
                $fields->where('is_special', 0)->pluck('code')->toArray()
            );

            //only special required columns
            $special = $keyed_row->only(
                $fields->where('is_special', 1)->pluck('code')->toArray()
            );

            //regular columns
            $regular = $keyed_row->except(
                $fields->pluck('code')->toArray()
            );

            return collect([
                'required' => $all_required,
                'required_non_special' => $required,
                'required_special' => $special,
                'regular' => $regular,
                'keys' => static::getRequiredNames(),
            ]);
        });

        return $data;
    }
}
