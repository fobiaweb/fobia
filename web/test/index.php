<?php
/**
 * index.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

// $_SERVER['SCRIPT_NAME'] = '/fobia/index.php';

require_once __DIR__ . '/../boot.php';

// $app = new \Fobia\Base\Application();
$app = App::instance();

$app->get('/../', function() {})->name('base1');


$app->get('/test1', function() use($app) {
    echo "urlFor: ";
    // echo $app->urlFor('nb', array('page'=> '11', '22')) . BR;
    echo $app->urlFor('base') . BR;
    dump($app['environment']);
});
$app->get('/test2', function() use($app) {
    $d = glob(__DIR__ . '/../../app/**.php');
    dump($d);
});

$app->run();
// Log::alert('test');