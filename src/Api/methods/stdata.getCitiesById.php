<?php
/**
 * stdata.getCitiesById.php file
 *
 * Возвращает информацию о городах по их идентификаторам.
 * --------------------------------------------
 *
 * PARAMS:
 * ------
 * city_ids     идентификаторы городов.
 * список положительных чисел, разделенных запятыми, количество элементов должно составлять не более 1000
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
/* @var $this \Api\ApiMethod */



$ids = parseNumbers($params['city_ids']);

$q = $this->app->db->createSelectQuery();
$q->from('st_cities')
        ->select('id')
        ->select('city_name_ru AS title')
        ->where($q->expr->in('id', $ids));

$stmt = $q->prepare();
$stmt->execute();


$thiss->response = $stmt->fetchAll();
