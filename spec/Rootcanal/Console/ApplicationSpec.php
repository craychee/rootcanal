<?php

namespace spec\Rootcanal\Console;

use Symfony\Component\Console\Input\InputInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ApplicationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rootcanal\Console\Application');
    }
}
