<?php

/**
 * Auth class  - Auth.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
namespace Fobia\Auth;

use Fobia\Base\Application;
use Log;
/**
 * Auth class
 *
 * @package Fobia.Auth
 */
class Authentication
{

    /**
     * @var Fobia\Base\Application
     */
    protected $app;

    /**
     * @var Fobia\Base\Model
     */
    public $user;

    /**
     * @var array
     */
    protected $map = array(
        'id'       => 'id',
        'login'    => 'login',
        'password' => 'password',
        'role'     => 'role',
        'online'   => 'online'
    );
    /**
     * @var bool  хранить сесию
     */
    protected $cacheAuth = true;
/**
 * @var string table
 */
    protected $tableName = 'users';

    /**
     *
     * @param \Fobia\Base\Application $app
     * @param type $map
     * @internal
     */
    function __construct(Application $app, $map = array())
    {
        $this->app = $app;
        $this->map = array_merge($this->map, $map);
    }

    public function isRole($role)
    {
        return $this->getRoles() & (int) $role;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->user->{$this->map['login']};
    }

    /**
     * Get user password hex
     * @return string
     */
    public function getPassword()
    {
        return $this->user->{$this->map['password']};
    }

    /**
     * Get user mask roles
     * @return int
     */
    public function getRoles()
    {
        return $this->user->{$this->map['role']};
    }

    /**
     *
     * @param string $login
     * @param string $password
     * @return boolean
     * @api
     */
    public function login($login, $password, $hash = true)
    {
        if ($hash) {
            $password = hash_hmac($this->app['settings']['crypt.method'], $password,
                              $this->app['settings']['crypt.key']);
        }

        $user = $this->checkLogin($login, $password);
        if ( ! $user ) {
            return false;
        }

        $this->user = $user;

        $this->app->session['auth'] = array(
            'user'     => $user,
            'password' => $password,
            'login'    => $login,
            'online'   => time()
        );
        $this->setOnline();

        return true;
    }

    /**
     * @api
     */
    public function logout()
    {
        $this->app['session']['auth'] = array();

        $this->user = null;
    }

    /**
     * Тупо проверка верного логиа и пароля
     * @param string $login
     * @param string $passhex hex string
     * @return boolean
     * @api
     *
     * @return mixeed
     */
    public function checkLogin($login, $passhex)
    {
        $q    = $this->app->db->createSelectQuery();
        $q->select('*')->from($this->tableName)
                ->where($q->expr->eq($this->map['login'],
                                     $this->app->db->quote($login)))
                ->where($q->expr->eq($this->map['password'],
                                     $this->app->db->quote($passhex)))
                ->limit(1);
        $stmt = $q->prepare();
        $stmt->execute();
        return $stmt->fetchObject();
    }

    /**
     * Устанавливает флаг в jnline
     * @return void
     */
    public function setOnline()
    {
        if (!@$this->map['online'] || !@$this->user) {
            return;
        }
        $id = $this->user->{$this->map['id']};

        $q  = $this->app->db->createUpdateQuery();
        $q->update($this->tableName)
                ->set($this->map['online'], 'NOW()')
                ->where($q->expr->eq($this->map['online'], $this->app->db->quote($id)));
        $q->prepare()->execute();
        Log::debug('authenticate:: set online');
    }

    /**
     * Механизм индетификации
     */
    public function authenticate()
    {
        
        if (!  is_array($this->app->session['auth'])) {
            $this->app->session['auth'] = array();
        }

        $login    = $this->app->session['auth']['login'];
        $password = $this->app->session['auth']['password'];
        $online   = $this->app->session['auth']['online'];

        if ($this->cacheAuth) {
            $d_time = time() - $online;
            if ($d_time < 300) {
                $this->user = $this->app->session['auth']['user'];
            }
        }
        
        Log::debug('authenticate:: start', $this->app->session['auth']);

        if (!$this->user && $login && $password) {
            Log::debug("authenticate:: checkLogin; online: $online ($this->cacheAuth)");
            $this->login($login, $password, false);
            // $this->user 
        }
        Log::debug('authenticate:: init');
        // $this->user = $this->app->session['auth']['user'];
        // SELECT roles, (roles & 4) AS r FROM users WHERE roles & (SELECT SUM(id) FROM `roles` WHERE name IN(  'login', 'admin'))
    }

    /**
     * Returns true if and only if an identity is available from storage
     *
     * @return bool
     */
    public function hasIdentity()
    {
        return ($this->user) ? true : false;
    }
}
