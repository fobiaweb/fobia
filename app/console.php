#!/usr/bin/env php
<?php
/**
 * console.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

require_once __DIR__ . '/../vendor/autoload.php';


use Console\FobiaApplication;
use Symfony\Component\Console\Application;

$application = new Fobia\Base\ConsoleApplication();
$application->add(new Console\Command\GetCommand());
$application->add(new Console\Command\ApiCreateCommand());
$application->add(new Console\Command\ApiSearchCommand());
$application->run();

