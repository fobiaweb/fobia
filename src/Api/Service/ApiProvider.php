<?php
/**
 * ApiProvider class  - ApiProvider.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Congress\Service;

use Congress\Application;

/**
 * Api class
 *
 * @package   Congress\Service
 */
class ApiProvider
{
    /**
     * @var \Congress\Application
     */
    protected $app;
    protected $map;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->map = include __DIR__ . '/Api/autoload_apimap.php';

        $this->app->hook('api', function ($args = null) use ($app) {
            \Fobia\Log::debug('error api', (array) $args);
            dump($args);
        });
    }


    public function method($method, $params = null)
    {
        $api_dir = __DIR__ . '/Api';

        if (!array_key_exists($method, $this->map)) {
            return "No method";
        }

        list($class, $method) = explode('::', $this->map[$method]);

        $api = new $class($params);
        $response = $api->$method();

        return $response;

        /*
        $api_arr = explode('.', $method);
        $api_class = $api_arr[0];
        $api_method = $api_arr[1];

        $file = $api_dir . '/' . $api_class . '.class.php';
        if (file_exists($file)) {
            include_once $file;
            $api_class = '\\Congress\\Service\\Api\\' . $api_class;

            $api = new $api_class($this->app);
            $response = $api->$api_method($params);
            return $response;
        }
        */
        // ===

        // $file = $api_dir . '/' . $method . '.inc.php';


        // if (file_exists($file)) {
        //     \Fobia\Log::debug('api method', array($file));
        //     $api = new \Congress\Service\ApiMethodFile($this->app, $file);
        //     $response = $api->execute($params);
        //     return $response;
        // }

    }

//return hash_hmac($this->_config['hash_method'], $str, $this->_config['hash_key']);
}