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

register_shutdown_function(function(){
    echo "\n" . Fobia\Base\Utils::resourceUsage();
});

$application = new FobiaApplication();
$application->add(new Console\Command\GetCommand());
$application->add(new Console\Command\ApiCreateCommand());
$application->add(new Console\Command\ApiSearchCommand());
$application->run();

