# DevBar plugin for CakePHP

This plugin displays a red bar at the top of all the pages of your application if the debug is enabled.

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

```
composer require richardpiel/dev-bar
```

Then you'll need to load the plugin in your `src/Application.php` file.

```php
$this->addPlugin('DevBar');
```

or you can use the console to do this for you.

```bash
bin/cake plugin load DevBar
```