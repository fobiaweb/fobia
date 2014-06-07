<?php

/**
 * Auth class  - Auth.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
use Fobia\Base\Application;

/**
 * Auth class
 *
 * @package
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

    protected $tableName = 'users';

    function __construct(Application $app)
    {
        $this->app = $app;

        $a = $this->app->session['auth'];
    }

    public function isRole($role)
    {
        return $this->getRoles() & (int) $role;
    }

    public function getLogin()
    {
        return $this->user->{$this->map['login']};
    }

    public function getPassword()
    {
        return $this->user->{$this->map['password']};
    }

    public function getRoles()
    {
        return $this->user->{$this->map['role']};
    }

    public function login($login, $password)
    {
        $password = hash_hmac($this->app['settings']['crypt.method'], $password,
                              $this->app['settings']['crypt.key']);

        $user = $this->checkLogin($login, $password);
        if ( ! $user) {
            return false;
        }

        $this->user = $user;

        $this->app->session['auth'] = array(
            'user'     => $user,
            'password' => $password,
            'login'    => $login,
        );
    }

    public function logout()
    {
        $this->app['session']['auth'] = array();
        $this->user                   = null;
    }

    public function checkLogin($login, $password)
    {
        $q    = $this->app->db->createSelectQuery();
        $q->select('*')->from($this->tableName)
                ->where($q->expr->eq($this->map['login'],
                                     $this->app->db->quote($login)))
                ->where($q->expr->eq($this->map['password'],
                                     $this->app->db->quote($password)))
                ->limit(1);
        $stmt = $q->prepare();
        $stmt->execute();
        return $stmt->fetchObject();
    }

    public function setOnline()
    {
        $id = $this->user->{$this->map['id']};
        $q  = $this->app->db->createUpdateQuery();
        $q->update($this->tableName)->set('online', $this->app->db->quote($id));
        $q->prepare()->execute();
    }

    public function authenticate()
    {
        // if (!$this->app->session['auth']) {
        //     $this->app->session['auth'] = array(
        //         'user' => ''
        //     );
        // }
        // $auth = $this->app->session['auth'];
        $login    = $this->app->session['auth']['login'];
        $password = $this->app->session['auth']['password'];

        $this->user = $this->checkLogin($login, $password);
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
/*
    $authenticated = false;
    if ( !$session->isValid( $credentials ) )
    {
        // create the authentication object
        $authentication = new ezcAuthentication( $credentials );
        $authentication->session = $session;

     authenticated
 *
 */