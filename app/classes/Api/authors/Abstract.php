<?php

/**
 * Api_Authors_Abstract class  - Api_Authors_Abstract.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
use Api\Method\Method;

/**
 * Api_Authors_Abstract class
 *
 * @package   
 */
abstract class Api_Authors_Abstract extends Method
{

    protected function updareOrder()
    {
        $db = \App::Db();
        $db->query("UPDATE  authors
                  SET ord = (SELECT @a:= @a + 1 FROM (SELECT @a:=0) s)
                  WHERE type = {$db->quote($p['type'])} AND data_id = {$db->quote($p['data_id'])}
                  ORDER BY ord");
    }
}