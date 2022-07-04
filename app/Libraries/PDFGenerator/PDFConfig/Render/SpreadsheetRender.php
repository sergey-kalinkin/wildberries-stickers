<?php


namespace App\Libraries\PDFGenerator\PDFConfig\Render;


use App\Libraries\PDFGenerator\PDFConfig\Data\ASpreadsheetData;
use App\Libraries\PDFGenerator\PDFConfig\Paper\IPaperConfig;
use App\Libraries\PDFGenerator\PDFConfig\Render\Configs\ARenderConfig;
use App\Libraries\PDFGenerator\PDFConfig\Render\Configs\CommonConfig;
use App\Libraries\PDFGenerator\PDFConfig\Render\Configs\DocumentConfig;
use App\Libraries\PDFGenerator\PDFConfig\Render\Configs\TemplateConfig;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Mpdf\Mpdf;
use Mpdf\MpdfException;

class SpreadsheetRender implements IRender
{
    private CommonConfig $settings;
    private IPaperConfig $config;

    public function __construct(IPaperConfig $paper_config, CommonConfig $user_config)
    {
        $this->config = $paper_config;
        $this->settings = $user_config;
    }

    /**
     *
     * @throws MpdfException|ValidationException
     */
    public function draw(ASpreadsheetData $data)
    {
        //
        //
        $fitter = self::fitStickerHeight(
            $data->getProbe()
        );

        //modify data
        $data->setExcepted(
            $fitter->getExceptedFields()
        );
        $this->settings = $fitter->getFittedConfig();//fresh config

        //
        //calc possible row count per page and column count
        [$row_count, $column_count] = self::calcDimentionSize(
            $data->getProbe()
        )->values()->toArray();

        //modify settings
        $this->settings = new CommonConfig(
            array_merge(
                $this->settings->config(),
                ['row_count' => $row_count]
            ),
        );

        //
        //draw pdf
        $chunks = $data->getData()
            ->chunk($column_count);

        $mpdf = self::tryToDraw($chunks, $this->settings->config());
        return $mpdf->Output();
    }

    private function fitStickerHeight(Collection $item): ConfigFitter
    {
        $fitter = new ConfigFitter($this->settings, $item->first()->get('regular', collect()));
        do {
            $is_modified = false;

            //check drawing size, compare with standard size
            $data_example = $item->map(function ($item) use($fitter) {//TODO duplicated
                /** @var Collection $sub_item */
                $sub_item=$item['regular'];
                return $item->merge(
                    ['regular' => $sub_item->except($fitter->getExceptedFields())]
                );
            });
            $gen = self::testDraw(collect([$data_example]), $fitter->getFittedConfig()->config());

            do {
                $is_modified = self::testHeight($fitter, $gen->current());
                $gen->next();
            } while ($gen->valid() && !$is_modified);

        } while($is_modified);

        return $fitter;
    }

    private function testHeight(ConfigFitter $fitter, Mpdf $mpdf): bool
    {
        $is_modified = false;
        [$st_width, $st_height] = $this->config->sectionSize();
        $do_not_fit_to_standard_height = $mpdf->page !== 1 || ($mpdf->y - $mpdf->tMargin) > $st_height;
        if($do_not_fit_to_standard_height) {

            //fit font size
            $is_modified = !!($new_font_size=$fitter->fitFontSize());

            //fit item size
            if(!$is_modified)
                $is_modified = !!$fitter->fitItemSize();
        }

        return $is_modified;
    }

    private function calcDimentionSize(Collection $item): Collection
    {
        if($this->config->isTermoPaper())
            return collect(['row_count' => 1, 'col_count' => 1]);

        //
        [$st_width, $st_height] = $this->config->sectionSize();
        $gen = self::testDraw(collect([$item]), $this->settings->config());
        $elements_per_page = [];
        foreach ($gen as $mpdf) {
            $h_margins = $mpdf->bMargin + $mpdf->tMargin;
            $w_margins = $mpdf->lMargin + $mpdf->rMargin;
            [$row_count, $column_count] = [
                (int)(($mpdf->h - $h_margins) / ($mpdf->y - $mpdf->tMargin)),  //count per column
                (int)(($mpdf->w - $w_margins) / ($st_width - 1)) //count per row
            ];

            if( !isset($elements_per_page['row_count']) || $row_count < $elements_per_page['row_count'])
                $elements_per_page['row_count'] = $row_count;

            if(!isset($elements_per_page['col_count']) || $column_count < $elements_per_page['col_count'] )
                $elements_per_page['col_count'] = $column_count;
        }

        return collect($elements_per_page);
    }

    private function testDraw(Collection $data, array $doc_config): \Generator
    {
        //TODO
        $tmp = $this->settings->do_split_barcode_and_info ?
            ['layout' => 'app.elements.stickers.split_layout' , 'components' => ['app.elements.stickers.split_barcodes', 'app.elements.stickers.split_barcodes_info']] : [];

        //
        $printer = new Printer($this->config, new TemplateConfig(
            array_merge(
                $this->settings->config(),
                $tmp
            )
        ));

        $gen = $printer->printOneBlock($data, new DocumentConfig(
            array_merge(
                $this->settings->config(),
                $doc_config,
                ['is_dashed' => false]
            )
        ));
        //TODO template only
        foreach ($gen as $mpdf) {
            yield $mpdf;
        }
    }

    private function tryToDraw(Collection $data, array $doc_config): Mpdf
    {
        //TODO
        $tmp = $this->settings->do_split_barcode_and_info ?
            ['layout' => 'app.elements.stickers.split_layout' , 'components' => ['app.elements.stickers.split_barcodes', 'app.elements.stickers.split_barcodes_info']] : [];

        //
        $printer = new Printer($this->config, new TemplateConfig(
            array_merge(
                $this->settings->config(),
                $tmp
            )
        ));

        $mpdf = $printer->printDocument($data, new DocumentConfig(
            array_merge(
                $this->settings->config(),
                $doc_config
            )
        ));

        return $mpdf;
    }
}
