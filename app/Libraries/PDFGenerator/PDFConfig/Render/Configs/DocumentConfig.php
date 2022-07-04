<?php


namespace App\Libraries\PDFGenerator\PDFConfig\Render\Configs;


/**
 * Class DocumentConfig
 * @package App\Libraries\PDFGenerator\PDFConfig\Render\Configs
 *
 * @property int $font_size
 */
class DocumentConfig extends ARenderConfig
{

    protected function default(): array
    {
        return [
            'font_size' => 12,
            'is_dashed' => false,
            'has_eac' => false,
            'is_certified' => false,
        ];
    }

    protected function rules(): array
    {
        return [
            'font_size' => 'integer',
            'is_dashed' => 'boolean',
            'has_eac' => 'boolean',
            'is_certified' => 'boolean',
        ];
    }
}
