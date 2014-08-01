<?php

use Fobia\Api\Method\Method;

/**
 * Возвращает список стран.
 * --------------------------------------------
 *
 * PARAMS:
 * ------
 * need_all        1 — вернуть список всех стран.
 * offset          отступ, необходимый для выбора определенного подмножества стран.
 *                 положительное число
 * limit           количество стран, которое необходимо вернуть.
 *                 положительное число, по умолчанию 100, максимальное значение 1000
 * --------------------------------------------
 *
 * @param  array $params
 * @return mixed
 * @api
 */
class Api_Stdata_GetCountries extends Method
{

    protected function configure()
    {
        $this->setName('stdata.getCountries');

        $this->setDefinition(array(
            'name'  => 'need_all',
            'default' => 0
        ));
        $this->setDefinition(array(
            'name' => 'limit',
            'default' => 10,
        ));
        $this->setDefinition(array(
            'name' => 'offset',
            'default' => 0,
        ));
    }

    protected function execute()
    {
        $p   = $this->getDefinitionParams();
        $app = \App::instance();
        $db  = $app->db;

        // $db->query('SET NAMES "UTF8"');
        $q = $db->createSelectQuery();

        $q->from('st_countries')
                ->select('id')
                ->select('name_rus AS title')
                ->where("1");

        if ( ! $p['need_all']) {
            if ( ! $p['limit']) {
                $p['limit'] = 100;
            }
            if ( ! $p['offset']) {
                $p['offset'] = 0;
            }

            $q->limit((int) $p['limit'], (int) $p['offset']);
        }

        $this->response = $q->fetchItemsCount();
    }
}