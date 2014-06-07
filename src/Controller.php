<?php
/**
 * Controller class  - Controller.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

// namespace Fobia\Base;

/**
 * Controller class
 *
 * @package   Fobia.Base
 */
class Controller
{
    public $app;
    public $params;
    public $segments  = array();

    public function __construct($app)
    {
        $this->app = $app;

        echo "==================";
        $this->params = func_get_args();
        $_ENV['controller'] = $this;
    }

    public function segment($id)
    {
        return $this->segments[$id];
    }

    public function indexAction()
    {
        echo "Action:: " . __METHOD__. PHP_EOL;
        dump(func_get_args());
        dump($this);
//        dump($_ENV['controller']);
    }
}