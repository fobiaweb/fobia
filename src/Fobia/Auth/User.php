<?php
/**
 * User class  - User.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Auth;

/**
 * User class
 *
 * @package   Fobia.Auth
 */
class User implements IUserIdentity
{
    public $access = array();

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function isRole($role)
    {

    }

    public function isAccess($access, $value = 1)
    {
        return ($this->access[$access] == $value) ? true : false;
    }

    public function getAccess($access)
    {
        return $this->access[$access];
    }

    public function readData()
    {
        $app = \App::instance();
        $db= $app->db;

        $q = $db->createSelectQuery();
        $q->select("*")->from("users")
                ->where($q->expr->eq($value1, $db->quote( $this->getPassword())))
                ->where($q->expr->eq($value1, $db->quote( $this->getName())))
                ->limit(1);

        $stmt = $q->prepare();

        if ($stmt->execute()) {
            if ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                foreach ($result as $key => $value) {
                    $this->$key = $value;
                }
            }
        }
    }

}