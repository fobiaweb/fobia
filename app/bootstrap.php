<?php
/**
 * bootstrap.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
if (defined('BOOTSTRAP_FILE')) {
    return;
} else {
   define('BOOTSTRAP_FILE', true);
}
defined('REMOTE_SERVER') or define('REMOTE_SERVER', true);

// COMPOSER
if (!class_exists('\\Composer\\Autoload\\ClassLoader')) {
    $_ENV['loader']  = require_once __DIR__ . '/../vendor/autoload.php';
}

// Fobia\Base\Utils::isRequire(__FILE__);

// if (Fobia\Base\Utils::isRequire(__FILE__)) {
    // die('Хуй вам');
// }

// CLASSES
if (file_exists(__DIR__ . '/classes/autoload.php')) {
    require_once  __DIR__ . '/classes/autoload.php';
}


// APPRC
$apprc_file = dirname(__DIR__) . '/.apprc';
if (file_exists($apprc_file)) {
    require_once $apprc_file;
}
unset($apprc_file);


// INIT CONSTANTS
defined('SYSPATH') or define('SYSPATH', realpath( dirname(__DIR__)) );

defined('SRC_DIR') or define('SRC_DIR',   __DIR__ );
defined('HTML_DIR') or define('HTML_DIR',   SYSPATH . '/web');

defined('LOGS_DIR') or define('LOGS_DIR',   SYSPATH . "/app/logs");
defined('CACHE_DIR') or define('CACHE_DIR',  SYSPATH . "/app/cache");
defined('CONFIG_DIR') or define('CONFIG_DIR', SYSPATH . "/app/config");

Log::debug('bootstrap init');
