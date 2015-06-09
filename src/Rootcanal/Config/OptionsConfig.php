<?php

namespace Rootcanal\Config;

class OptionsConfig
{
    /*
     * @var string|bool
     */
    private $productionMode;

    public function __construct($productionMode)
    {
        $this->productionMode = $productionMode;
    }

    public function isProductionEnabled()
    {
        return $this->productionMode;
    }
}
