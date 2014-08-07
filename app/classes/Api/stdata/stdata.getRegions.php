<?php

use Fobia\Api\Method as Method;

/**
 * Возвращает список регионов.
 * --------------------------------------------
 *
 * PARAMS:
 * ------
 * country_id   (*) идентификатор страны, полученный в методе database.getCountries.
 *              положительное число, обязательный параметр
 * q            строка поискового запроса. Например, Лен.
 * limit        отступ, необходимый для выбора определенного подмножества регионов.
 * count        количество регионов, которое необходимо вернуть.
 *
 * --------------------------------------------
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 * @api
 */
class Api_Stdata_GetRegions extends SearchMethod
{

    protected function configure()
    {
        $this->setName('stdata.getRegions');
        $this->setDefinition(array(
            'name'  => 'country_id',
            'mode'  => Method::VALUE_REQUIRED,
            'parse' => 'parsePositive',
        ));
        $this->setDefinition(array(
            'name' => 'fields',
            'default' => array('country_id'),
        ));
        $this->setDefinition(array(
            'name'  => 'q',
            'parse' => 'trim'
        ));
    }

    protected function execute()
    {
        $p   = $this->getDefinitionParams();
        $app = \App::instance();
        $db  = $app->db;

        $this->execQuery();
        $query = $this->query;
        $query->from('st_regions');
        $query->where($query->expr->eq('country_id', $db->quote($p["country_id"])));
        if ($p["q"]) {
            $query->where($query->expr->like('name_rus', $db->quote("%{$p["q"]}%")));
        }
        $this->response = $query->fetchItemsCount();
        return true;

        $qs = clone $query;
        $qs->select('id');
        $qs->select('name_rus AS title');
        $qs->limit((int) $p["limit"], (int) $p["offset"]);

        $stmt  = $qs->prepare();
        $stmt->execute();
        $items = $stmt->fetchAll();

        $query->select('COUNT(*) AS `count`');
        $stmt = $query->prepare();
        $stmt->execute();
        $row  = $stmt->fetch();

        $this->response = array(
            'count' => (int) $row['count'],
            'items' => $items
        );


    }
}