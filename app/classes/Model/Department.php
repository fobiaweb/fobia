<?php
/**
 * Department class  - Department.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Model;

use Fobia\Base\Model;

/**
 * Department class
 *
 * @property int    $dept_id
 * @property string $dept
 * @property int    $parent
 *
 * @package   Model
 */
class Department extends Model
{

    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            $l = \App::Db()->query('SELECT * FROM departments')->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
            foreach ($l as $obj) {
                self::$instance[$obj->dept_id] = $obj;
            }
        }
        return self::$instance;
    }

    /**
     *
     * @param int $dept_id
     * @return self
     */
    public static function getItem($dept_id)
    {
        self::getInstance();
        return self::$instance[$dept_id];
    }

    public function getChildren()
    {
        $children = array();
        foreach (self::getInstance() as $dept) {
            if ($dept->parent == $this->dept_id) {
                $children[] = $dept;
            }
        }
        return $children;
    }

    public function getChildrenRecursive()
    {
        $children = array();
        foreach (self::getInstance() as $dept) {
            if ($dept->parent == $this->dept_id) {
                $children[] = $dept;
                $children = array_merge($children, $dept->getChildrenRecursive());
            }
        }
        return $children;
    }

    public function getParents()
    {
        if ($this->parent == 0) {
            return array();
        }

        $dept = self::getItem($this->parent);
        return array_merge(array($dept), $dept->getParents());
    }
}