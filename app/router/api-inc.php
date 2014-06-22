<?php
/**
 * api-inc.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


$app->any('/api/:method', function($method) use($app) {
    $api = new \Api\ApiHandler();

    $params = $app->request->params();
    $result = $api->request($method, $params);
    var_dump($result);
//    $app->response->setHeader('Content-Type', 'text/json; charset=utf-8');
    // print_r($d);
//    echo json_encode($d);
})->name('api');