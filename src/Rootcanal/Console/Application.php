<?php

namespace Rootcanal\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends BaseApplication
{

    /**
     * Gets the name of the command based on input.
     *
     * @param InputInterface $input The input interface
     *
     * @return string The command name
     */
    protected function getCommandName(IntputInterface $input)
    {
        return 'drupal:canal';
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return array An array of default Command instances
     *
     */
    protected function getDefaultCommands()
    {
        $defaultCommands = parent::getDefaultCommands();
        $defaultCommands[] = new Command();

        return $defaultCommands;
    }

    /**
     * Overridden so that the application doesn't expect the command name
     *  to be the first argument.
     */
    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        $inputDefinition->setArguments();

        return $inputDefinition;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return init
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        parent::doRun($input, $output);
    }
}
