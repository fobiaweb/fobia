<?php

use Fobia\Api\Method as Method;

/**
 * Возвращает информацию о странах по их идентификаторам
 * --------------------------------------------
 *
 * PARAMS:
 * ------
 * country_ids   (*) идентификаторы стран. список положительных чисел,
 *               разделенных запятыми, количество элементов должно составлять
 *               не более 1000
 * --------------------------------------------
 *
 * RESULT
 * ------
 * Возвращает массив объектов country, каждый из которых имеет поля id и title.
 * --------------------------------------------
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 * @api
 */
class Api_Stdata_GetCountriesById extends Method
{

    protected function configure()
    {
        $this->setName('stdata.getCountriesById');
        $this->setDefinition(array(
            'name'  => 'country_ids',
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

        $q->from('st_countries')
                ->select('id')
                ->select('name_rus AS title')
                ->where($q->expr->in('id', $p["country_ids"]));
        $stmt           = $q->prepare();
        $stmt->execute();
        $this->response = $stmt->fetchAll();
    }
}