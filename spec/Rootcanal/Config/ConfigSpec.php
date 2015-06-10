<?php

namespace spec\Rootcanal\Config;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigSpec extends ObjectBehavior
{

    function it_says_production_mode_is_enabled_when_true()
    {
        $this->beConstructedWith(true, null, null, null, null, null);
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
            '/profiles/%s'
        );
        $this->getPaths()->shouldHaveKey('core');
        $this->getPaths()->shouldHaveValue('www');
        $this->getPaths()->shouldHaveKey('module');
        $this->getPaths()->shouldHaveValue('www/sites/all/modules/%s');
        $this->getPaths()->shouldHaveKey('theme');
        $this->getPaths()->shouldHaveValue('www/sites/all/themes/%s');
        $this->getPaths()->shouldHaveKey('drush');
        $this->getPaths()->shouldHaveValue('www/sites/all/drush/%s');
        $this->getPaths()->shouldHaveKey('profile');
        $this->getPaths()->shouldHaveValue('www/profiles/%s');
    }


    function it_should_have_return_drupal_destinations_defaults_by_type()
    {
        $this->beConstructedWith(
            true,
            'www',
            '/sites/all/modules/%s',
            '/sites/all/themes/%s',
            '/sites/all/drush/%s',
            '/profiles/%s'
        );
        $this->getPathsByType('core')->shouldReturn('www');
        $this->getPathsByType('module')->shouldReturn('www/sites/all/modules/%s');
        $this->getPathsByType('theme')->shouldReturn('www/sites/all/themes/%s');
        $this->getPathsByType('drush')->shouldReturn('www/sites/all/drush/%s');
        $this->getPathsByType('profile')->shouldReturn('www/profiles/%s');
       # $this->getPathsByType('files-public')->shouldReturn('www/sites/default/files');
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
