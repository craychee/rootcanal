<?php

namespace spec\Rootcanal;

use PhpSpec\ObjectBehavior;
use PhpSpec\Util\Filesystem;
use Prophecy\Argument;

class MapperSpec extends ObjectBehavior
{
    private $destPath;
    private $srcPath;

    function it_is_initializable()
    {
        $this->shouldHaveType('Rootcanal\Mapper');
    }

    function let(Filesystem $fs)
    {
        $this->destPath = realpath(__DIR__.'/../../www');
        $this->srcPath = realpath(__DIR__.'/../../vendor');
    }

    function it_generates_fullDestPath_from_srcPath(Filesystem $fs)
    {
        $this->beConstructedWith($this->srcPath, $this->destPath);
        $fs->pathExists($this->destPath)->willReturn(true);
    }
}
