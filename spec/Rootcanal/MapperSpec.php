<?php

namespace spec\Rootcanal;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Rootcanal\Config\Config;
use Rootcanal\Config\Finder;
use Composer\Installer\InstallationManager;
use Composer\Repository\RepositoryManager;

class MapperSpec extends ObjectBehavior
{

    private $prophet;

    function let(
        Finder $finder,
        Config $config,
        InstallationManager $im,
        RepositoryManager $rm
    )
    {
        $this->beConstructedWith($config, $finder, $im, $rm);
        $this->prophet = new \Prophecy\Prophet;
    }

    function it_returns_an_instance_of_filesystem()
    {
        $this->getFS()->shouldReturnAnInstanceOf('Symfony\Component\Filesystem\Filesystem');
    }

    function it_returns_the_files_source_path_and_destination(Finder $finder, Config $config)
    {
        $finder->getFilesPublicSourcePath()->willReturn('cnf/files');
        $config->getPathsByType('files-public')->willReturn('www/sites/default/files');
        $finder->getFilesPrivateSourcePath()->willReturn('cnf/private');
        $config->getPathsByType('files-private')->willReturn('www/sites/default/files-private');
        $this->mapFiles()->shouldHaveKey('files-private');
        $this->mapFiles()->shouldHavePath(['cnf/private' => 'www/sites/default/files-private']);
        $this->mapFiles()->shouldHaveKey('files-public');
        $this->mapFiles()->shouldHavePath(['cnf/files' => 'www/sites/default/files']);
    }

    function it_returns_the_settings_path(Finder $finder, Config $config)
    {
        $finder->getSettingsSourcePath()->willReturn('cnf/settings.php');
        $config->getPathsByType('settings')->willReturn('www/sites/default/settings.php');
        $this->mapSettings()->shouldHaveKey('settings');
        $this->mapSettings()->shouldHavePath(['cnf/settings.php' => 'www/sites/default/settings.php']);
    }

    function it_returns_the_vendor_path(Finder $finder, Config $config)
    {
        $config->getPathsByType('vendor')->willReturn('www/sites/default/vendor');
        $this->mapVendor()->shouldHaveKey('vendor');
        $this->mapVendor()->shouldHavePath(['vendor' => 'www/sites/default/vendor']);
    }

    function it_maps_custom_directories_by_type(Finder $finder)
    {
        $type = 'module';
        $prophecy = $this->prophet->prophesize();
        $prophecy->willExtend('Symfony\Component\Finder\SplFileInfo');
        $prophecy->getRealPath()->willReturn(getcwd() . '/fixture/modules/README.md');
        $prophecy->getFilename()->willReturn('README.md');
        $finder->getSourceRoot()->willReturn(getcwd() . '/fixture');
        $this->mapCustom($type, array($prophecy->reveal()))->shouldHaveKey("{$type}s");
        $this->mapCustom($type, array($prophecy->reveal()))->shouldHavePathWithKey("{$type}s", 'modules/README.md');
        $this->prophet->checkPredictions();
    }

    public function getMatchers()
    {
        return [
            'haveKey' => function ($map, $type) {
                return array_key_exists($type, $map);
            },
            'havePath' => function($map, $pathMap) {
                return in_array($pathMap, $map);
            },
            'havePathWithKey' => function($map, $type, $pathMap) {
                return array_key_exists($pathMap, $map[$type]);
            },
        ];
    }

}
