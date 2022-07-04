<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Facades\Excel;

class SpreadsheetFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !empty($this->user());
    }

    public function prepareForValidation()
    {
        $this->merge(['user_id'=>$this->user()->id]);
    }

    public function validateResolved()
    {
        Excel::import(
            $data = self::spreadsheetImporter(),
            $this->file('spreadsheetFile')
        );

        //validate required table fields
        $spreadsheet = $data->res;
        self::checkSpreadsheetData($spreadsheet);//TODO here is nested validation

        $this->merge([
            'spreadsheet' => $data->res,
            'name' => $this->file('spreadsheetFile')->getClientOriginalName(),
            'user_id' => $this->user()->id
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'spreadsheetFile' => 'required|mimetypes:application/csv,application/excel,
                                    application/vnd.ms-excel, application/vnd.msexcel,
                                    text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];
    }

    //TODO move table parsing in another spot
    private function spreadsheetImporter()
    {
        return new class() implements ToCollection {
            public $res;
            public function collection(Collection $collection)
            {
                //reject empty rows
                $collection = $collection->reject(function ($row, $index) {
                    $filter = $row->filter(fn($v) => !empty($v));
                    return $filter->isEmpty();
                });

                //get only named columns
                //TODO now accepting there are no nullable columns between named
                $buff = ($_=$collection->first())->filter(fn($v,$i) => !empty($v))->count();
                $collection = $collection->map(function ($row) use($buff) {
                    return $row->take($buff);
                });

                $collection = collect([
                    'columns' => $collection->shift(),
                    'rows' => $collection
                ]);

                $this->res = $collection;
            }
        };
    }

    /**
     * TODO make another validation request
     * @throws ValidationException
     */
    private function checkSpreadsheetData(Collection $spreadsheet)
    {
        //
        return \Validator::make([$spreadsheet], [
            '*' => 'spreadsheet_columns:wildberries',
        ])->validate();
    }
}
