<?php

namespace Rootcanal\Config;

class Finder
{
    /*
     * @var string
     */
    private $sourceRoot;

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
        $sourceRoot,
        $filesPublicSourcePath,
        $filesPrivateSourcePath,
        $settingsSourcePath
    )
    {
        $this->sourceRoot             = $sourceRoot;
        $this->filesPublicSourcePath  = $filesPublicSourcePath;
        $this->filesPrivateSourcePath = $filesPrivateSourcePath;
        $this->settingsSourcePath     = $settingsSourcePath;
    }

    public function getFilesPublicSourcePath()
    {
        return $this->filesPublicSourcePath;
    }

    public function getFilesPrivateSourcePath()
    {
        return $this->filesPrivateSourcePath;
    }

    public function getSettingsSourcePath()
    {
        return $this->settingsSourcePath;
    }
}
