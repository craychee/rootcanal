<?php

namespace Rootcanal\Config;

class Config
{
    /*
     * @var string|bool
     */
    private $productionMode;

    /*
     * @var string
     */
    private $destination;

    /*
     * @var string
     */
    private $modulePath;

    /*
     * @var string
     */
    private $themePath;

    /*
     * @var string
     */
    private $drushPath;

    /*
     * @var string
     */
    private $profilePath;

    /*
     * @var string
     */
    private $filesPublicSourcePath;

    /*
     * @var string
     */
    private $filesPrivateSourcePath;

    /*
     * @var string
     */
    private $settingsSourcePath;

    public function __construct(
        $productionMode,
        $destination,
        $modulePath,
        $themePath,
        $drushPath,
        $profilePath
    )
    {
        $this->productionMode         = $productionMode;
        $this->destination            = $destination;
        $this->modulePath             = $modulePath;
        $this->themePath              = $themePath;
        $this->drushPath              = $drushPath;
        $this->profilePath            = $profilePath;
    }

    public function isProductionEnabled()
    {
        return $this->productionMode;
    }

    public function getPaths()
    {
        return [
                'core'    => $this->destination,
                'module'  => $this->destination . $this->modulePath,
                'theme'   => $this->destination . $this->themePath,
                'drush'   => $this->destination . $this->drushPath,
                'profile' => $this->destination . $this->profilePath,
            ];
    }

    public function getPathsByType($type)
    {
        return $this->getPaths()[$type];
    }
}
