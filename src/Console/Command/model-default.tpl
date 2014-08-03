<?php
/**
 * This file is part of Model.
 * 
 * {{fileName}}.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Model;

use \Fobia\Model;

/**
 * {{className}} class - table $table
 *
 *
 * {{property}}
 *
 * @package  Model
 */
class {{className}} extends Model
{

    const CLASS_NAME = __CLASS__;
    const TABLE_NAME = '{{tableName}}';

    static protected $_primaryKey = null;
    static protected $_rules = array(
        {{rules}}
    );

}
