<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpreadsheetFileRequest;
use App\Libraries\PDFGenerator\PDFConfig\Data\PrintingData;
use App\Libraries\PDFGenerator\PDFConfig\Paper\Paper;
use App\Libraries\PDFGenerator\PDFConfig\Render\Configs\CommonConfig;
use App\Libraries\PDFGenerator\PDFConfig\Render\Configs\DataConfig;
use App\Libraries\PDFGenerator\PDFConfig\Render\SpreadsheetRender;
use App\Libraries\PDFGenerator\PDFGenerator;
use App\Libraries\PDFGenerator\PDFHelper\Format;
use App\Models\StickerData;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StickerController extends Controller
{
    //TODO refactor it
    public function importSpreadsheetFile(SpreadsheetFileRequest $request)
    {
        $data = $request->input();
        StickerData::create($data);

        return response()->json(RouteServiceProvider::HOME);
    }

    public function generateBarcodes($id, Request $request)
    {
        //
        //get data
        $stickers = StickerData::tryGetSpreadsheet($id);//TODO
        $data = new PrintingData(
            $stickers,
            new DataConfig($request->all())
        );

        //
        //make paper settings
        $paper_settings = new Paper();
        $paper_settings->margins(5,0,0,5);

        $size = $request->input('stickerSize');
        if($request->input('is_termopaper')) {
            $paper_settings->termoPaperType();
            $paper_settings->format()->$size;//TODO we have strange behaviour here
        }
        $paper_settings->format()->f_A4;//TODO
        $paper_settings->sectionFormat()->$size;

        //
        //
        $user_config = new CommonConfig($request->all());//user settings
        $render = new SpreadsheetRender($paper_settings, $user_config);
        $doc = $render->draw($data);

        return $doc;
    }

    //TODO bad code
    public function updateSpreadsheet($spreadsheet_id, Request $request)
    {
        $uid = Auth::id() or abort(404); //TODO add policy

        StickerData::whereId($spreadsheet_id)
            ->whereUserId($uid)
            ->when(!empty($spreadsheet_id), function ($query) use($request) {
                $query->update($request->all());
            })
            ->when(empty($spreadsheet_id), function ($query)  use($request, $uid, ) {
                $query->create(
                    array_merge($request->all(), [ 'user_id' => $uid, 'name' => Str::random()])
                );
            });

        return true;
    }

    private function getItems()
    {
        //
        $data = StickerData::whereUserId(\Auth::id())->orderByDesc('id')->first();
        $spreadsheet = json_decode($data->spreadsheet, true);

        $columns = $spreadsheet['columns'];
        $items = collect($spreadsheet['rows'])->map(function ($row) use ($columns) {
            return collect($columns)->combine($row);
        });

        return $items;
    }
}
