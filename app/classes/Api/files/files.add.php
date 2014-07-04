<?php

/**
 * This file is part of API.
 * 
 * files.add.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
use Api\Method\Method;

/**
 * Метода 'files.add' <br>
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
 * @api        files.add
 */
class Api_Files_Add extends Method
{

    protected $method = 'files.add';

    protected function configure()
    {
        $this->setDefinition('file',
                             array(
            'mode' => Method::VALUE_REQUIRED,
        ));

        $this->setDefinition('type',
                             array(
            'mode'  => Method::VALUE_NONE,
            'parse' => 'trim'
        ));
        $this->setDefinition('data_id',
                             array(
            'mode'  => Method::VALUE_NONE,
            'parse' => 'parsePositive'
        ));
        $this->setDefinition('title',
                             array(
            'mode'  => Method::VALUE_NONE,
            'parse' => 'trim'
        ));
    }

    protected function execute()
    {
        $p   = $this->getDefinitionParams();
        $app = \App::instance();
        $db  = $app->db;

        $dest = HTML_DIR;// '/tmp';

        if(!copy($p['file']['tmp_name'],  $dest . '/' . basename($p['file']['tmp_name']))){
            $this->response = 0;
            return;
        }

//array (size=1)
//  'file' =>
//    array (size=5)
//      'name' => string 'webcam-toy-photo11.jpg' (length=22)
//      'type' => string 'image/jpeg' (length=10)
//      'tmp_name' => string '/tmp/php4cDxxW' (length=14)
//      'error' => int 0
//      'size' => int 43014

        

        $q = $db->createInsertQuery();
        $q->insertInto('files')->set('id', 'NULL');

        if ($p['type']) {
            $q->set('type', $db->quote($p['type']));
        }
        if ($p['data_id']) {
            $q->set('data_id', $db->quote($p['data_id']));
        }
        if ($p['title']) {
            $q->set('title', $db->quote($p['title']));
        }

        $stmt = $q->prepare();
        $stmt->execute();
        if ($stmt->rowCount()) {
            $this->response = $db->lastInsertId();
        } else {
            $this->response = 0;
        }
        /* */
    }
}