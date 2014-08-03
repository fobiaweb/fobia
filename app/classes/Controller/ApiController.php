<?php
/**
 * ApiController class  - ApiController.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Controller;

use Log;

/**
 * ApiController class
 *
 * @package   Controller
 */
class ApiController extends \Fobia\Base\Controller
{

    public function index($method)
    {
        $_json = true;
        $_json = $this->app->request->isAjax();

        $params = array_merge($this->app->request->params(), $_FILES);

        $apiHandler = new \Fobia\Api\ApiHandler(dirname(__DIR__) . '/Api');
        $result = $apiHandler->request($method, $params);

        if ($_json) {
            Log::getLogger()->enableRender = false;
            $this->app->response->setHeader('Content-Type', 'text/json; charset=utf-8');
            echo \CJSON::encode($result);
        } else {
            dump($result);
        }
    }
}