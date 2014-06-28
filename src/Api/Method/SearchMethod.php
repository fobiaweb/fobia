<?php
/**
 * SearchMethod class  - SearchMethod.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Api\Method;

/**
 * SearchMethod class
 *
 * @package   Api.Method
 */
class SearchMethod extends \Api\Method
{

    /**
     * @var \Fobia\DataBase\Query\QuerySelect
     */
    protected $query;

    protected function execute()
    {
        $params = array(
            'limit' => array('OPTION', 100)
        );


        $app = \App::instance();
        $db  = $app->db;
        $q   = $db->createSelectQuery();
        /* @var $q \Fobia\DataBase\Query\QuerySelect */

        $response = array(
            'count' => $q->findAll(),
            'items' => $q->fetchItemsCount(),
        );
    }

    protected function execQuery()
    {
        return array(
            'count' => $this->query->findAll(),
            'items' => $this->query->fetchItemsCount()
        );
    }
}