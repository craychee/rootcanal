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
     * @var array
     */
    private $clean;

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

    /*
     * @var string
     */
    private $vendorPath;

    public function __construct(
        $productionMode,
        $destination,
        $clean,
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
        $this->destination      = $this->getDestination($destination);
        $this->clean            = $clean;
        $this->modulePath       = $modulePath;
        $this->themePath        = $themePath;
        $this->drushPath        = $drushPath;
        $this->profilePath      = $profilePath;
        $this->filesPublicPath  = $filesPublicPath;
        $this->filesPrivatePath = $filesPrivatePath;
        $this->settingsPath     = $settingsPath;
        $this->vendorPath       =
            DIRECTORY_SEPARATOR . 'sites' .
            DIRECTORY_SEPARATOR . 'default' .
            DIRECTORY_SEPARATOR . 'vendor';
    }

    public function isProductionEnabled()
    {
        return $this->productionMode;
    }

    public function getClean()
    {
        return $this->clean;
    }

    public function getDestination($relDest)
    {
        return implode(DIRECTORY_SEPARATOR, [getcwd(), rtrim($relDest, DIRECTORY_SEPARATOR)]);
    }

    public function getPaths()
    {
        return [
            'core'          => $this->destination,
            'module'        => $this->destination . $this->modulePath,
            'custom'        => $this->destination . $this->modulePath . '/%s',
            'theme'         => $this->destination . $this->themePath,
            'drush'         => $this->destination . $this->drushPath,
            'profile'       => $this->destination . $this->profilePath,
            'files-public'  => $this->destination . $this->filesPublicPath,
            'files-private' => $this->destination . $this->filesPrivatePath,
            'settings'      => $this->destination . $this->settingsPath,
            'vendor'        => $this->destination . $this->vendorPath,
            ];
    }

    public function getPathsByType($type)
    {
        return $this->getPaths()[$type];
    }
}
