<?php

/**
 * This file is part of API.
 * 
 * authors.add.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
use Api\Method\Method;

/**
 * Метода 'authors.add' <br>
 * --------------------------------------------
 *
 * PARAMS 
 * --------------------------------------------
 * <pre>
 *  type
 *  data_id
 *  employee_id      отступ, необходимый для получения определенного подмножества.
 *  name       количество записей, которые необходимо вернуть.
 * </pre>
 *
 * RESULT <br>
 * -------------------------------------------- <br>
 * Возвращает результат успеха
 *
 * 
 * @api        authors.add
 */
class Api_Authors_Add extends Method
{

    protected $method = 'authors.add';

    protected function configure()
    {
        $this->setDefinition('type',
                             array(
            'mode'  => Method::VALUE_REQUIRED,
            'parse' => 'trim',
        ));
        $this->setDefinition('data_id',
                             array(
            'mode'  => Method::VALUE_REQUIRED,
            'parse' => 'parsePositive',
        ));
        $this->setDefinition('employee_id',
                             array(
            'mode'  => Method::VALUE_OPTIONAL,
            'parse' => 'parsePositive',
        ));
        $this->setDefinition('name',
                             array(
            'mode'  => Method::VALUE_OPTIONAL,
            'parse' => 'trim',
        ));
    }

    protected function execute()
    {
        $p   = $this->getDefinitionParams();
        $app = \App::instance();
        $db  = $app->db;

        if (!$p['employee_id'] && !$p['name']) {
            throw new \Api\Exception\BadRequest('employee_id', 'name');
        }
        // dump($p);

        $q = $db->createInsertQuery();
        $q->insertInto('authors')
                ->set('type', $db->quote($p['type']))
                ->set('data_id', $db->quote($p['data_id']))
                ->set('employee_id', $db->quote($p['employee_id']))
                ->set('name', $db->quote($p['name']))
                ;
        $stmt = $q->prepare();
        $stmt->execute();
        if ($stmt->rowCount()) {
            $db->query("UPDATE  authors
                  SET ord = (SELECT @a:= @a + 1 FROM (SELECT @a:=0) s)
                  WHERE type = {$db->quote($p['type'])} AND data_id = {$db->quote($p['data_id'])}
                  ORDER BY ord");
                  
            $this->response = 1;
        } else {
            $this->response = 0;
        }

    }
}