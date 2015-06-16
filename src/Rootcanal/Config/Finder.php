<?php

namespace Rootcanal\Config;

use Symfony\Component\Finder\Finder as BaseFinder;

class Finder
{
    /*
     * @var string
     */
    private $name;

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
        $fullSourceRoot = implode(DIRECTORY_SEPARATOR, [getcwd(), $this->sourceRoot]);

        return $fullSourceRoot;
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

    /**
     * @param string $type The type of custom directory to search for.
     *
     * @return Finder instance
     */
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

    /**
     * @param bool $custom Custom file extension to filter.
     *
     * @return Finder instance
     */
    public function getCustomFilesFinder($custom = false)
    {
        $finder = $this->getFinder()
            ->ignoreUnreadableDirs()
            ->depth('== 0')
            ->in($this->getSourceRoot())
            ->exclude($this->getIgnoredDirs());

        if ($custom) {
            $finder->name("*.{$custom}");
        }

        else {
            foreach ($this->customFileExtentions as $extension) {
                $finder->name("*.{$extension}");
            }
        }

        return $finder;
    }

    /**
     * @return string $name
     */
    public function getName()
    {
        foreach ($this->getCustomFilesFinder('info')->files() as $file) {
            $this->name = basename($file->getFilename(), ".info");
        };

        return $this->name;
    }
}
