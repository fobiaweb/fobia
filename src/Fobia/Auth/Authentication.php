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
        'online'   => 'online',
        'sid'      => 'sid',
    );

    /**
     * @var bool  хранить сесию
     */
    protected $cacheAuth = true;

    /**
     * @var string название таблиццы
     */
    protected $tableName = 'users';

    /**
     * @var int интервал онлайна
     */
    protected $dTime = 300;

    protected $status = null;//'AUTH_INCORRECT';

    const STATUS_USERNAME_INCORRECT = 'USERNAME_INCORRECT';
    const STATUS_SESSION_INCORRECT  = 'SESSION_INCORRECT';
    const STATUS_AUTH_NONE          = 'AUTH_NONE';
    const STATUS_AUTH_INCORRECT     = 'AUTH_INCORRECT';
    const STATUS_AUTH_OK            = 'AUTH_OK';

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

        $this->status = self::STATUS_AUTH_NONE;
    }
    
    /********************************************************************************
     * USER Methods
     * ***************************************************************************** */

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
    /********************************************************************************
     * Auth Methods
     * ***************************************************************************** */

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
            $password = hash_hmac($this->app['settings']['crypt.method'],
                                  $password, $this->app['settings']['crypt.key']);
        }

        $user = $this->checkLogin($login, $password);
        if ( ! $user) {
            $this->status = self::STATUS_USERNAME_INCORRECT;
            return false;
        }
        if ($this->map['sid']) {
            $sidName = $this->map['sid'];
            $onlineName = $this->map['online'];
            $d_time = time() - strtotime($user->$onlineName);
            if ($d_time < $this->dTime && $user->$sidName) {
                if (!$this->checkSidAuth($user->$sidName)) {
                    $this->status = self::STATUS_SESSION_INCORRECT;
                    return false;
                }
            }
        }


        $this->user = $user;

        $this->app->session['auth'] = array(
            'user'     => $user,
            'password' => $password,
            'login'    => $login,
            'online'   => time()
        );
        $this->setOnline();


        if ($this->map['sid']) {
            $db = $this->app->db;
            $id = (int) $this->user->id;
            $sid = $this->app->request->getClientIp() . ';' . session_id();
            if (!$db->query("UPDATE `{$this->tableName}` SET `{$this->map['sid']}` = '{$sid}' WHERE `{$this->map['id']}` = '{$id}'")) {
                exit('error');
            }
        }

        return true;
    }

    /**
     * @return void
     * @api
     */
    public function logout()
    {
        if ($this->map['sid'] && $this->user) {
            $db = $this->app->db;
            $id = (int) $this->user->id;
            if (!$db->query("UPDATE `{$this->tableName}` SET `{$this->map['sid']}` = NULL WHERE `{$this->map['id']}` = '{$id}'")) {
                exit('error');
            }
        }
        
        $this->app['session']['auth'] = array();
        $this->user                   = null;
    }

    /**
     * Тупо проверка верного логиа и пароля
     *
     * @param string $login
     * @param string $passhex hex string
     * @return mixed
     * @api
     */
    public function checkLogin($login, $passhex)
    {
        $db = $this->app->db;
        $q  = $db->createSelectQuery();
        $e  = $q->expr;

        $q->select('*')->from($this->tableName)
                ->where($e->eq($this->map['login'], $db->quote($login)))
                ->where($e->eq($this->map['password'], $db->quote($passhex)))
                ->limit(1);
        $stmt = $q->prepare();
        $stmt->execute();
        return $stmt->fetchObject();
    }

    public function checkSidAuth($userSid)
    {
        list($ip, $sid) = explode(';', $userSid);
        if ($ip == $this->app->request->getClientIp() && $sid == session_id()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Устанавливает флаг в online
     *
     * @return void
     */
    public function setOnline()
    {
        if ( ! @$this->map['online'] || ! @$this->user) {
            return;
        }
        $id = $this->user->{$this->map['id']};

        $q = $this->app->db->createUpdateQuery();
        $q->update($this->tableName)
                ->set($this->map['online'], 'NOW()')
                ->where($q->expr->eq($this->map['id'],
                                     $this->app->db->quote($id)));
        if ($q->prepare()->execute()) {
            $_a = $this->app->session['auth'];
            $_a['online'] = time();
            $this->app->session['auth'] = $_a;
        }
        Log::debug('authenticate:: set online');
    }

    /**
     * Механизм индетификации
     */
    public function authenticate()
    {
        if ( ! is_array($this->app->session['auth'])) {
            $this->app->session['auth'] = array();
        }
        $this->status = self::STATUS_AUTH_INCORRECT;
        
        $login    = $this->app->session['auth']['login'];
        $password = $this->app->session['auth']['password'];
        $online   = $this->app->session['auth']['online'];

        if ($this->cacheAuth) {
            $d_time = time() - $online;
            if ($d_time < $this->dTime) {
                $this->user = $this->app->session['auth']['user'];
                $this->status = self::STATUS_AUTH_OK;
            } else {
                $this->user = null;
            }
        }

        Log::debug('authenticate:: start', $this->app->session['auth']);

        if ( ! $this->user && $login && $password) {
            if ($user = $this->checkLogin($login, $password)) {
                if ($this->map['sid']) {
                    $sidName = $this->map['sid'];
                    if ($this->checkSidAuth($user->$sidName)) {
                        $this->user = $user;
                        $this->setOnline();
                    } else {
                        $this->status = self::STATUS_SESSION_INCORRECT;
                    }
                } else {
                    $this->user = $user;
                    $this->setOnline();
                }
            } else {
                $this->status = self::STATUS_USERNAME_INCORRECT;
            }

            Log::debug("authenticate:: checkLogin; online: $online ($this->cacheAuth)");
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