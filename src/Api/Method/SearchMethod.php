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

    protected function configure()
    {
        $this->setDefinition('limit', array(
            'mode' => Method::VALUE_OPTIONAL,
            'default' => 100,
        ));
        $this->setDefinition('offset',  array(
            'mode' => Method::VALUE_OPTIONAL,
            'default' => 0,
        ));
        $this->setDefinition('sort',  array(
            'mode' => Method::VALUE_OPTIONAL,
        ));
        $this->setDefinition('fields',  array(
            'mode' => Method::VALUE_OPTIONAL,
        ));
    }

    protected function execute()
    {
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