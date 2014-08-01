<?php
/**
 * session.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
require_once __DIR__ . '/../vendor/autoload.php';

session_id('console');

$handler = new MySessionHandler();

// session_start();
// $_SESSION['data2'] = 22;

var_dump($_SESSION);

session_write_close();

session_id("console2");
@session_start();
// $_SESSION["console2_1"] = time();
//@session_regenerate_id();

var_dump($_SESSION);
