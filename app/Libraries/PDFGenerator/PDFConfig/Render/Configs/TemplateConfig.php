<?php


namespace App\Libraries\PDFGenerator\PDFConfig\Render\Configs;

/**
 * Class TemplateConfig
 * @package App\Libraries\PDFGenerator\PDFConfig\Render\Configs
 *
 * @property string $layout
 * @property string[] $components
 * @property int $row_count
 * @property boolean $is_double_barcode_size
 */
class TemplateConfig extends ARenderConfig
{
    protected function default(): array
    {
        return [
            'layout' => 'app.elements.stickers.layout',
            'components' => ['app.elements.stickers.barcodes'],
            'row_count' => 1,
            'is_double_barcode_size' => false,
        ];
    }

    protected function rules(): array
    {
        return [
            'layout' => 'string',
            'components' => 'array|min:1',
            'components.*' => 'required|string',
            'row_count' => 'integer',
            'is_double_barcode_size' => 'boolean',
        ];
    }
}
