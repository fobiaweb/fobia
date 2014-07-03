<?php
/**
 * This file is part of API.
 *
 * utils.test.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

/**
 * Название метода <br>
 * --------------------------------------------
 *
 * PARAMS <br>
 * --------------------------------------------
 * <pre>
 *  offset      отступ, необходимый для получения определенного подмножества.
 *  count       количество записей, которые необходимо вернуть.
 * </pre>
 *
 * RESULT <br>
 * -------------------------------------------- <br>
 * Возвращаемый результат
 *
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 *
 * @api        utils.test
 */
class Api_Utils_Test extends \Api\Method\Method
{

    protected $name = 'utils.test';

    protected function configure()
    {
        $this->map = array(
            'name' => array(),
            'count' => array(),
            'offset' => array(),
            'fields' => array()
        );
    }


    protected function execute()
    {
        $p   = $this->params;
        $app = \App::instance();
        $db  = $app->db;

        $this->params();

        // yeur code

        $this->response = 1;
    }
}
