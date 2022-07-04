<?php

namespace App\Libraries\PDFGenerator\PDFConfig\Render;


use App\Libraries\PDFGenerator\PDFConfig\Data\ASpreadsheetData;

interface IRender
{
    public function draw(ASpreadsheetData $data);
}
