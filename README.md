# Drupal RootCanal

[![Build Status](https://travis-ci.org/craychee/rootcanal.svg?branch=master)](https://travis-ci.org/craychee/rootcanal)

This library canals a composer vendor directory and a project's custom files and directories into a Drupal(7) webroot. The webroot is assembled either by symlinking custom directories and contrib modules into the Drupal root (recommended for development) or copying all files into the webroot and removing specified files and directories (recommended for production).

##Usage

The drupal:canal command specifications:  

```
bin/rootcanal
```
Will run generate a drupal root directory inside www using your composer installation and custom files and directories that are in your project's root.

By default, modules, themes, and custom directories will be symlinked into a Drupal root.
You can instead copy all files and directories with:  
```
bin/rootcanal --prod
```

Rootcanal uses the following settings by default:
```yml
default:
  destination_paths:
    root: www
    module: sites/all/modules
    theme: sites/all/themes
    drush: sites/all/drush
    profile: profiles
    vendor: sites/default/vendor
    files_public: sites/default/files
    files_private: sites/default/files-private
    settings: sites/default/settings.php
  source_paths:
    files_public: cnf/files
    files_private: cnf/private
    settings: cnf/settings.php
  finder_settings:
    ignore_dirs: [vendor, cnf]
    custom_file_extensions: [php, inc, module, info, install]
```
You can override the destination and source defaults with your own `rootcanal.yml`, which you should place in your project root. `config/drupal` is a good place for it. See this project's `rootcanal.dist.yml`.

##Credits
This library is a reworking of [drupal/tangler](https://github.com/winmillwill/drupal-tangler) to accommodate the abstraction of configuration, the ability to generate a production artifact, and to make it more testable. All glory belongs to [winmillwill](https://github.com/winmillwill).
