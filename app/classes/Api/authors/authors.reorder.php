<?php
/**
 * This file is part of API.
 * 
 * authors.reorder.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

 use Api\Method\Method;

/**
 * Метода 'authors.reorder' <br>
 * --------------------------------------------
 *
 * PARAMS <br>
 * --------------------------------------------
 * <pre>
 *  author_id      отступ, необходимый для получения определенного подмножества.
 *  before
 *  after
 * </pre>
 *
 * RESULT <br>
 * -------------------------------------------- <br>
 * Возвращает результат успеха
 *
 * 
 * @api        authors.reorder
 */
class Api_Authors_Reorder extends Method
{

    protected $method = 'authors.reorder';

    protected function configure()
    {
        $this->setDefinition(array(
            'name' => 'author_id',
            'mode' => Method::VALUE_REQUIRED,
            'parse' => 'parsePositive',
        ));
        $this->setDefinition(array(
            'name' => 'after',
            'mode' => Method::VALUE_OPTIONAL,
            'parse' => 'parsePositive',
        ));
    }

    protected function execute()
    {
        $p   = $this->getDefinitionParams();
        $app = \App::instance();
        $db  = $app->db;

        $res = $db->query("SELECT `type`, `data_id`, `ord` FROM authors WHERE id = {$db->quote($p['author_id'])}");
        $row = $res->fetch();

        if ($p['after']) {
            $db->query("UPDATE  authors SET ord = ord + 100 WHERE type = {$row['type']} AND data_id = {$row['data_id']}");
            $db->query("UPDATE  authors SET ord = '{$p['before']}' WHERE type = {$row['type']} AND data_id = {$row['data_id']}");
        }
        $db->query("UPDATE  authors
                  SET ord = (SELECT @a:= @a + 1 FROM (SELECT @a:=0) s)
                  WHERE type = {$row['type']} AND data_id = {$row['data_id']}
                  ORDER BY ord");

        // Пронумеровать поле таблицы "по-порядку"
                  /*
        $query = "UPDATE  authors
                  SET ord = (SELECT @a:= @a + 1 FROM (SELECT @a:=0) s)
                  WHERE type = {$row['type']} AND data_id = {$row['data_id']}
                  ORDER BY ord";
                  */
        $this->response = 1;
    }
}
