<?php


namespace App\Libraries\PDFGenerator\PDFConfig\Render;


use App\Libraries\PDFGenerator\PDFConfig\Paper\IPaperConfig;
use App\Libraries\PDFGenerator\PDFConfig\Render\Configs\DocumentConfig;
use App\Libraries\PDFGenerator\PDFConfig\Render\Configs\IRenderConfig;
use App\Libraries\PDFGenerator\PDFConfig\Render\Configs\TemplateConfig;
use Illuminate\Support\Collection;
use Mpdf\Mpdf;

class Printer
{
    private IPaperConfig $config;
    private TemplateConfig $template;

    public function __construct(IPaperConfig $paper_config, TemplateConfig $render_config)
    {
        $this->config = $paper_config;
        $this->template = $render_config;
    }

    public function printDocument(Collection $data, DocumentConfig $render_config): Mpdf
    {
        $mpdf = new Mpdf(
            $this->config->config()
        );

        $sticker_rows = self::printStickers($data);
        self::addPageDelimiters($sticker_rows);

        $config = array_merge(
            //$this->settings->config(),
            $render_config->config(),
            [
                'rows' => $sticker_rows,
                'sticker_size' => $this->config->sectionSize(),
            ]
        );

        $mpdf->WriteHTML(
            view($this->template->layout, $config)->render()
        );

        return $mpdf;
    }

    public function printOneBlock(Collection $data, DocumentConfig $render_config): \Generator
    {
        $sticker_rows = self::printStickers($data);
        foreach($sticker_rows as $sticker) {
            $mpdf = new Mpdf(
                $this->config->config()
            );

            $config = array_merge(
            //$this->settings->config(),
                $render_config->config(),
                [
                    'rows' => [$sticker],
                    'sticker_size' => $this->config->sectionSize(),
                ]
            );

            $mpdf->WriteHTML(
                view($this->template->layout, $config)->render()
            );
            yield $mpdf;
        }
    }

    private function addPageDelimiters(Collection $sticker_blocks)
    {
        $row_count = $this->config->isTermoPaper() ? 1 : $this->template->row_count;

        try {
            $elements_per_page = range(
                $row_count,
                $sticker_blocks->count()-1 ?: 1,
                $row_count+1
            );
        }
        catch (\Throwable $t) {
            $elements_per_page = [];
        }

        if(count($elements_per_page) <= 1)
            return;

        foreach ($elements_per_page as $it) {
            $sticker_blocks->splice($it, 0, ['<pagebreak>']);
        }
    }

    private function printStickers($chunks): Collection
    {
        $sticker_rows = collect();
        foreach ($chunks as $slice) {
            $sticker_rows->push(...self::drawBlock($slice));
        }

        return $sticker_rows;
    }

    private function drawBlock(Collection $block): \Generator
    {
        foreach($this->template->components as $tmp)
            yield view($tmp, [
                'items' => $block,
                'is_double_barcode_size' => $this->template->is_double_barcode_size,
            ])->render();
    }
}
