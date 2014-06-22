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

if (!class_exists('\\Composer\\Autoload\\ClassLoader')) {    
    require_once __DIR__ . '/../vendor/autoload.php';
}


//echo __FILE__;

defined('SYSPATH') or define('SYSPATH', realpath( dirname(__DIR__)) );

defined('SRC_DIR') or define('SRC_DIR',   __DIR__ );
defined('HTML_DIR') or define('HTML_DIR',   SYSPATH . '/web');

defined('LOGS_DIR') or define('LOGS_DIR',   SYSPATH . "/app/logs");
defined('CACHE_DIR') or define('CACHE_DIR',  SYSPATH . "/app/cache");
defined('CONFIG_DIR') or define('CONFIG_DIR', SYSPATH . "/app/config");
