<?php


namespace App\Libraries\PDFGenerator\PDFConfig\Data;


use App\Models\SpreadsheetField;
use App\Models\StickerData;
use Illuminate\Support\Collection;

class RenderData extends AData
{
    private StickerData $spreadsheetInstance;
    private Collection $spreadsheetRepresentation;
    private Collection $keys;
    private Collection $excluded;

    public function __construct(StickerData $spreadsheet)
    {
        parent::__construct('wildberries');

        //
        $this->spreadsheetInstance = $spreadsheet;
        //
        $this->spreadsheetRepresentation = self::parseSpreadsheet(
            $data = json_decode($this->spreadsheetInstance->spreadsheet, true)//parse json to array
        );
    }

    public function getKeys(): Collection
    {
        return $this->keys;
    }

    public function getExcludedFields(): Collection
    {
        return $this->excluded;
    }

    protected function parseSpreadsheet(array $data): Collection
    {
        //
        //split columns data and keys
        $this->keys = collect($data['columns']);
        $this->excluded = collect($data['excluded'] ?? []);

        //
        $rows = collect($data['rows']);
        $rows = $rows->map(function ($row) {
            return $row;
        });

        return $rows;
    }

    public function getProbe(): Collection
    {
        return  collect([$this->spreadsheetRepresentation->first()]);
    }

    public function getData(): Collection
    {
        return $this->spreadsheetRepresentation;
    }
}
