<?php

/*
 * Configuration loader for Rootcanal.
 */

namespace Rootcanal\Config;

use Symfony\Component\Yaml\Yaml;

/**
 * Loads configuration from different sources.
 */
class ConfigLoader
{
    /**
     * @var null|string
     */
    private $confPath;
    /**
     * @var Boolean
     */
    private $profileFound;

    /**
     * Constructs reader.
     *
     * @param string $configurationPath       Configuration file path
     */
    public function __construct($configPath = null)
    {
        $this->configPath = $configPath;
    }

    /**
     * Sets configuration file path.
     *
     * @param null|string $path
     */
    public function setConfigFilePath($path)
    {
        $this->configPath = $path;
    }

    /**
     * Returns configuration file path.
     *
     * @return null|string
     */
    public function getConfigFilePath()
    {
        return $this->configPath;
    }

    /**
     * Reads configuration sequence for specific profile.
     *
     * @param string $profile Profile name
     *
     * @return array
     *
     * @throws Exception
     */
    public function loadConfig($profile = 'default')
    {
        $configs = array();
        $this->profileFound = false;

        if ($this->configPath) {
            foreach ($this->loadFileConfig($this->configPath, $profile) as $config) {
                $configs[] = $config;
            }
        }

        // if specific profile has not been found
        if ('default' !== $profile && !$this->profileFound) {
            throw new Exception(sprintf(
                'Can not find configuration for `%s` profile.',
                $profile
            ));
        }

        return $configs;
    }

    /**
     * Loads information from YAML configuration file.
     *
     * @param string $configPath Config file path
     * @param string $profile    Profile name
     *
     * @return array
     *
     * @throws Exception If config file is not found
     */
    protected function loadFileConfig($configPath, $profile)
    {
        if (!is_file($configPath) || !is_readable($configPath)) {
            throw new Exception(sprintf('Configuration file `%s` not found.', $configPath));
        }

        $basePath = rtrim(dirname($configPath), DIRECTORY_SEPARATOR);
        $config = (array) Yaml::parse(file_get_contents($configPath));

        return $this->loadConfigs($basePath, $config, $profile);
    }

    /**
     * Loads configs for provided config and profile.
     *
     * @param string $basePath
     * @param array  $config
     * @param string $profile
     *
     * @return array
     */
    private function loadConfigs($basePath, array $config, $profile)
    {
        $configs = array();

        // Load default profile from current config if custom profile requested.
        // @TODO allow for multiple profiles.
        if ('default' !== $profile && isset($config['default'])) {
            $configs[] = $config['default'];
        }

        // Then load specific profile from current config.
        if (isset($config[$profile])) {
            $configs[] = $config[$profile];
            $this->profileFound = true;
        }

        return $configs;
    }
}
