<?php


namespace App\Libraries\PDFGenerator\PDFHelper;


interface IFormat
{
    public function getFormat($default = false): ?array;
    public function defaultFormat(): array;
}
