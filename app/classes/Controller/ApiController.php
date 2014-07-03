<?php
/**
 * ApiController class  - ApiController.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Controller;

/**
 * ApiController class
 *
 * @package   Controller
 */
class ApiController extends \Fobia\Base\Controller
{

    public function index($method)
    {
        var_dump($method);
/*
        $app = \App::instance();
        
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
 * 
 */
    }
}