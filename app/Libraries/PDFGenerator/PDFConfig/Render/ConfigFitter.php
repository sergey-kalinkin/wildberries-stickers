<?php


namespace App\Libraries\PDFGenerator\PDFConfig\Render;


use App\Libraries\PDFGenerator\PDFConfig\Render\Configs\ARenderConfig;
use App\Libraries\PDFGenerator\PDFConfig\Render\Configs\CommonConfig;
use App\Libraries\PDFGenerator\PDFConfig\Render\Configs\DocumentConfig;
use Illuminate\Support\Collection;

class ConfigFitter
{
    private CommonConfig $settings;
    private Collection $dataProbe;

    public function __construct(CommonConfig $user_config, Collection $data_example)
    {
        $this->settings = $user_config;
        $this->dataProbe = $data_example;

        //
        $default_font_size = (new DocumentConfig())->font_size;
        $current_font_size = $this->settings->font_size ?? $default_font_size;
        $this->fontSize = $current_font_size;
    }

    public function getFittedConfig(): CommonConfig
    {
        return new CommonConfig(
            array_merge(
                $this->settings->config(),
                [
                    'font_size' => $this->fontSize
                ]
            ),
        );
    }

    public function getExceptedFields(): array
    {
        return $this->removedFields;
    }

    private int $fontSize;
    public function fitFontSize(): ?float
    {
        if(!$this->settings->font_size && $this->fontSize > 7)
            return $this->fontSize-=0.5;

        return null;
    }

    private array $removedFields = [];
    public function fitItemSize(): ?array
    {
        //
        /*$regular_fields = $this->dataProbe->get('regular');
        if(!($regular_fields instanceof Collection))
            return null;*/

        //
        $accepted_fields = $this->dataProbe
                ->except($this->removedFields);
        if($accepted_fields->isEmpty())
            return null;

        //
        $this->removedFields[] = $accepted_fields
                                            ->keys()
                                            ->pop();
        return $this->removedFields;
    }
}
