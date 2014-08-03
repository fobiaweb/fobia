<?php
/**
 * auth.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
require_once __DIR__ . '/../vendor/autoload.php';
$app = App::create();

// $app->auth->

$array = array();
$append = array();


$arr1= array('file1', 'file2', 'file3');
$arr2= array('file4', 'file2', 'file5');


$arr = array_merge($arr1, $arr2);
$arr = array_unique($arr);
//var_dump($arr);


require_once __DIR__ . '/../src/Model/CDbColumnSchema.php';

$s = "`type` varchar(20) DEFAULT NULL COMMENT 'Тип файла'";



$column = new CDbColumnSchema();
$column->init('varchar(20)', 11);
var_dump($column);
