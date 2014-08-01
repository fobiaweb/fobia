<?php
/**
 * User class
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Congress\Model;

use \Fobia\Model;

/**
 * User class - table users
 *
 *
 * @property int        $id           -
 * @property string     $login        - Логин пользователя или емаил
 * @property string     $password     - Пароль захешированый методам приложения
 * @property int        $role_mask    - принимаемые роли (битовая маска)
 * @property DateTime   $online       - время online
 * @property string     $sid          - Текущая сесия
 *
 * @package  Congress.Model
 */
class User extends Fobia\Base\Model
{

    const CLASS_NAME = __CLASS__;
    const TABLE_NAME = 'users';

    // array(type, null, default)
    static protected $_rules = array(
        'id'            => 'id',
        'login'         => 'string',
        'password'      => 'string',
        'role_mask'     => 'int',
        'online'        => 'datetime',
        'sid'           => 'id',
    );

}
