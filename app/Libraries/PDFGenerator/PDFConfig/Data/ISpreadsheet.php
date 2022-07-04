<?php

namespace App\Libraries\PDFGenerator\PDFConfig\Data;

use Illuminate\Support\Collection;

interface ISpreadsheet
{
    public function getProbe(): Collection;
    public function getData(): Collection;
}
