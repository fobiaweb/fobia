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
        
        echo $this->generateApiClass($method);

//                if (array_key_exists($method, $this->apimap)) {
//            $map = $this->apimap[$method];
//            $class = array_shift($map);
//            $classMethod = array_shift($map);
//        } else {
//            $class = $this->generateApiClass($method);
//            $classMethod = 'invoke';
//            $map = array();
//        }
//
//
//
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
//
//        $obj = new $class($params);
//        /* @var $obj \AbstractApiInvoke */
//
//        call_user_func_array(array($obj, $classMethod), $map);
//        // $obj->invoke();
//        return $obj->getFormatResponse();




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


        /**
     * Генерирует название класса и подключает при необходимости
     *
     * @param string $method
     * @return string
     */
    protected function generateApiClass($method)
    {
        /*
        $list = explode('.', $method);
        array_pop($list);
        array_push($list, $method);

        $class = implode('/', $list);
        */

        $class = $this->prefixClass . str_replace('.', '_', $method);
        if ( ! class_exists($class)) {
            $file = $this->classDirectory . '/' . $method . '.php';
            if (file_exists($file)) {
                require $file;
            }
        }

        return $class;
    }
}