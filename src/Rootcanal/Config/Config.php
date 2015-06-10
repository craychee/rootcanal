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
    private $filesPublicPath;

    /*
     * @var string
     */
    private $filesPrivatePath;

    /*
     * @var string
     */
    private $settingsPath;

    public function __construct(
        $productionMode,
        $destination,
        $modulePath,
        $themePath,
        $drushPath,
        $profilePath,
        $filesPublicPath,
        $filesPrivatePath,
        $settingsPath
    )
    {
        $this->productionMode   = $productionMode;
        $this->destination      = rtrim($destination, DIRECTORY_SEPARATOR);
        $this->modulePath       = $modulePath;
        $this->themePath        = $themePath;
        $this->drushPath        = $drushPath;
        $this->profilePath      = $profilePath;
        $this->filesPublicPath  = $filesPublicPath;
        $this->filesPrivatePath = $filesPrivatePath;
        $this->settingsPath     = $settingsPath;
    }

    public function isProductionEnabled()
    {
        return $this->productionMode;
    }

    public function getPaths()
    {
        return [
                'core'          => $this->destination,
                'module'        => $this->destination . $this->modulePath,
                'theme'         => $this->destination . $this->themePath,
                'drush'         => $this->destination . $this->drushPath,
                'profile'       => $this->destination . $this->profilePath,
                'files-public'  => $this->destination . $this->filesPublicPath,
                'files-private' => $this->destination . $this->filesPrivatePath,
                'settings'      => $this->destination . $this->settingsPath,
            ];
    }

    public function getPathsByType($type)
    {
        return $this->getPaths()[$type];
    }
}
