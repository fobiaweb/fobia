<?php
/**
 * index.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

require_once __DIR__ . '/boot.php';

require_once __DIR__ . '/auth-inc.php';

/* @var $app \Fobia\Base\Application */

$app->get('/', function() {
    echo 'login';
});

$app->any('/api/:method', function($method) use($app) {
    dump($method);
});




$app->run();

// Log::alert('test');

echo '<pre>' . Log::getLogger()->readMemory() . '</pre>';