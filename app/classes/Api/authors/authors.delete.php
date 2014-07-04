<?php
/**
 * This file is part of API.
 *
 * authors.delete.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

 use Api\Method\Method;

/**
 * Метода 'authors.delete' <br>
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
 * @api        authors.delete
 */
class Api_Authors_Delete extends Method
{

    protected $method = 'authors.delete';
    protected function configure()
    {
        $this->setDefinition(array(
            'name'  => 'author_id',
            'mode'  => Method::VALUE_REQUIRED,
            'parse' => 'parsePositive',
        ));
    }
    protected function execute()
    {
        $p   = $this->getDefinitionParams();
        $app = \App::instance();
        $db  = $app->db;

        // yeur code
        $stmt = $db->query("DELETE FROM authors WHERE id = '{$p['author_id']}'");
        $this->response = $stmt->rowCount();
    }
}
