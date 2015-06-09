<?php

namespace Rootcanal\Console;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Single command, responsible for running the application
 */
class Command extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('drupal:canal')
            ->setDefinition(array(
                    new InputArgument(
                        'source',
                        InputArgument::OPTIONAL,
                        'Path to source of the project'
                    ),
                    new InputArgument(
                        'destination',
                        InputArgument::OPTIONAL,
                        'Path to destination of the project',
                        'www'
                    ),
                    new InputOption(
                        'production',
                        null,
                        InputOption::VALUE_NONE,
                        'Copy all files and directories'
                    )
                ))
            ->setDescription('Build a canal between composer and a working Drupal Application')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command specifications:

  <info>php %command.full_name%</info>

Will run all the specifications in the spec directory.

You can choose the bootstrap file with the bootstrap option e.g.:

  <info>php %command.full_name% --bootstrap=bootstrap.php</info>

By default, you will be asked whether missing methods and classes should
be generated. You can suppress these prompts and automatically choose not
to generate code with:

  <info>php %command.full_name% --no-code-generation</info>

You can choose to stop on failure and not attempt to run the remaining
specs with:

  <info>php %command.full_name% --stop-on-failure</info>

You can opt to automatically fake return values with:

  <info>php %command.full_name% --fake</info>

You can choose the output format with the format option e.g.:

  <info>php %command.full_name% --format=dot</info>

The available formatters are:

   progress (default)
   html
   pretty
   junit
   dot
   tap

EOF
            )
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
