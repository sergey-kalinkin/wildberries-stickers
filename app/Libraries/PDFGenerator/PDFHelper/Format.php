<?php


namespace App\Libraries\PDFGenerator\PDFHelper;

use Illuminate\Support\Collection;

/**
 * Class Format
 * @property bool $f_A4
 *
 * @property bool $f_66d7x46
 * @property bool $f_64d6x33d8
 *
 * @package App\Libraries\PDFGenerator\PDFHelper
 */
class Format implements IFormat
{
    private $prefix = 'f_';
    private $key = null;
    private static $formats;

    public function __get(string $name)
    {
        $key = str_replace($this->prefix, '', $name);

        $is_prepare = !isset($this->key) && static::formats()->has($key);
        if ($is_prepare)
            $this->key = $key;

        return $is_prepare;
    }

    /**
     * Return two dimensions size
     * @param bool $default
     * @return array|null [width, height]
     */
    public function getFormat($default = false): ?array
    {
        return static::formats()->get(
            $this->key,
            $default ? $this->defaultFormat() : null
        );
    }

    public function defaultFormat(): array
    {
        return static::formats()->first();
    }

    public static function formats(): Collection
    {
        if (static::$formats)
            return static::$formats;

        return static::$formats = collect(
            [
                'A4' => ['210', '297'],

                //custom formats
                '66d7x46' => ['66.7', '46'],
                '64d6x33d8' => ['64.6', '33.8'],
            ]
        );
    }
}
