<?php
/**
 * BadRequest class  - BadRequest.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Api;

/**
 * BadRequest class
 *
 * @package   Api
 */
class Exception_BadRequest extends \Exception
{

    /**
     * @internal
     */
    function __construct($message = "")
    {
        parent::__construct($message);
    }
}