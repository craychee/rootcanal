<?php

namespace spec\Rootcanal\Config;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OptionsConfigSpec extends ObjectBehavior
{
    function it_says_production_mode_is_enabled_when_true()
    {
        $this->beConstructedWith(true);
        $this->isProductionEnabled()->shouldReturn(true);
    }
}
