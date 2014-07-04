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



//$l = \Model\Department::getItem(42);

//dump($l->getParents());


$file = SYSPATH . '/app/classes/Api/auth/auth.login.php';
$str = file_get_contents($file);
preg_match_all('/@api\s+([\w\.]+).*(class Api)/i', $str, $m);

var_dump($m);

