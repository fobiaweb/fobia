<?php
/**
 * Author class  - Author.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Model;

use Fobia\Base\Model;

/**
 * Author class
 *
 *
 * @property int        $id           - Автор
 * @property string     $type         - К чему относится авторство(publication, thesis)
 * @property int        $data_id      -
 * @property int        $employee_id  - Сотрудник
 * @property string     $name         -
 * @property int        $ord          - Порядок сортировки
 *
 * @package   Model
 */
class Author extends Model
{
    const CLASS_NAME = __CLASS__;
    const TABLE_NAME = 'authors';

    static protected $_rules = array(
        'id'            => 'id',
        'type'          => 'string',
        'data_id'       => 'id',
        'employee_id'   => 'id',
        'name'          => 'string',
        'ord'           => 'int',
    );
    
}
