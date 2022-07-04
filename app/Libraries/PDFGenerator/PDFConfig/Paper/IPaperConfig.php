<?php

namespace  App\Libraries\PDFGenerator\PDFConfig\Paper;

interface IPaperConfig
{
    /**
     * Return array with config
     * @return array
     */
    public function config(): array;

    /**
     * Check paper type
     */
    public function isTermoPaper(): bool;

    /**
     * Return section size
     * @return array [width, height]
     */
    public function sectionSize(): array;
}
