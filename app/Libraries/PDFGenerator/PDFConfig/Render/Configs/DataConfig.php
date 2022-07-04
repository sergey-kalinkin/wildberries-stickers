<?php


namespace App\Libraries\PDFGenerator\PDFConfig\Render\Configs;

/**
 * Class DataConfig
 * @package App\Libraries\PDFGenerator\PDFConfig\Render\Configs
 *
 * @property boolean $do_merge_color_and_size
 */
class DataConfig extends ARenderConfig
{

    protected function default(): array
    {
        return [
            'do_merge_color_and_size' => false,
        ];
    }

    protected function rules(): array
    {
        return [
            'do_merge_color_and_size' => 'boolean',
        ];
    }
}
