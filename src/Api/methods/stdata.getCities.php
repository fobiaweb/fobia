<?php

/**
 * stdata.getCities.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

/**
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
 */

$db = $this->app->db;

$query = $db->createSelectQuery();
$query->from('st_cities');
$query->where($query->expr->eq('country_id', $db->quote($params['country_id'])));
if ((int)$params['region_id']) {
    $query->where($query->expr->eq('region_id', $db->quote($params['region_id'])));
}


if ($params['q']) {
    $query->where($query->expr->like('city_name_ru', $db->quote("%{$params['q']}%")));
}

$qs = clone $query;

$qs->select('id')->select('city_name_ru AS title');

if (!$params['need_all']) {
    if (!$count) {
        $count = 100;
    }
    if (!$offset) {
        $offset = 0;
    }

    $qs->limit((int)$count, (int)$offset);
}

$stmt = $qs->prepare();
if (!$stmt->execute()) {

    dump($stmt->errorInfo());
}
$items = $stmt->fetchAll();

$query->select('COUNT(*) AS `count`');
$stmt = $query->prepare();
$stmt->execute();
$row = $stmt->fetch();

return array('count' => (int)$row['count'], 'items' => $items);
