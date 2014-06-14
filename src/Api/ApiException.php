<?php
/**
 * ApiException class  - ApiException.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Api;

/**
 * ApiException class
 *
 * @package   Api
 */
class ApiException extends \Exception
{
    public $params;
    public $method;

    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code, null);
    }

}