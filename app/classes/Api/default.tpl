<?php
/**
 * This file is part of API.
 *
 * {{name}}.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

 use Fobia\Api\Method as Method;

/**
 * Метода '{{name}}'
 * --------------------------------------------
 *
 * PARAMS:
 * -------
 *
 *  offset      отступ, необходимый для получения определенного подмножества.
 *  count       количество записей, которые необходимо вернуть.
 *
 * --------------------------------------------
 *
 * RESULT:
 * -------
 * Возвращает результат успеха
 * --------------------------------------------
 *
 * @api        {{name}}
 */
class {{classname}} extends Method
{

    protected function configure()
    {
        $this->setName('{{name}}');

        $this->setDefinition(array(
            'name' => 'id',
            //'mode' => Method::VALUE_REQUIRED,
            //'parse' => null,
            //'assert' => null
        ));
    }

    protected function execute()
    {
        $p   = $this->getDefinitionParams();
        $app = \App::instance();
        $db  = $app->db;

        // yeur code

        $this->response = 1;
    }
}
