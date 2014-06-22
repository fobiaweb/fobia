<?php
/**
 * api-inc.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

$app = App::instance();

$app->any('/api/:method', function($method) use($app) {
    $api = new \Api\ApiHandler();

    $params = $app->request->params();
    $result = $api->request($method, $params);

    if ($app->request->isAjax()) {
        \Log::getLogger()->enableRender = false;
        $app->response->setHeader('Content-Type', 'text/json; charset=utf-8');
        echo CJSON::encode($result);
    } else {
        dump($result);
    }
})->name('api');