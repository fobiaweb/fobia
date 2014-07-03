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
    }


    public function errorAction()
    {
        $this->app->error();
    }
}