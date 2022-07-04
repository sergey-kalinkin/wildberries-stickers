<?php


namespace App\Libraries\PDFGenerator\PDFConfig\Render\Configs;

/**
 * Class UserRenderConfig
 * @package App\Libraries\PDFGenerator\PDFConfig\Render
 *
 * @property boolean|null $is_dashed
 * @property boolean|null $has_eac
 * @property boolean|null $is_certified
 * @property boolean|null $do_merge_color_and_size
 * @property boolean|null $do_split_barcode_and_info
 * @property int|null $font_size
 * @property int|null $row_count
 * @property boolean|null $is_double_barcode_size
 */
class CommonConfig extends ARenderConfig
{
    protected function rules(): array
    {
        return [
            'is_dashed' => 'boolean',
            'has_eac' => 'boolean',
            'is_certified' => 'boolean',
            'do_merge_color_and_size' => 'boolean',
            'do_split_barcode_and_info' => 'boolean',
            'font_size' => 'integer',
            'row_count' => 'integer',
            'is_double_barcode_size' => 'boolean',
        ];
    }

    protected function default() : array
    {
        return [

        ];
    }
}
