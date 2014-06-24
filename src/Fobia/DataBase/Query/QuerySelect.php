<?php
/**
 * QuerySelect class  - QuerySelect.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\DataBase\Query;

use ezcQuerySelect;

/**
 * QuerySelect class
 *
 * @package   
 */
class QuerySelect extends ezcQuerySelect
{

    protected function doJoin($type)
    {
        $this->lastInvokedMethod = 'from';
        return call_user_func_array(array('parent', 'doJoin'), func_get_args());
    }

    public function fetchItemsCount()
    {
        $s = $this->prepare();
        $s->execute();
        return array(
            'items' => $s->fetchAll(),
            'count' => $this->findAll()
        );
    }

    public function findAll()
    {
        $q = clone $this;

        $q->selectString = null;
        $q->limitString  = null;

        $q->select('COUNT(*) AS count');
        $stmt   = $q->prepare();
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'];
    }
}