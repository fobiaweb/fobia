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
    public $segments  = array();

    public function __construct($app, $segments = array())
    {
        $this->app = $app;
        $this->segments = $segments;
    }

    public function segment($id)
    {
        return $this->segments[$id];
    }

    public function indexAction()
    {
        dump(array(
            'Action' => __METHOD__,
            'Segments' => $this->segments,
            'Args' => func_get_args()
        ));
    }
}