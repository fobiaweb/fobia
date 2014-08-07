<?php
/**
 * This file is part of API.
 *
 * files.delete.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

 use Fobia\Api\Method as Method;

/**
 * Метода 'files.delete'
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
 * @api        files.delete
 */
class Api_Files_Delete extends Method
{

    protected function configure()
    {
        $this->setName('files.delete');

        $this->setDefinition(array(
            'name' => 'id',
            'mode' => Method::VALUE_REQUIRED,
            'parse' => 'parseInt',
            //'assert' => null
        ));
    }

    protected function execute()
    {
        $p   = $this->getDefinitionParams();
        $app = \App::instance();
        $db  = $app->db;

        if ($stmt = $db->query("DELETE FROM files WHERE id = '{$p['id']}'")) {
            $this->response = $stmt->rowCount();
        }
    }
}
