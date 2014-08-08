<?php
/**
 * RouterController class  - RouterController.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Controller;

use Fobia\Base\Controller;
use Fobia\Debug\Log;

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

        $app->hook('slim.before.dispatch', function() use($app) {
            echo 'slim.before.dispatch' . BR;
        });

        $subRoute = new \Fobia\Base\MapRouter($app);

        $subRoute->group('/router', function () use ($self, $app, $subRoute) {
            $class = "\\" . __CLASS__;

            $subRoute->get('/test', "$class:test");
            $subRoute->get('/page(/:num+)', "$class:pageNum");


            $subRoute->get('/test1', function() use ($app) {
                Log::debug("/test1 :: function-1 :: pass()");
                $app->pass();
            });

            $subRoute->get('/test1', function() use ($app) {
                Log::debug("/test1 :: function-2 :: pass()");
                $app->pass();
            });

            $subRoute->get('/test1', function() {
                echo "OK" . BR;
                echo "\\" . __CLASS__;
            });
        });

        $subRoute->run();
    }

    public function pageNum($num = array())
    {
        var_dump($num);
    }


}