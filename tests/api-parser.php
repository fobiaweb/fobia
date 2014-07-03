<?php
/**
 * api-parser.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


require_once 'bootstrap.php';

$api = 'utils.getServerTime';


$result = 'Api_' . preg_replace_callback('/^\w|_\w/', function($matches) {
    return strtoupper($matches[0]);
}, str_replace('.', '_', $api));


dump($result);