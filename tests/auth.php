<?php
/**
 * auth.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
require_once __DIR__ . '/../vendor/autoload.php';
$app = App::create();

$app['tetsKey'] = 'valueKey';

var_dump($app);
