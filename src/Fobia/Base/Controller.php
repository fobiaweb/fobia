<?php
/**
 * Controller class  - Controller.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Base;

/**
 * Controller class
 *
 * @package   Fobia.Base
 */
class Controller
{
    /**
     * @var \Fobia\Base\Application
     */
    public $app;
    public $params   = array();

    public function __construct(Application $app, $params = array())
    {
        $this->app      = $app;
        $this->params   = $params;
    }

    public function section($section = null)
    {
        $method = $this->app->config('controller.action_prefix')
                . $section
                . $this->app->config('controller.action_suffix');
        if ($method === null || !method_exists($this, $method) ) {
            $this->app->notFound();
        }

        $args = array_slice(func_get_args(), 1);
        dispatchMethod($this, $method, $args);
    }

    public function index()
    {
        $this->app->notFound();
    }

    public function errorAction()
    {
        $this->app->error();
    }
}