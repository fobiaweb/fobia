<?php

use Api\Method\Method;


/**
 * stdata.getRegions.php file
 *
 * Возвращает список регионов.
 * --------------------------------------------
 *
 * PARAMS:
 * ------
 * country_id   (*) идентификатор страны, полученный в методе database.getCountries.
 *              положительное число, обязательный параметр
 * q            строка поискового запроса. Например, Лен.
 * offset       отступ, необходимый для выбора определенного подмножества регионов.
 * count        количество регионов, которое необходимо вернуть.
 *
 * --------------------------------------------
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 * @api
 */
class Api_Stdata_GetRegions extends Method
{

    protected $method = 'stdata.getCountriesById';

    protected function execute()
    {
        $p   = $this->params();
        $app = \App::instance();
        $db  = $app->db;

        extract($p);


        if ( ! $count) {
            $count = 100;
        }
        if ( ! $offset) {
            $offset = 0;
        }


        $query = $db->createSelectQuery();

        $query->from('st_regions');
        $query->where($query->expr->eq('country_id', $db->quote($country_id)))
                ->where($query->expr->like('name_rus', $db->quote("%{$q}%")));

        $qs = clone $query;
        $qs->select('id');
        $qs->select('name_rus AS title');
        $qs->limit((int) $count, (int) $offset);

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
