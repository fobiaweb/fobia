<?php

/**
 * This file is part of API.
 * 
 * users.add.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
use Fobia\Api\Method\Method;

/**
 * Создает пользователя <br>
 * --------------------------------------------
 *
 * PARAMS <br>
 * --------------------------------------------
 * <pre>
 *  login      - (*)
 *  passwod    - (*)
 *  roles      -
 * </pre>
 *
 * RESULT <br>
 * -------------------------------------------- <br>
 * Возвращает ID только что созданого пользователя
 *
 * 
 * @api        users.add
 */
class Api_Users_Add extends Method
{

    protected $method = 'users.add';

    protected function configure()
    {
        $this->setDefinition('login',
                             array(
            'mode' => Method::VALUE_REQUIRED,
        ));
        $this->setDefinition('password',
                             array(
            'mode' => Method::VALUE_REQUIRED,
        ));
        $this->setDefinition('roles',
                             array(
            'mode'    => Method::VALUE_NONE,
            'default' => 0,
            'parse'   => array('parsePositive'),
        ));
    }

    protected function execute()
    {
        $p   = $this->getDefinitionParams();
        $app = \App::instance();
        $db  = $app->db;

        $password = hash_hmac($app['settings']['crypt.method'], $p['password'],
                              $app['settings']['crypt.key']);

        $q = $db->createInsertQuery();

        $q->insertIntoIgnore('users')
                ->set('login', $db->quote($p['login']))
                ->set('password', $db->quote($password))
                ->set('role_mask', $db->quote($p['roles']));

        $stmt = $q->prepare();
        $stmt->execute();
        if ($stmt->rowCount()) {
            $this->response = (int) $db->lastInsertId();
        } else {
            $this->response = 0;
        }
    }
}