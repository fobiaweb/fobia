<?php

use Fobia\Api\Method as Method;

/**
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

    protected function configure()
    {
        $this->setName('stdata.getCitiesById');

        $this->setDefinition(array(
            'name'  => 'city_ids',
            'mode'  => Method::VALUE_REQUIRED,
            'parse' => 'parseNumbers',
            'assert' => 'count'
        ));
    }

    protected function execute()
    {
        $p   = $this->getDefinitionParams();
        $app = \App::instance();
        $db  = $app->db;

        $q = $db->createSelectQuery();
        $q->from('st_cities')
                ->select('id')
                ->select('city_name_ru AS title')
                ->where($q->expr->in('id', $p['city_ids']));

        $stmt = $q->prepare();
        $stmt->execute();

        $this->response = $stmt->fetchAll();
    }
}
