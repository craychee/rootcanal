<?php

namespace spec\Rootcanal\Console;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ApplicationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rootcanal\Console\Application');
    }

    function it_returns_the_name_of_command()
    {
        $this->getCommandName()->shouldReturn('drupal:canal');
    }


}
