<?php
/**
 * autoload-config.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


require_once __DIR__ . '/../vendor/autoload.php';

$cfg = new \Fobia\Base\AutoCfg(CONFIG_DIR);

var_dump($cfg['access']);
var_dump($cfg['reles']);

