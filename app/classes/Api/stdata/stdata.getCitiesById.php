<?php

use Api\Method\Method;

/**
 * stdata.getCitiesById.php file
 *
 * Возвращает информацию о городах по их идентификаторам.
 * --------------------------------------------
 *
 * PARAMS:
 * ------
 * city_ids     идентификаторы городов.
 *              список положительных чисел, разделенных запятыми,
 *              количество элементов должно составлять не более 1000
 *
 * --------------------------------------------
 *
 * RESULT
 * ------
 * Возвращает массив объектов city, каждый из которых имеет поля id и title.
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 * @api
 */
class Api_Stdata_GetCitiesById extends Method
{

    protected $method = 'stdata.getCitiesById';

    protected function execute()
    {
        $app = \App::instance();
        $db = $app->db;

        $p = $this->params();

        $ids = parseNumbers($p['city_ids']);
        if ( !count($ids) ) {
            throw new \Api\Exception\BadRequest("city_ids");
        }

        $q = $db->createSelectQuery();
        $q->from('st_cities')
                ->select('id')
                ->select('city_name_ru AS title')
                ->where($q->expr->in('id', $ids));

        $stmt = $q->prepare();
        $stmt->execute();

        $this->response = $stmt->fetchAll();
    }
}
