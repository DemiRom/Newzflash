<?php
// YOU SHOULD NOT EDIT ANYTHING IN THIS FILE,
// COPY .../newzflash/config/settings.example.php TO .../newzflash/config/settings.php AND EDIT THAT FILE!

define('NEWZFLASH_MINIMUM_PHP_VERSION', '7.0.20');

define('DS', DIRECTORY_SEPARATOR);

// These are file path constants
define('NEWZFLASH_ROOT', realpath(dirname(__DIR__)) . DS);

// Used to refer to the main lib class files.
define('NEWZFLASH_LIB', NEWZFLASH_ROOT . 'newzflash' . DS);
define('NEWZFLASH_CORE', NEWZFLASH_LIB);

define('NEWZFLASH_CONFIGS', NEWZFLASH_ROOT . 'configuration' . DS);

// Used to refer to the third party library files.
define('NEWZFLASH_LIBS', NEWZFLASH_ROOT . 'libraries' . DS);

// Used to refer to the /misc class files.
define('NEWZFLASH_MISC', NEWZFLASH_ROOT . 'misc' . DS);

// /misc/update/
define('NEWZFLASH_UPDATE', NEWZFLASH_MISC . 'update' . DS);

// /misc/update/nix/
define('NEWZFLASH_NIX', NEWZFLASH_UPDATE . 'nix' . DS);

// /misc/update/nix/multiprocessing/
define('NEWZFLASH_MULTIPROCESSING', NEWZFLASH_NIX . 'multiprocessing' . DS);

// Used to refer to the resources folder
define('NEWZFLASH_RES', NEWZFLASH_ROOT . 'resources' . DS);

// Path where log files are stored.
define('NEWZFLASH_LOGS', NEWZFLASH_RES . 'logs' . DS);

// Smarty's cache.
define('NEWZFLASH_SMARTY_CACHE', NEWZFLASH_RES . 'smarty' . DS . 'cache/');

// Smarty's configuration files.
define('NEWZFLASH_SMARTY_CONFIGS', NEWZFLASH_RES .'smarty' . DS . 'configs/');

// Smarty's compiled template cache.
define('NEWZFLASH_SMARTY_TEMPLATES', NEWZFLASH_RES . 'smarty' . DS . 'templates_c/');

// Used to refer to the tmp folder
define('NEWZFLASH_TMP', NEWZFLASH_RES . 'tmp' . DS);

// Refers to the web root for the Smarty lib
define('NEWZFLASH_WWW', NEWZFLASH_ROOT . 'www' . DS);

// Full path is fs to the themes folder
define('NEWZFLASH_THEMES', NEWZFLASH_WWW . 'themes' . DS);

// Shared theme items (pictures, scripts).
define('NEWZFLASH_THEMES_SHARED', NEWZFLASH_THEMES . 'shared' . DS);

define('NEWZFLASH_VERSIONS', NEWZFLASH_ROOT . 'build' . DS . 'newzflash.xml');

define('INSTALLED', NEWZFLASH_CONFIGS . 'install.lock');
