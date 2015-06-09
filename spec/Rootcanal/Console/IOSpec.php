<?php

namespace spec\Rootcanal\Console;

use Rootcanal\Config\OptionsConfig;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Composer\IO\ConsoleIO;

class IOSpec extends ObjectBehavior
{
    function let(
        InputInterface $input,
        OutputInterface $output,
        OptionsConfig $config
    ) {

       $input->getOption('destination')->willReturn('.');
       $input->getOption('source')->willReturn('www/');
       $this->beConstructedWith($input, $output, $config);
    }

    function it_has_io_interface()
    {
      $this->shouldHaveType('Rootcanal\IO\IOInterface');
    }

    function it_will_disable_production_mode_if_command_line_option_and_config_flag_are_not_set($input, $config)
    {
        $input->getOption('production')->willReturn(false);
        $config->isProductionEnabled()->willReturn(false);
        $this->isProductionEnabled()->shouldReturn(false);
    }

   function it_will_enable_production_mode_if_command_line_option_is_set($input, $config)
    {
        $input->getOption('production')->willReturn(true);
        $config->isProductionEnabled()->willReturn(false);
        $this->isProductionEnabled()->shouldReturn(true);
    }

   function it_will_enable_production_mode_if_config_option_is_set($input, $config)
    {
        $input->getOption('production')->willReturn(false);
        $config->isProductionEnabled()->willReturn(true);
        $this->isProductionEnabled()->shouldReturn(true);
    }

    function it_loads_composer()
    {
        $this->getComposer()->shouldReturnAnInstanceOf('Composer\Composer');
    }

    function it_instatiates_console_io_instance()
    {
        $this->io->shouldReturnAnInstanceOf('Composer\IO\ConsoleIO');
    }

}
