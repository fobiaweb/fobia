<?php
/**
 * ModelAction class  - ModelAction.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Base;

use Fobia\Base\Model;

/**
 * ModelAction class
 *
 * @package   Fobia\Base
 */
class ModelAction
{

    protected $model;

    function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Стичать из базы и построить объект по заданым полям (id)
     *
     * @param int|array $data
     * @return boolean
     */
    public function select($data = null)
    {
        $db = Application::getInstance()->db;

        $q = $db->createSelectQuery();
        $q->select('*')->from($this->getTableName());

        $column = 'id';
        $value  = $this->id;

        if ($data) {
            if (is_array($data)) {
                foreach ($data as $k => $v) {
                    $q->where($q->expr->eq($k, $db->quote($v)));
                }
            } else {
                $value = $data;
                $q->where($q->expr->eq($column, $db->quote($value)));
            }
        } else {
            $q->where($q->expr->eq($column, $db->quote($value)));
        }

        $stmt = $q->prepare();
        if ($stmt->execute()) {
            if ($result = $stmt->fetch()) {
                foreach ($result as $k => $v) {
                    $this->$k = $v;
                }
                return true;
            }
        }
        return false;
    }

    /**
     *
     * @param array $params
     * @return boolean
     */
    public function update($params = null)
    {
        $db     = $this->getDb();
        $params = (array) $params;

        $pkey = ($params['pkey']) ? $params['pkey'] : 'id';
        $id   = ($params['id']) ? $params['id'] : $this->$pkey;

        $q = $db->createUpdateQuery();
        $q->update($this->getTableName());

        $keys = $this->rules();
        unset($keys[$pkey]);

        foreach ($keys as $k => $v) {
            if (isset($this->$k)) {
                $q->set($k, $db->quote($this->$k));
            }
        }

        $q->where($q->expr->eq($pkey, $db->quote($id)));
        $stmt = $q->prepare();
        return $stmt->execute();
    }

    /**
     *
     * @param array $params
     * @return boolean
     */
    public function delete($params = null)
    {
        $db     = $this->getDb();
        $params = (array) $params;

        $pkey = ($params['pkey']) ? $params['pkey'] : 'id';
        $id   = ($params['id']) ? $params['id'] : $this->$pkey;

        $q    = $db->createDeleteQuery();
        $q->deleteFrom($this->getTableName());
        $q->where($q->expr->eq($pkey, $db->quote($id)));
        $stmt = $q->prepare();
        return $stmt->execute();
    }
}