<?php
/**
 * This file is part of API.
 * 
 * {{name}}.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

 use Api\Method\Method;

/**
 * Метода '{{name}}' <br>
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
 * Возвращает результат успеха
 *
 * 
 * @api        {{name}}
 */
class {{classname}} extends Method
{

    protected $method = '{{name}}';

    protected function execute()
    {
        $p   = $this->getDefinitionParams();
        $app = \App::instance();
        $db  = $app->db;

        // yeur code

        $this->response = 1;
    }
}
