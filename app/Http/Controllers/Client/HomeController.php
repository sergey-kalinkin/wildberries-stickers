<?php

namespace App\Http\Controllers\Client;


use App\Http\Controllers\Controller;
use App\Libraries\PDFGenerator\PDFConfig\Data\RenderData;
use App\Models\StickerData;

class HomeController extends Controller
{
    public function index($id=null)
    {
        $table = StickerData::tryGetSpreadsheet($id);

        if(!$table)
            return view('app.client.generator');

        $id = $table->id;
        $data = new RenderData($table);

        $spreadsheet = $data->getData();
        $required_fields = $data->getRequiredNames();
        $keys = $data->getKeys();
        $excluded = $data->getExcludedFields();

        return view('app.client.generator', compact(
            'spreadsheet',
            'required_fields',
            'keys',
            'id',
            'excluded',
            )
        );
    }

    public function getListOfTables()
    {
        $list = StickerData::select(['id', 'name'])->get();
        return view('app.client.spreadsheets', compact('list'));
    }
}
