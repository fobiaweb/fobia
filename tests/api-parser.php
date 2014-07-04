<?php
/**
 * api-parser.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


require_once 'bootstrap.php';

$app = new \Fobia\Base\Application( __DIR__ . '/../app/config/config.php' );

//dump($app->db);



$l = \Model\Department::getItem(42);

dump($l->getParents());
