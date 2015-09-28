<?php

/*
 * Configuration Tree loader for Rootcanal.
 */

namespace Rootcanal\Config;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Loads configuration from different sources.
 */
class ConfigTree implements ConfigurationInterface
{
    protected $defaults = array(
        'destination_paths' => array(
            'root'          => 'www',
            'module'        => "sites{DIRECTORY_SEPARATOR}all{DIRECTORY_SEPARATOR}modules",
            'theme'         => "sites{DIRECTORY_SEPARATOR}all{DIRECTORY_SEPARATOR}themes",
            'drush'         => "sites{DIRECTORY_SEPARATOR}all{DIRECTORY_SEPARATOR}drush",
            'profile'       => 'profiles',
            'vendor'        => "sites{DIRECTORY_SEPARATOR}default{DIRECTORY_SEPARATOR}vendor",
            'files_public'  => "sites{DIRECTORY_SEPARATOR}default{DIRECTORY_SEPARATOR}files",
            'files_private' => "sites{DIRECTORY_SEPARATOR}default{DIRECTORY_SEPARATOR}files-private",
            'settings'      => "sites{DIRECTORY_SEPARATOR}default{DIRECTORY_SEPARATOR}settings.php"
        ),
        'source_paths' => array(
           'source_root'   => '',
           'files_public'  => "cnf{DIRECTORY_SEPARATOR}files",
           'files_private' => "cnf{DIRECTORY_SEPARATOR}private",
           'settings'      => "cnf{DIRECTORY_SEPARATOR}settings.php",
        ),
        'finder_settings' => array(
            'ignore_dirs' => ['vendor', 'cnf'],
            'custom_file_extensions' => ['php', 'inc', 'module', 'info', 'install'],
        )
    );

    /**
     * Returns the configuration defaults.
     *
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * Generates the configuration tree.
     *
     * @return NodeInterface
     */
    public function getConfigTreeBuilder()
    {
        $defaults = $this->defaults;
        $tree = new TreeBuilder();
        $rootNode = $tree->root('drupal');
        $rootNode->children()
                ->arrayNode('destination_paths')
                  ->isRequired()
                  ->cannotBeEmpty()
                  ->children()
                    ->scalarNode('root')
                      ->defaultValue($defaults['destination_paths']['root'])
                    ->end()
                    ->scalarNode('module')
                      ->defaultValue($defaults['destination_paths']['module'])
                    ->end()
                    ->scalarNode('theme')
                      ->defaultValue($defaults['destination_paths']['theme'])
                    ->end()
                    ->scalarNode('drush')
                      ->defaultValue($defaults['destination_paths']['drush'])
                    ->end()
                    ->scalarNode('profile')
                      ->defaultValue($defaults['destination_paths']['profile'])
                    ->end()
                    ->scalarNode('vendor')
                      ->defaultValue($defaults['destination_paths']['vendor'])
                    ->end()
                    ->scalarNode('files_public')
                      ->defaultValue($defaults['destination_paths']['files_public'])
                    ->end()
                    ->scalarNode('files_private')
                      ->defaultValue($defaults['destination_paths']['files_private'])
                    ->end()
                    ->scalarNode('settings')
                      ->defaultValue($defaults['destination_paths']['settings'])
                    ->end()
                  ->end()
              ->end()
              ->arrayNode('source_paths')
                  ->isRequired()
                  ->cannotBeEmpty()
                  ->children()
                    ->scalarNode('source_root')
                      ->defaultValue($defaults['source_paths']['source_root'])
                    ->end()
                    ->scalarNode('files_public')
                      ->defaultValue($defaults['source_paths']['files_public'])
                    ->end()
                    ->scalarNode('files_private')
                      ->defaultValue($defaults['source_paths']['files_private'])
                    ->end()
                    ->scalarNode('settings')
                      ->defaultValue($defaults['source_paths']['settings'])
                    ->end()
                 ->end()
               ->end()
               ->arrayNode('finder_settings')
                  ->isRequired()
                  ->cannotBeEmpty()
                  ->children()
                    ->variableNode('ignore_dirs')
                      ->defaultValue($defaults['finder_settings']['ignore_dirs'])
                    ->end()
                    ->variableNode('custom_file_extensions')
                      ->defaultValue($defaults['finder_settings']['custom_file_extensions'])
                    ->end()
                 ->end()
            ->end();

        return $tree;
    }
}
