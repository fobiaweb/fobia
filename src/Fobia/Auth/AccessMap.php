<?php
/**
 * AccessMap class  - AccessMap.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Auth;

/**
 * AccessMap class
 *
 * @package   Fobia.Auth
 */
class AccessMap
{
    private $role = 0;
    private $access = array();

    public function add($access)
    {
        if (!is_array($access)) {
            $access = array($access => 1);
        }
        $this->access = array_merge($access, $access);
    }

    public function isAccess($name, $value = 1)
    {
        return ($this->access[$name] == $value) ? true : false;
    }

    public function getAccess($name)
    {
        return $this->access[$name];
    }

    public function isRole($role)
    {
        return ($this->role & (int) $role) ? true : false;
    }
}
