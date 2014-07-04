<?php

use Api\Method\Method;

/**
 * stdata.getCities.php file
 *
 * Возвращает список городов.
 * --------------------------------------------
 *
 * PARAMS:
 * ------
 *
 *  country_id  (*) идентификатор страны, полученный в методе database.getCountries.
 *              положительное число, обязательный параметр
 *  region_id   идентификатор региона, города которого необходимо получить. (параметр не обязателен)
 *              положительное число
 *  q           строка поискового запроса. Например, Санкт.
 *  need_all    1 – возвращать все города. 0 – возвращать только основные города.
 *              флаг, может принимать значения 1 или 0
 *  offset      отступ, необходимый для получения определенного подмножества городов.
 *  count       количество городов, которые необходимо вернуть.
 *
 * --------------------------------------------
 *
 * RESULT
 * ------
 * Возвращает массив объектов city, каждый из которых содержит поля cid и title.
 * При наличии информации о регионе и/или области, в которых находится данный город,
 * в объекте могут дополнительно включаться поля area и region.
 * Если не задан параметр q, то будет возвращен список всех городов в заданной стране.
 * Если задан параметр q, то будет возвращен список городов, которые релевантны поисковому запросу.
 * --------------------------------------------
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 *
 * @api
 */
class Api_Stdata_GetCities extends Method
{

    protected $method = 'stdata.getCities';

    protected function execute()
    {
        $p   = $this->params();
        $app = \App::instance();
        $db  = $app->db;

        $q = $db->createSelectQuery();
        $e = $q->expr;

        $q->from('st_cities')
                ->select('id')
                ->select('city_name_ru AS title')
                ->orderBy('id')
                ->where($e->eq('id_country', $db->quote($p['country_id'])));

        if ((int) $p['region_id']) {
            $q->where($e->eq('id_region', $db->quote($p['region_id'])));
        }

        if ($p['q']) {
            $q->where($e->like('city_name_ru', $db->quote("%{$p['q']}%")));
        }

        if ( ! $p['need_all']) {
            if ( ! $p['count']) {
                $p['count'] = 100;
            }
            if ( ! $p['offset']) {
                $p['offset'] = 0;
            }

            $q->limit((int) $p['count'], (int) $p['offset']);
        }

        $this->response = $q->fetchItemsCount();
    }
}