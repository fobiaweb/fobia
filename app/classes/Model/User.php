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
 *
 * @package  Congress.Model
 */
class User extends \Fobia\Base\Model implements \Fobia\Auth\IUserIdentity
{

    const CLASS_NAME = __CLASS__;
    const TABLE_NAME = 'users';

    // array(type, null, default)
    static protected $_rules = array(
        'id'            => 'id',
    );

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->login;
    }

    public function getPassword()
    {
        return $this->password;
    }

}
