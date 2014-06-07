<?php
/**
 * index.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


require_once __DIR__ . '/../boot.php';

// $app = new \Fobia\Base\Application();
$app = App::instance();

$app->get('/../', function() {})->name('nb');


$app->get('/test', function() use($app) {
    echo "urlFor: ";
    echo $app->urlFor('nb', array('page'=> '11', '22')) . BR;
});



$app->run();
Log::alert('test');