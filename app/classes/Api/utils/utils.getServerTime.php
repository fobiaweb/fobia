<?php
/**
 * This file is part of API.
 *
 * utils.getServerTime.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

use Fobia\Api\Method\Method;

/**
 * Возвращаемый время на сервере 
 * --------------------------------------------
 *
 * RESULT:
 * -------
 * Возвращаемый время на сервере
 * --------------------------------------------
 *
 * @api        utils.getServerTime
 */
class Api_Utils_GetServerTime extends Method
{

    protected function configure()
    {
        $this->setName('utils.getServerTime');
    }

    protected function execute()
    {
        $this->response = time();
    }
}

