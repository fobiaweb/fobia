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
        if ($section === null || !method_exists($this, $section) ) {
            $this->app->notFound();
        }
        
        $args = func_get_args();
        array_shift($args);
        call_user_func_array(array($this, $section), $args);
    }

    public function index()
    {
        $this->app->notFound();
    }

    public function indexAction()
    {
        dump(array(
            'Action'   => __METHOD__,
            'Segments' => $this->segments,
            'Args'     => func_get_args()
        ));
        dump($this->app['router']->getCurrentRoute()->getParams());

        dump($this->app['request']->getPathInfo());

        dump($this->app['view']);
    }


    public function errorAction()
    {
        $this->app->error();
    }
}