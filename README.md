# Drupal RootCanal

[![Build Status](https://travis-ci.org/craychee/rootcanal.svg?branch=master)](https://travis-ci.org/craychee/rootcanal)

This library canals a composer vendor directory and a project's custom files and directories into a Drupal(7) webroot. The webroot is assembled either by symlinking custom directories and contrib modules into the Drupal root (recommended for development) or copying all files into the webroot and removing specified files and directories (recommended for production).

##Usage

The drupal:canal command specifications:  

```
bin/rootcanal
```
Will run generate a drupal root directory inside a `www` directory using your composer installation and custom files and directories that are in your project's root.  

You can override the default name of the destination path with:  
```
bin/rootcanal --destination=docroot
```
You can override the default source path of your custom directories and files with:  
```
bin/rootcanal --source=my_custom_dir
```
By default, modules, themes, and custom directories will be symlinked into a Drupal root.
You can instead copy all files and directories with:  
```
bin/rootcanal --prod
```

##Libraries
Currently, this solution does not elegantly accommodate libraries. You can see an example of how to work around this [here](https://gist.github.com/craychee/950136f52edefab3f839). The next release, currently in dev, will resolve this by allowing for configuration, such as libraries, to be passed in with a yml config file.

##Credits
This library is a reworking of [drupal/tangler](https://github.com/winmillwill/drupal-tangler) to accommodate the abstraction of configuration, the ability to generate a production artifact, and to make it more testable. All glory belongs to [winmillwill](https://github.com/winmillwill).
