<?php

namespace Rootcanal\Config;

use Symfony\Component\Finder\Finder as BaseFinder;

class Finder
{
    /*
     * @var string
     */
    private $file;

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

    /*
     * @var array
     */
    private $ignoreSourceDirs = ['vendor', 'cnf'];

    /*
     * @var array
     */
    private $customFileExtentions = array(
        'php',
        'inc',
        'module',
        'info',
        'install',
    );

    public function __construct(
        $sourceRoot,
        $destination,
        $filesPublicSourcePath,
        $filesPrivateSourcePath,
        $settingsSourcePath
    )
    {
        $this->sourceRoot             = rtrim($sourceRoot, DIRECTORY_SEPARATOR);
        $this->destination            = rtrim($destination, DIRECTORY_SEPARATOR);
        $this->filesPublicSourcePath  = $filesPublicSourcePath;
        $this->filesPrivateSourcePath = $filesPrivateSourcePath;
        $this->settingsSourcePath     = $settingsSourcePath;
    }

    public function getSourceRoot()
    {
        return $this->sourceRoot;
    }

    public function getIgnoredDirs()
    {
        $this->ignoreSourceDirs[] = $this->destination;

        return $this->ignoreSourceDirs;
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

    public function getFinder()
    {
        return new BaseFinder();
    }

    public function getFinderByType($type)
    {
        if (is_dir($dir = $this->getSourceRoot() . DIRECTORY_SEPARATOR . "{$type}s")) {
            $finder = $this->getFinder()
                ->ignoreUnreadableDirs()
                ->depth('== 0')
                ->in($dir);

            return $finder;
        }

        return [];
    }

    public function getCustomFilesFinder()
    {
        $finder = $this->getFinder()
            ->ignoreUnreadableDirs()
            ->depth('== 0')
            ->in($this->getSourceRoot())
            ->exclude($this->getIgnoredDirs());

        foreach ($this->customFileExtentions as $extension) {
            $finder->name("*.{$extension}");
        }

        return $finder;
    }

    public function getName()
    {
        foreach ($this->getCustomFilesFinder()->files()->name('*.info')
            as $file) {
            $this->file = basename($file->getFilename(), ".info");
        };

        return $this->file;
    }
}
