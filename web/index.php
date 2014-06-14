<?php
/**
 * index.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

require_once __DIR__ . '/boot.php';

require_once __DIR__ . '/auth-inc.php';

/* @var $app \Fobia\Base\Application */

$app->get('/', function() {
    echo 'login';
});

$app->any('/api/:method', function($method) use($app) {
    $api = new \Api\ApiMethod();
    $api->method = $method;
    if($api->execute()) {
        $d = array( 'response' => $api->getResponse() )   ;
    }
    else {
        $app->response->setStatus(400);
        $d = array( 'error' => $api->errorInfo() );
    }
//    $app->response->setHeader('Content-Type', 'text/json; charset=utf-8');
    // print_r($d);
    echo json_encode($d);
})->name('api');

$app->any('/test(/:h+)', $app->createController('\\Controller::indexAction'));


$app->run();

// Log::alert('test');

 echo  Log::getLogger()->render() ;