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

        $class = $this->generateApiClass($method);
        $params = $this->app->request->params();

        $apiMethod = new $class($params);

        $apiMethod->invoke();
        $result = $apiMethod->getFormatResponse();

        if ($_json) {
            Log::getLogger()->enableRender = false;
            $this->app->response->setHeader('Content-Type', 'text/json; charset=utf-8');
            echo \CJSON::encode($result);
        } else {
            dump($result);
        }

 //        if ( ! class_exists($class) || ! method_exists( $class,  $classMethod)) {
//            return array(
//                'error' => array(
//                    'err_msg'  => 'неизвестный метод',
//                    'err_code' => 0,
//                    'method'   =>  $method,
//                    'params'   =>  $params
//                )
//            );
//        }
    }


        /**
     * Генерирует название класса и подключает при необходимости
     *
     * @param string $method
     * @return string
     */
    protected function generateApiClass($method)
    {
        $class = 'Api_' . preg_replace_callback('/^\w|_\w/', function($matches) {
            return strtoupper($matches[0]);
        }, str_replace('.', '_', $method));

        if ( ! class_exists($class)) {
            Log::debug("Class '$class' not autoloaded");
            $list = explode('.', $method);
            array_pop($list);
            array_push($list, $method);

            $file = dirname(__DIR__) . '/Api/' . implode('/', $list) . '.php';
            if (file_exists($file)) {
                require_once $file;
            }
        }

        return $class;
    }
}