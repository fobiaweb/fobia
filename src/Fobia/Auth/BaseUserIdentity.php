<?php
/**
 * BaseUserIdentity class  - BaseUserIdentity.php file
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
class BaseUserIdentity extends \Fobia\Base\Model implements IUserIdentity
{

    const TABLE_NAME = 'users';

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

    public function setUserData($data)
    {
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->map)) {
                $this->{$this->map[$key]} = $value;
            } else {
                $this->$key = $value;
            }
        }
    }

    /**
     * Проверить принадлежность роли
     *
     * @param int $role числовой индетификатор роли
     * @return boolean
     */
    public function isRole($role)
    {
        // SELECT roles, (roles & 4) AS r FROM users WHERE roles & (SELECT SUM(id) FROM `roles` WHERE name IN(  'login', 'admin'))
        return ((int) $this->getRoles() & (int) $role) ? true : false;
    }

    public function isAccess($access, $value = 1)
    {
        return ($this->access[$access] == $value) ? true : false;
    }

    /**
     *
     * @param string $access
     * @return string
     */
    public function getAccess($access)
    {
        return $this->access[$access];
    }

    /**
     * Прочесть запись из базы
     * 
     * @return boolean
     */
    public function readData()
    {
        $app = \Fobia\Base\Application::getInstance();
        $db  = $app->db;

        $q = $db->createSelectQuery();
        $q->select("*")->from("users")
                ->where($q->expr->eq($this->map['login'],    $db->quote($this->getUsername())))
                ->where($q->expr->eq($this->map['password'], $db->quote($this->getPassword())))
                ->limit(1);

        $stmt = $q->prepare();

        if ($stmt->execute()) {
            if ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                foreach ($result as $key => $value) {
                    $this->$key = $value;
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Устанавливает флаг в online
     *
     * @return void
     */
    public function setOnline($sid = null)
    {
        $id = $this->getId();
        $db = \Fobia\Base\Application::getInstance()->db;

        $q  = $db->createUpdateQuery();

        $q->update($this->getTableName())
                ->set($this->map['online'], 'NOW()')
                ->where($q->expr->eq($this->map['id'], $db->quote($id)))
        ;

        if (@$this->map['sid'] && $sid) {
            $q->set($this->map['sid'], $db->quote($sid));
        }

        $r = $q->prepare()->execute();
        \Fobia\Debug\Log::debug('[authenticate]:: set online', array(time(), $sid));
        return $r;
    }
}