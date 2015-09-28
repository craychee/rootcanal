<?php

namespace Rootcanal\Config;

use Rootcanal\Config\Config;
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
    private $config;

    public function __construct(
         $config
    )
    {
        $this->config = $config;
    }

    public function getSourceRoot()
    {
        $root = $this->config->getSourceConfig()['source_root'];
        $fullSourceRoot = implode(DIRECTORY_SEPARATOR, [getcwd(), $root]);

        return $fullSourceRoot;
    }

    public function getIgnoredDirs()
    {
        $ignoreSourceDirs = $this->config->getFinderConfig()['ignore_dirs'];
        $ignoreSourceDirs[] = $this->config->getDestination();

        return $ignoreSourceDirs;
    }

    public function getFileExt()
    {
        return $this->config->getFinderConfig()['custom_file_extensions'];
    }

    public function getFilesPublicSourcePath()
    {
        return $this->config->getSourceConfig()['files_public'];
    }

    public function getFilesPrivateSourcePath()
    {
        return $this->config->getSourceConfig()['files_private'];
    }

    public function getSettingsSourcePath()
    {
        return $this->config->getSourceConfig()['settings'];
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
            foreach ($this->getFileExt() as $extension) {
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
