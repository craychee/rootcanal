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

    protected function getPackages()
    {
        return $this->rm->getLocalRepository()->getCanonicalPackages();
    }

    /**
     * @param Composer\Package\CompletePackage Instance
     */
    protected function getInstallPath($package)
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
            $this->mapCustomFiles(),
            $this->mapVendor(),
            $this->mapSettings(),
            $this->mapFiles()
        );
    }

    public function clear()
    {
        $this->getFS()
            ->remove(['directory', $this->config->getPathsByType('core')]);
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

    /**
     * @param string $type The type of custom directory (module or theme)
     */
    public function mapCustomByType($type)
    {
        $paths = [];

        if ($finder = $this->finder->getFinderByType($type)) {
            $paths = $this->mapCustom($type, $finder);
        }

        return $paths;
    }

    protected function mapCustomFiles()
    {
        $paths = [];

        if ($name = $this->finder->getName()) {
            $finder = $this->finder->getCustomFilesFinder();
            $paths = $this->mapCustom('custom', $finder, $name);
        }

        return $paths;
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
    protected function mapContrib()
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
    protected function getDrupalType($package)
    {
        if (strpos($package->getType(), 'drupal') === 0) {

            return substr($package->getType(), strlen('drupal-'));
        }

        elseif ($package->getPrettyName() === 'drupal/drupal') {

            return 'core';
        }

        return false;
    }

    /**
     * Given the source and destination path map, build the Drupal root.
     *
     * @param array $map The result of $this->getMap()
     */
    public function mirror($map)
    {
        $fs = $this->getFS();
        $root = $this->finder->getSourceRoot();

        foreach ($map as $type => $pathMap) {
            foreach ($pathMap as $sourcePath => $destinationPath) {
                if ($fs->exists($root . DIRECTORY_SEPARATOR . $sourcePath)) {

                    if ($type === 'core') {
                        $fs->mirror(
                            $root . DIRECTORY_SEPARATOR . $sourcePath,
                            $destinationPath
                        );
                    }

                    elseif ($this->config->isProductionEnabled() === true) {

                        if (is_dir($root . DIRECTORY_SEPARATOR . $sourcePath)) {
                            $fs->mirror(
                                $root . DIRECTORY_SEPARATOR . $sourcePath,
                                $destinationPath
                            );
                        }

                        else {
                            $fs->copy(
                                $root . DIRECTORY_SEPARATOR . $sourcePath,
                                $destinationPath
                            );
                        }
                    }

                    else {
                        $fs->symlink(
                            rtrim(substr($fs->makePathRelative(
                                $root . DIRECTORY_SEPARATOR . $sourcePath,
                                $destinationPath
                            ), 3), DIRECTORY_SEPARATOR),
                            $destinationPath,
                            true
                        );
                    }
                }
            }
        }
    }
}
