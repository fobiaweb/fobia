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
class User extends \Fobia\Base\Model implements IUserIdentity
{

    public $access = array();

    /**
     * @var array карта-схема таблицы
     */
    protected $map = array(
        'id'       => 'id',
        'login'    => 'login',
        'password' => 'password',
        'role'     => 'role_mask',
        'online'   => 'online',
        'sid'      => 'sid',
    );

    public function __construct(array $map = array())
    {
        $this->map = array_merge($this->map, $map);
    }

    /**
     * Get user ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->{$this->map['id']};
    }

    /**
     * Get user mask roles
     *
     * @return int
     */
    public function getRoles()
    {
        return $this->{$this->map['role']};
    }

    /**
     * Get user login
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->{$this->map['login']};
    }

    /**
     * Get user password hex
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->{$this->map['password']};
    }

    /**
     * Проверить принадлежность роли
     *
     * @param int $role числовой индетификатор роли
     * @return boolean
     */
    public function isRole($role)
    {
        return (($this->user) && ($this->getRoles() & (int) $role)) ? true : false;
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
        $db  = $app->db;

        $q = $db->createSelectQuery();
        $q->select("*")->from("users")
                ->where($q->expr->eq($value1, $db->quote($this->getPassword())))
                ->where($q->expr->eq($value1, $db->quote($this->getName())))
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