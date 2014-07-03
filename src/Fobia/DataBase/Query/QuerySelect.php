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

    public function offset($offset)
    {
        $limit = 1000;
        if ( $this->limitString )
        {
            if(preg_match('/LIMIT ([^\s]+)/', $this->limitString, $ml)) {
                $limit = $ml[1];
            }
        }
        $this->limit($limit, $offset);
        return $this;
    }


    public function fetchItemsCount()
    {
        if (!$this->limitString) {
            $this->limit(1000, 0);
        }
        $s = $this->prepare();
        $s->execute();
        return array(
            'count' => $this->findAll(),
            'items' => $s->fetchAll(),
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
        return (int) $result['count'];
    }
}