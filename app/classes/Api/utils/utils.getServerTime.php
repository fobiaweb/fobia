<?php
/**
 * This file is part of API.
 *
 * utils.getServerTime.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

use Api\Method\Method;

/**
 * Возвращаемый время на сервере <br>
 * --------------------------------------------
 *
 * RESULT <br>
 * -------------------------------------------- <br>
 * Возвращаемый время на сервере
 *
 *
 * @api        utils.getServerTime
 */
class Api_Utils_GetServerTime extends Method
{

    protected $method = 'utils.getServerTime';

    protected function execute()
    {
        $this->response = time();
    }
}

