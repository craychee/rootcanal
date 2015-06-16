<?php

namespace Rootcanal;

use Symfony\Component\Filesystem\Filesystem;
use Rootcanal\Config\Config;
use Rootcanal\Config\Finder;
use Composer\Installer\InstallationManager;
use Composer\Repository\RepositoryManager;

class Mapper
{
    /*
     * @param Filesystem $fs
     */
    private $fs;

    /*
     * @param Config $config
     */
    private $config;

    /*
     * @param Finder $finder
     */
    private $finder;

    /*
     * @param InstallationManager $im
     */
    private $im;

    /*
     * @param RepositoryManager $rm
     */
    private $rm;

    public function __construct(
        Config $config,
        Finder $finder,
        InstallationManager $im,
        RepositoryManager $rm
    )
    {
        $this->config = $config;
        $this->finder = $finder;
        $this->im = $im;
        $this->rm = $rm;
    }

    public function getFS()
    {
        return $this->fs ? $this->fs : $this->fs = new Filesystem();
    }

    public function getPackages()
    {
        return $this->rm->getLocalRepository()->getCanonicalPackages();
    }

    /**
     * @param Composer\Package\CompletePackage Instance
     */
    public function getInstallPath($package)
    {
        return $this->im
            ->getInstaller($package->getType())
            ->getInstallPath($package);
    }

    public function getMap()
    {
        return array_merge(
            $this->mapContrib(),
            $this->mapCustomByType('module'),
            $this->mapCustomByType('theme'),
            #$this->mapCustomFiles(),
            $this->mapSettings(),
            $this->mapFiles()
        );
    }

    public function mapFiles()
    {
        return [
            'files-public' => [
            $this->finder->getFilesPublicSourcePath() =>
            $this->config->getPathsByType('files-public')],
                'files-private' => [
                $this->finder->getFilesPrivateSourcePath() =>
                $this->config->getPathsByType('files-private')],
                    ];
    }

    public function mapSettings()
    {
        return [
            'settings' => [
            $this->finder->getSettingsSourcePath() =>
            $this->config->getPathsByType('settings')
            ]
            ];
    }

    public function mapVendor()
    {
        return [
            'vendor' => [
            'vendor' => $this->config->getPathsByType('vendor')
            ]
            ];
    }

    public function mapCustomByType($type)
    {
        $finder = $this->finder->getFinderByType($type);
        $paths = $this->mapCustom($type, $finder);

        return $paths;
    }

    public function mapCustomFiles()
    {
        if ($name = $this->finder->getName()) {
            $finder = $this->finder->getCustomFilesFinder();
            $paths = $this->mapCustom('custom', $finder, $name);

            print_r($paths);
            return $paths;
        }
    }

    /**
     * Build a source and a destination path to each file of the Finder
     * instance relative to the project's source and absolute to the destination.
     *
     * @param string $type  The type of directory (module, theme, custom)
     * @param Finder $finder An instance of Finder
     * @param string $custom The name of the custom directory, if applicable
     *
     * @returns array $paths
     */
    public function mapCustom($type, $finder, $custom = null)
    {
        $paths = [];
        $root = $this->finder->getSourceRoot();

        foreach ($finder as $file) {
            $sourcePath = rtrim(
                $this->getFS()->makePathRelative(
                    $file->getRealpath(),
                    $root
                ), DIRECTORY_SEPARATOR
            );

            if ($custom) {
                $paths[$type][$sourcePath] = sprintf(
                    $this->config->getPathsByType($type),
                    $custom,
                    $file->getFilename()
                );
            }

            else {
                $paths["{$type}s"][$sourcePath] = sprintf(
                    $this->config->getPathsByType($type),
                    $file->getFilename()
                );
            }
        }

        return $paths;
    }

    /**
     * Build a source and a destination path to each file of the Finder
     * instance relative to the project's source and absolute to the destination.
     *
     * @returns array $paths
     */
    public function mapContrib()
    {
        $root    = $this->finder->getSourceRoot();
        $pathMap = $this->config->getPaths();
        $paths   = [];

        foreach ($this->getPackages() as $package) {

            if ($drupalType = $this->getDrupalType($package)) {
                $sourcePath = $this->getInstallPath($package);

                if (strpos($sourcePath, $root) !== false) {
                    $sourcePath = $this->getFS()->makePathRelative(
                        $sourcePath,
                        $root
                    );
                }

                $mapRef =& $paths[$drupalType][rtrim($sourcePath, DIRECTORY_SEPARATOR)];

                if (in_array($drupalType, ['module', 'theme'])) {
                    $mapRef = sprintf(
                        $pathMap[$drupalType] . DIRECTORY_SEPARATOR . '%s',
                        'contrib',
                        basename($package->getPrettyName())
                    );
                }

                else {
                    $mapRef = $pathMap[$drupalType];
                }
            }
        }

        return array_intersect_key($paths, $pathMap);
    }

    /**
     * @param Composer\Package\CompletePackage Instance
     */
    public function getDrupalType($package)
    {
        if (strpos($package->getType(), 'drupal') === 0) {

            return substr($package->getType(), strlen('drupal-'));
        }

        elseif ($package->getPrettyName() === 'drupal/drupal') {

            return 'core';
        }

        return false;
    }
}
