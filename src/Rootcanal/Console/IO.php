<?php

namespace Rootcanal\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Rootcanal\Config\Config;
use Rootcanal\IO\IOInterface;
use Composer\IO\ConsoleIO;
use Composer\Factory;

class IO implements IOInterface
{

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    private $input;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    /**
      * @var OptionsConfig
      */
    private $config;

    /**
     * @var \Composer\Composer
     */
    private $composer;

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param Config   $config
     */
    public function __construct(
        InputInterface $input,
        OutputInterface $output,
        Config $config
     ) {
        $this->input   = $input;
        $this->output  = $output;
        $this->config  = $config;
        $this->io = new ConsoleIO($this->input, $this->output, $this->getHelpers());
    }

    public function isProductionEnabled()
    {
        return $this->input->getOption('production') || $this->config->isProductionEnabled();
    }

    public function getHelpers()
    {
        $helpers = new HelperSet;
        return $helpers;
    }

    public function getComposer($required = true, $disablePlugins = false)
    {
        if (null === $this->composer) {
            try {
                $this->composer = Factory::create($this->io, null, $disablePlugins);
            } catch (\InvalidArgumentException $e) {
                if ($required) {
                    $this->io->write($e->getMessage());
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

    public function write($message)
    {
        $this->output->write($message);
    }
}
