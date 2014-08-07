<?php
/**
 * RouterController class  - RouterController.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Controller;

use Fobia\Base\Controller;

/**
 * RouterController class
 *
 * @package   Controller
 */
class RouterController extends Controller
{
    public function index($pages = array())
    {
        $self = & $this;
        $app  = & $this->app;

        $router = new \Slim\Router();
        $subRoute = new \Fobia\Base\MapRouter($router, $app['settings']['routes.case_sensitive']);


        $subRoute->group('/router', function () use ($self, $app, $subRoute) {
            $class = "\\" . __CLASS__;

            $subRoute->get('/test', "$class:test");
            $subRoute->get('/page(/:num+)', "$class:pageNum");
            $subRoute->get('/test1', function() {
                echo "OK" . BR;
                echo "\\" . __CLASS__;
            });
        });

        $app->subRun($router);
    }

    public function pageNum($num = array())
    {
        var_dump($num);
    }
    
    public function test()
    {
//        echo "FUNCTION: " . __FUNCTION__ . BR;
//        echo "CLASS: " .__CLASS__ . BR;
//        echo "METHOD: " . __METHOD__ . BR;
//        echo "NAMESPACE: " . __NAMESPACE__ . BR;
//
//        echo "Class:  " . preg_replace("#.*\\\#", "", __CLASS__) . BR;
//        echo $this->app->urlFor('base');
        var_dump($this->app->router->getCurrentRoute());
        var_dump($this->app['router']);
    }
}