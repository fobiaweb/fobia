<?php

/**
 * This file is part of API.
 *
 * utils.test.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
use \Api\Method\Method;

/**
 * Название метода 
 * --------------------------------------------
 *
 * PARAMS 
 * --------------------------------------------
 * 
 *  offset      отступ, необходимый для получения определенного подмножества.
 *  count       количество записей, которые необходимо вернуть.
 * 
 *
 * RESULT 
 * -------------------------------------------- 
 * Возвращаемый результат
 *
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 *
 * @api        utils.test
 */
class Api_Utils_Test extends Method
{

    protected $name = 'utils.test';

    protected function configure()
    {
        $this->setDefinition('count',
                             array(
            'default' => 100,
            'parse'   => 'parsePositive',
        ));
        $this->setDefinition('offset',
                             array(
            'default' => 0,
            'parse'   => 'parsePositive',
        ));
        $this->setDefinition('name',
                             array(
            'mode'   => Method::VALUE_OPTIONAL,
//            'assert' => array('is_numeric')
        ));
    }

    protected function execute()
    {
        $args = $this->getDefinitionParams();


//        var_dump($this->getDefinition());
        var_dump($this->getParams());
        var_dump($args);
        echo "<hr>";
    }
}