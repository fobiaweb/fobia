<?php
/**
 * CDbDatabaseSchema class  - CDbDatabaseSchema.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Database\Schema;

/**
 * CDbDatabaseSchema class
 *
 * @package Fobia.DataBase.Schema
 */
class CDbDatabaseSchema
{

    /**
     * @var string Название бфзы.
     */
    public $name;

    /**
     * @var string Сырье название. Это указано название, которое может быть использовано в запросах SQL.
     */
    public $rawName;

    /**
     * @var array
     */
    public $tables = array();

    public function getTable($name)
    {
        return isset($this->tables[$name]) ? $this->tables[$name] : null;
    }

    /**
     * @return array list of column names
     */
    public function getTableNames()
    {
        return array_keys($this->tables);
    }
}