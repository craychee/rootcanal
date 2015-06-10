<?php

namespace spec\Rootcanal\Config;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FinderSpec extends ObjectBehavior
{

    function it_returns_the_source_path_to_public_files()
    {
        $this->beConstructedWith(null, 'cnf/files', null, null);
        $this->getFilesPublicSourcePath()->shouldBe('cnf/files');
    }

    function it_returns_the_private_path_to_files()
    {
        $this->beConstructedWith(null, null, 'cnf/private', null);
        $this->getFilesPrivateSourcePath()->shouldBe('cnf/private');
    }

    function it_returns_the_settings_path_source()
    {
        $this->beConstructedWith(null, null, null, 'cnf/settings.php');
        $this->getSettingsSourcePath()->shouldBe('cnf/settings.php');
    }

    public function getMatchers()
    {
        return [
            'haveKey' => function($path, $key) {
                return array_key_exists($key, $path);
            },
            'haveValue' => function($path, $value) {
                return in_array($value, $path);
            },
        ];
    }
}
