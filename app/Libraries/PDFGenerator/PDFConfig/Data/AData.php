<?php


namespace App\Libraries\PDFGenerator\PDFConfig\Data;


use App\Models\Spreadsheet;
use App\Models\SpreadsheetField;
use Illuminate\Support\Collection;

abstract class AData implements ISpreadsheet
{
    protected string $table;
    protected Collection $fields;
    protected Collection $names;

    public function __construct(string $table)
    {
        $this->table = $table;

        $this->fields = Spreadsheet::whereCode($this->table)
            ->with('fields')
            ->first()
            ->fields
            /** @var SpreadsheetField $field1 */
            /** @var SpreadsheetField $field2 */
            ->sort(fn($field1, $field2)=>~$field1->sequence ? $field1->sequence > $field2->sequence : -1)
            ->reverse()
            ->values();

        $this->names = $this->fields->pluck('name', 'code');
    }

    /**
     * Return definition of required fields
     * @return Collection [code=>name]
     */
    public function getRequiredNames(): Collection
    {
        return $this->names;
    }

    abstract protected function parseSpreadsheet(array $data): Collection;
}
