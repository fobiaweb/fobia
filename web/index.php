<?php
/**
 * index.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

require_once __DIR__ . '/boot.php';


$app->get('/', function() {
    echo 'login';
});




$app->run();

// Log::alert('test');

echo '<pre>' . Log::getLogger()->readMemory() . '</pre>';