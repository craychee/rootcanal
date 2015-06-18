<?php

namespace Rootcanal\Console;

use Rootcanal\Config\Config;
use Rootcanal\Config\Finder;
use Rootcanal\Mapper;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Composer\Factory;

/**
 * Single command, responsible for running the application
 */
class Command extends BaseCommand
{
    /*
     * @var Composer
     */
    private $composer = null;

    protected function configure()
    {
        $this
            ->setName('drupal:canal')
            ->setDefinition(array(
                new InputOption(
                    'source',
                    's',
                    InputOption::VALUE_OPTIONAL,
                    'Path to source of the custom files and directories'
                ),
                new InputOption(
                    'destination',
                    'd',
                    InputOption::VALUE_REQUIRED,
                    'Path to destination of the project',
                    'www'
                ),
                new InputOption(
                    'prod',
                    false,
                    InputOption::VALUE_NONE,
                    'Generate production artifact from source'
                ),
                new InputOption(
                    'clean',
                    'c',
                    InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                    'Remove these files when generating production artifact.',
                    array('*.md', '*.txt', '*.install', 'LICENSE')
                )
            ))
            ->setDescription('Build a canal between composer and a working Drupal Application')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command specifications:

  <info>%command.full_name%</info>

Will run generate a drupal root directory inside 'www' using your composer installation and custom files and directories that are in your project's root.

You can override the default name of the destination path with:

  <info>%command.full_name% --destination=docroot</info>

You can override the default source path of your project with:

  <info>%command.full_name% --source=my_custom_dir</info>

By default, modules, themes, and custom directories will be symlinked into a Drupal root.
You can instead copy all files and directories with:

  <info>%command.full_name% --prod</info>

Also by default, when the production is enabled, files and directories matching '*.md', '*.txt', '*.install', 'LICENSE' will be removed. This can be overridden with:

  <info>%command.full_name% --clean=['custom']</info>
EOF
        )
            ;
    }

    /**
     * This method has been lifted from Composer/Console/Application to access
     * an instance of composer outside the context of a composer command event.
     *
     * @param  bool $required
     * @param  bool $disablePlugins
     *
     * @throws JsonValidationException
     * @return \Composer\Composer
     */
    public function getComposer($required = true, $disablePlugins = false)
    {
        $io = $this->getApplication()->getIO();

        if (null === $this->composer) {
            try {
                $this->composer = Factory::create($io, null, $disablePlugins);
            } catch (\InvalidArgumentException $e) {
                if ($required) {
                    $io->write($e->getMessage());
                    exit(1);
                }
            } catch (JsonValidationException $e) {
                $errors = ' - ' . implode(PHP_EOL . ' - ', $e->getErrors());
                $message = $e->getMessage() . ':' . PHP_EOL . $errors;
                throw new JsonValidationException($message);
            }
        }

        return $this->composer;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return mixed
     *
     * @TODO instantiate instances of Config and Finder with default yml.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new Config(
            $input->getOption('prod'),
            $input->getOption('destination'),
            $input->getOption('clean'),
            '/sites/all/modules/%s',
            '/sites/all/themes/%s',
            '/sites/all/drush/%s',
            '/profiles/%s',
            '/sites/default/files',
            '/sites/default/files-private',
            '/sites/default/settings.php'
        );

        $finder = new Finder(
             $input->getOption('source'),
            $input->getOption('destination'),
            'cnf/files',
            'cnf/private',
            'cnf/settings.php'
        );

        $im = $this->getComposer()->getInstallationManager();
        $rm = $this->getComposer()->getRepositoryManager();

        $mapper = new Mapper($config, $finder, $im, $rm);
        $mapper->clear();
        $mapper->mirror($mapper->getMap());
        $mapper->clean();
    }
}
