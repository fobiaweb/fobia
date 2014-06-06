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
    public $params;

    public function __construct($app)
    {
        echo "==================";
        $this->params = func_get_args();
        $_ENV['controller'] = $this;
    }

    public function index()
    {
        dump(func_get_args());
        dump($_ENV['controller']);
    }
}