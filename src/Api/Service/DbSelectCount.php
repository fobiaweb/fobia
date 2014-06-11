<?php
/**
 * DbSelectCount class  - DbSelectCount.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Congress\Service;

/**
 * DbSelectCount class
 *
 * @package   Congress.Service
 */
class DbSelectCount
{
    /**
     * @var \ezcQuerySelect
     */
    protected $queryWhere;
    /**
     * @var \ezcQuerySelect
     */
    protected $queryCount;
    protected $countColumn = '*';
    protected $needAll = false;

    /**
     *
     * @param \ezcQuerySelect $query
     */
    function __construct(\ezcQuerySelect $query)
    {
        $this->queryCount = clone $query;
        $this->queryWhere = $query;
    }

    /**
     * @return \ezcQuerySelect
     */
    public function select()
    {
        dispatchMethod($this->queryWhere, 'select', func_get_args());
        return $this;
    }

    public function limit( $limit, $offset = '' )
    {
        $limit = (int) $limit;
        if ($limit <= 0) {
            $limit = 100;
        }
        if ($limit > 1000) {
            $limit = 1000;
        }
        $this->queryWhere->limit($limit, $offset);
        return $this;
    }

    public function needAll()
    {
        $this->needAll = true;
    }

    public function count($column = '*')
    {
        $this->countColumn = $column;
    }

    public function execute()
    {
        if (!$this->needAll) {
            $this->limit(100);
        }
        $stmt = $this->queryWhere->prepare();
        dispatchMethod($stmt, 'execute', func_get_args());

        if ($this->needAll) {
            $row = array('count' => $stmt->rowCount());
        } else {
            $this->queryCount->select("COUNT({$this->countColumn}) AS count");
            $stmt_c = $this->queryCount->prepare();
            dispatchMethod($stmt_c, 'execute', func_get_args());
            $row = $stmt_c->fetch();
        }

        return array(
            'count' => $row['count'],
            'items' => $stmt->fetchAll()
        );
    }
}