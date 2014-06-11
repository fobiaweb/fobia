<?php
/**
 * Stdata class  - Stdata.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Congress\Service\Api;

use Congress\Service\ApiMethod;

/**
 * Stdata class
 *
 * @package   Congress.Service.Api
 */
class Stdata extends ApiMethod
{

    /**
     * Возвращает список стран.
     * --------------------------------------------
     *
     * PARAMS:
     * ------
     * need_all        1 — вернуть список всех стран.
     * offset          отступ, необходимый для выбора определенного подмножества стран.
     *                 положительное число
     * count           количество стран, которое необходимо вернуть.
     *                 положительное число, по умолчанию 100, максимальное значение 1000
     * --------------------------------------------
     *
     * @param  array $params
     * @return mixed
     * @api
     */
    public function getCountries()
    {
        $params = $this->prepare(func_get_args());
        extract($params);

        $q = $this->getDb()->createSelectQuery();
        $q->from('st_countries')
                ->select('id')
                ->select('name_rus AS title');
        if ( ! $need_all) {
            if ( ! $count) {
                $count = 100;
            }
            if ( ! $offset) {
                $offset = 0;
            }

            $q->limit((int) $count, (int) $offset);
        }
        $stmt  = $q->prepare();
        $stmt->execute();
        $items = $stmt->fetchAll();

        $stmt = $this->getDb()->query('SELECT COUNT(*) AS `count` FROM st_countries');
        $row  = $stmt->fetch();

        return array(
            'count' => (int) $row['count'],
            'items' => $items
        );
    }

    /**
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
     * @param  array $params
     * @return mixed
     * @api
     */
    public function getRegions()
    {
        $params = $this->prepare(func_get_args());
        extract($params);
        $db     = $this->getDb();


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

        return array(
            'count' => (int) $row['count'],
            'items' => $items
        );
    }

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
     * @param  array $params
     * @return mixed
     * @api
     */
    public function getCountriesById()
    {
        $params = $this->prepare(func_get_args());

        $ids = parseNumbers($params['country_ids']);
        $q = $this->getDb()->createSelectQuery();
        $q->from('st_countries')
                ->select('id')
                ->select('name_rus AS title')
                ->where($q->expr->in('id', $ids));
        $stmt = $q->prepare();
        $stmt->execute();
        return $stmt->fetchAll();
    }

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
     *
     * @param  array $params
     * @return mixed
     * @api
     */
    public function getCities()
    {
        $params = $this->prepare(func_get_args());
        extract($params);
        $db     = $this->getDb();


        $query = $this->getDb()->createSelectQuery();
        $query->from('st_cities');
        $query->where($query->expr->eq('country_id', $db->quote($country_id)));
        if ((int) $region_id) {
            $query->where($query->expr->eq('region_id', $db->quote($region_id)));
        }
        if ($q) {
            $query->where($query->expr->like('city_name_ru', $db->quote("%{$q}%")));
        }

        $qs = clone $query;

        $qs->select('id')
           ->select('city_name_ru AS title');

        if ( ! $need_all) {
            if ( ! $count) {
                $count = 100;
            }
            if ( ! $offset) {
                $offset = 0;
            }

            $qs->limit((int) $count, (int) $offset);
        }




        $stmt  = $qs->prepare();
        if(!$stmt->execute()) {

            dump($stmt->errorInfo());
        }
        $items = $stmt->fetchAll();

        $query->select('COUNT(*) AS `count`');
        $stmt = $query->prepare();
        $stmt->execute();
        $row  = $stmt->fetch();

        return array(
            'count' => (int) $row['count'],
            'items' => $items
        );
    }

    /**
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
     * @param  array $params
     * @return mixed
     * @api
     */
    public function getCitiesById()
    {
        $params = $this->prepare(func_get_args());


        $ids = parseNumbers($params['city_ids']);

        $q = $this->getDb()->createSelectQuery();
        $q->from('st_cities')
                ->select('id')
                ->select('city_name_ru AS title')
                ->where($q->expr->in('id', $ids));

        $stmt = $q->prepare();
        $stmt->execute();
        return $stmt->fetchAll();
    }
}