#!/usr/bin/env php
<?php
/**
 * console.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

require_once __DIR__ . '/bootstrap.php';

$_ENV['no_stderr'] = 1;

use Fobia\Console\FobiaApplication;

$application = new FobiaApplication();
$application->add(new Fobia\Console\Command\GetCommand());
$application->add(new Fobia\Console\Command\ApiCreateCommand());
$application->add(new Fobia\Console\Command\ApiSearchCommand());
$application->add(new Fobia\Console\Command\ModelCreateCommand());
$application->run();

