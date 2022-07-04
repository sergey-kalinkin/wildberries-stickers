<?php


namespace App\Libraries\PDFGenerator\PDFConfig\Render\Configs;


interface IRenderConfig
{
    /**
     * Return render config
     * @return array
     */
    public function config() : array;
}
