<?php

use Api\Method\Method;

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
class Api_Stdata_GetCountries extends Method
{

    protected $method = 'stdata.getCountries';

    protected function execute()
    {
        $p   = $this->params;
        $app = \App::instance();
        $db  = $app->db;

        // $db->query('SET NAMES "UTF8"');
        $q = $db->createSelectQuery();
        $q->from('st_countries')->select('id')->select('name_rus AS title');
        if ( ! $p['need_all']) {
            if ( ! $p['count']) {
                $p['count'] = 100;
            }
            if ( ! $p['offset']) {
                $p['offset'] = 0;
            }

            $q->limit((int) $p['count'], (int) $p['offset']);
        }
        $stmt  = $q->prepare();
        $stmt->execute();
        $items = $stmt->fetchAll();

        $stmt = $db->query('SELECT COUNT(*) AS `count` FROM st_countries');
        $row  = $stmt->fetch();

        $this->response = array(
            'count' => (int) $row['count'],
            'items' => $items
        );
    }
}