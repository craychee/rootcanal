<?php

namespace spec\Rootcanal\Config;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigSpec extends ObjectBehavior
{

    function it_says_production_mode_is_enabled_when_true()
    {
        $this->beConstructedWith(true, null, null, null, null, null, null, null, null);
        $this->isProductionEnabled()->shouldReturn(true);
    }

    function it_should_have_specified_drupal_destinations_by_default()
    {
        $this->beConstructedWith(
            true,
            'www',
            '/sites/all/modules/%s',
            '/sites/all/themes/%s',
            '/sites/all/drush/%s',
            '/profiles/%s',
            '/sites/default/files',
            '/sites/default/files-private',
            '/sites/default/settings.php'
        );
        $this->getPaths()->shouldHaveKey('core');
        $this->getPaths()->shouldHaveValue(getcwd() . '/www');
        $this->getPaths()->shouldHaveKey('module');
        $this->getPaths()->shouldHaveValue(getcwd() . '/www/sites/all/modules/%s');
        $this->getPaths()->shouldHaveKey('theme');
        $this->getPaths()->shouldHaveValue(getcwd() . '/www/sites/all/themes/%s');
        $this->getPaths()->shouldHaveKey('drush');
        $this->getPaths()->shouldHaveValue(getcwd() . '/www/sites/all/drush/%s');
        $this->getPaths()->shouldHaveKey('profile');
        $this->getPaths()->shouldHaveValue(getcwd() . '/www/profiles/%s');
        $this->getPaths()->shouldHaveKey('files-public');
        $this->getPaths()->shouldHaveValue(getcwd() . '/www/sites/default/files');
        $this->getPaths()->shouldHaveKey('files-private');
        $this->getPaths()->shouldHaveValue(getcwd() . '/www/sites/default/files-private');
        $this->getPaths()->shouldHaveKey('settings');
        $this->getPaths()->shouldHaveValue(getcwd() . '/www/sites/default/settings.php');
    }


    function it_should_have_return_drupal_destinations_defaults_by_type()
    {
        $this->beConstructedWith(
            true,
            'www',
            '/sites/all/modules/%s',
            '/sites/all/themes/%s',
            '/sites/all/drush/%s',
            '/profiles/%s',
            '/sites/default/files',
            '/sites/default/files-private',
            '/sites/default/settings.php'
        );
        $this->getPathsByType('core')->shouldReturn(getcwd() . '/www');
        $this->getPathsByType('module')->shouldReturn(getcwd() . '/www/sites/all/modules/%s');
        $this->getPathsByType('theme')->shouldReturn(getcwd() . '/www/sites/all/themes/%s');
        $this->getPathsByType('drush')->shouldReturn(getcwd() . '/www/sites/all/drush/%s');
        $this->getPathsByType('profile')->shouldReturn(getcwd() . '/www/profiles/%s');
        $this->getPathsByType('files-public')->shouldReturn(getcwd() . '/www/sites/default/files');
        $this->getPathsByType('files-private')->shouldReturn(getcwd() . '/www/sites/default/files-private');
        $this->getPathsByType('settings')->shouldReturn(getcwd() . '/www/sites/default/settings.php');
    }

    function it_returns_the_trimmed_destination_path()
    {
        $this->beConstructedWith(
            null,
            'www/',
            null,
            null,
            null,
            null,
            null,
            null,
            null
        );
        $this->getPathsByType('core')->shouldBe(getcwd() . '/www');
    }

    function it_returns_the_vendor_destination_path()
    {
        $this->beConstructedWith(
            null,
            'www',
            null,
            null,
            null,
            null,
            null,
            null,
            null
        );
        $this->getPathsByType('vendor')->shouldBe(getcwd() . '/www/sites/default/vendor');
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
