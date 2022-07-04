<?php


namespace App\Libraries\PDFGenerator\PDFConfig\Paper;


use App\Libraries\PDFGenerator\PDFHelper\IFormat;
use App\Libraries\PDFGenerator\PDFHelper\Format;

class Paper implements IPaperSettings, IPaperConfig
{
    private IFormat $format;
    private bool $isTermoPaper;
    private array $margins;
    private IFormat $sectionFormat;

    /**
     * Paper constructor.
     */
    public function __construct()
    {
        $this->format = new Format();
        $this->sectionFormat = new Format();
        $this->isTermoPaper = false;
        $this->margins = self::defaultMargins();
    }

    /**
     * @inheritdoc
     */
    public function config(): array
    {
        return array_merge(
            [
                'mode' => 'utf-8',
                'format' => $this->format->getFormat(true),
            ],
            $this->isTermoPaper ? self::defaultMargins() : $this->margins
        );
    }

    /**
     * @inheritdoc
     */
    public function format(): Format
    {
        return $this->format;
    }

    /**
     * @inheritdoc
     */
    public function termoPaperType()
    {
        $this->isTermoPaper = true;
    }

    /**
     * @inheritdoc
     */
    public function isTermoPaper(): bool
    {
        return $this->isTermoPaper;
    }

    /**
     * @inheritdoc
     */
    public function margins(int $top = 0, int $right = 0, int $bottom = 0, int $left = 0)
    {
        $this->margins = [
            'margin_top' => $top,
            'margin_bottom' => $bottom,
            'margin_left' => $left,
            'margin_right' => $right,
        ];
    }

    private function defaultMargins()
    {
        return [
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
        ];
    }

    /**
     * @inheritdoc
     */
    public function sectionFormat(): Format
    {
        return $this->isTermoPaper ? $this->format : $this->sectionFormat;
    }

    /**
     * @inheritdoc
     */
    public function sectionSize(): array
    {
        return static::sectionFormat()->getFormat(true);
    }
}
