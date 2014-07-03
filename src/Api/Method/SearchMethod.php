<?php
/**
 * SearchMethod class  - SearchMethod.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Api\Method;

use Api\Method\Method;

/**
 * SearchMethod class
 *
 * @package   Api.Method
 */
class SearchMethod extends Method
{
    /**
     * @var \Fobia\DataBase\Query\QuerySelect
     */
    protected $query;

    protected function execute()
    {
        $params = array(
            'limit' => array(Method::VALUE_OPTIONAL, 100),
            'offset' => array(Method::VALUE_OPTIONAL, 0),
            'sort' => array(Method::VALUE_OPTIONAL),
            'fields' => array(Method::VALUE_OPTIONAL)
        );


        $app = \App::instance();
        $db  = $app->db;
        $q   = $db->createSelectQuery();
        /* @var $q \Fobia\DataBase\Query\QuerySelect */

        $this->query = $q;
    }

    protected function execQuery()
    {
        return array(
            'count' => $this->query->findAll(),
            'items' => $this->query->fetchItemsCount()
        );
    }
}