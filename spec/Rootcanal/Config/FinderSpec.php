<?php

namespace spec\Rootcanal\Config;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FinderSpec extends ObjectBehavior
{
    function it_returns_a_finder()
    {
        $this->beConstructedWith(null, null, null, null, null);
        $this->getFinder()->shouldReturnAnInstanceOf('Symfony\Component\Finder\Finder');
    }

    function it_returns_the_source_root_path()
    {
        $this->beConstructedWith('.', null, null, null, null);
        $this->getSourceRoot()->shouldBe(getcwd() . '/.');
    }

    function it_returns_ignored_directories_inside_source_including_destination()
    {
        $this->beConstructedWith(null, 'something', null, null, null);
        $this->getIgnoredDirs()->shouldReturn(array('vendor', 'cnf', 'something'));
    }

    function it_returns_the_trimmed_source_root_path()
    {
        $this->beConstructedWith('something/', null, null, null, null);
        $this->getSourceRoot()->shouldBe(getcwd() . '/something');
    }

    function it_returns_the_source_path_to_public_files()
    {
        $this->beConstructedWith(null, null, 'cnf/files', null, null);
        $this->getFilesPublicSourcePath()->shouldBe('cnf/files');
    }

    function it_returns_the_private_path_to_files()
    {
        $this->beConstructedWith(null, null, null, 'cnf/private', null);
        $this->getFilesPrivateSourcePath()->shouldBe('cnf/private');
    }

    function it_returns_the_settings_path_source()
    {
        $this->beConstructedWith(null, null, null, null, 'cnf/settings.php');
        $this->getSettingsSourcePath()->shouldBe('cnf/settings.php');
    }

    function it_finds_the_module_directory_inside_source()
    {
        $this->beConstructedWith('fixture', null, null, null, null);
        $this->getFinderByType('module')->shouldReturnAnInstanceOf('Symfony\Component\Finder\Finder');
    }

    function it_finds_the_theme_directory_inside_source()
    {
        $this->beConstructedWith('fixture', null, null, null, null);
        $this->getFinderByType('theme')->shouldReturnAnInstanceOf('Symfony\Component\Finder\Finder');
    }

    function it_returns_empty_array_if_there_is_no_type_of_directory_inside_source()
    {
        $this->beConstructedWith('fixture', null, null, null, null);
        $this->getFinderByType('something')->shouldReturn([]);
    }

    function it_finds_drupal_files_inside_source()
    {
        $this->beConstructedWith('fixture', null, null, null, null);
        $this->getCustomFilesFinder()->shouldReturnAnInstanceOf('Symfony\Component\Finder\Finder');
    }

    function it_returns_file_name_of_info_files()
    {
        $this->beConstructedWith('fixture', 'www', null, null, null);
        $this->getName()->shouldReturn('test');
    }

}
