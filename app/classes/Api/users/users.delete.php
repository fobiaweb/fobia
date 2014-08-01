<?php

/**
 * This file is part of API.
 * 
 * users.delete.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
use Fobia\Api\Method\Method;

/**
 * Удаляет пользователя
 * --------------------------------------------
 *
 * PARAMS:
 * -------
 * 
 *  id      (*)
 *  login
 * 
 * --------------------------------------------
 *
 * RESULT:
 * -------
 * Возвращает результат успеха
 * --------------------------------------------
 * 
 * @api        users.delete
 */
class Api_Users_Delete extends Method
{

    protected $method = 'users.delete';

    protected function configure()
    {
        $this->setDefinition('id',
                             array(
            'mode'  => Method::VALUE_REQUIRED,
            'parse' => array('parsePositive'),
        ));
        $this->setDefinition('login',
                             array(
            'mode' => Method::VALUE_NONE,
        ));
    }

    protected function execute()
    {
        $p   = $this->getDefinitionParams();
        $app = \App::instance();
        $db  = $app->db;

        $q = $db->createDeleteQuery();

        $q->deleteFrom('users')
                ->where($q->expr->eq('id', $db->quote($p['id'])));

        $stmt           = $q->prepare();
        $stmt->execute();
        $this->response = $stmt->rowCount();
    }
}