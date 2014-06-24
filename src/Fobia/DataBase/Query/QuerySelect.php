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
    /**
     * Resets the query object for reuse.
     *
     * @return void
     */
    protected function resetLimit()
    {
        $this->selectString = null;
        $this->limitString = null;
    }

    protected function doJoin($type)
    {
        $args = func_get_args();
        $this->lastInvokedMethod = 'from';
        return call_user_func_array(array('parent', 'doJoin'), $args);
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
        $q->resetLimit();
        $q->select('COUNT(*) AS count');
        $stmt = $q->prepare();
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'];
    }
}