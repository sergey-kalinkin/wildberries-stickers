<?php

namespace App\Libraries\PDFGenerator\PDFConfig\Paper;


use App\Libraries\PDFGenerator\PDFHelper\Format;

interface IPaperSettings
{
    /**
     * Return paper format
     *          for set value
     * @return Format
     */
    public function format(): Format;

    /**
     * Return paper section format
     *          for set value
     * @return Format
     */
    public function sectionFormat(): Format;

    /**
     * Set paper type as termopaper
     */
    public function termoPaperType();

    /**
     * Set margins
     *
     * @property int $top
     * @property int $right
     * @property int $bottom
     * @property int $left
     */
    public function margins(int $top = 0, int $right = 0, int $bottom = 0, int $left = 0);
}
