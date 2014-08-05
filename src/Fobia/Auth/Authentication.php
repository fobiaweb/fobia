<?php
/**
 * Auth class  - Auth.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Auth;

use Fobia\Base\Application;

/**
 * Auth class
 *
 * Options:
 *   tableName - название таблиццы
 *   map       - карта-схема таблицы
 *   cacheAuth - хранить сесию
 *   dTime     - интервал онлайна сесии (при включеном cacheAuth)
 *
 *
 * @package Fobia.Auth
 */
class Authentication
{

    const STATUS_USERNAME_INCORRECT = 'USERNAME_INCORRECT';
    const STATUS_SESSION_INCORRECT  = 'SESSION_INCORRECT';
    const STATUS_AUTH_NONE          = 'AUTH_NONE';
    const STATUS_AUTH_INCORRECT     = 'AUTH_INCORRECT';
    const STATUS_AUTH_OK            = 'AUTH_OK';

    /**
     * @var Fobia\Base\Application
     */
    protected $app;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var Fobia\Base\Model
     */
    public $user;

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

    /**
     * @var bool  хранить сесию
     */
    protected $cacheAuth = true;

    /**
     * @var string название таблиццы
     */
    protected $tableName = 'users';

    /**
     * @var int интервал онлайна сесии
     */
    protected $dTime = 300;

    protected $status = null; // 'AUTH_NONE';


    /**
     *
     * @param \Fobia\Base\Application $app
     * @param array $map
     * @internal
     */
    public function __construct(Application $app, $map = array())
    {
        $this->app = $app;
        $this->map = array_merge($this->map, $map);

        $this->status = self::STATUS_AUTH_NONE;

        if (class_exists('\Fobia\Debug\Log')) {
            $this->logger = \Fobia\Debug\Log::getLogger();
        } else {
            $this->logger = new \Psr\Log\NullLogger();
        }
    }

    /********************************************************************************
     * USER Methods
     * ***************************************************************************** */

    /**
     * Get user ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->user->{$this->map['id']};
    }

    /**
     * Get user mask roles
     *
     * @return int
     */
    public function getRoles()
    {
        return $this->user->{$this->map['role']};
    }

    /**
     * Get user login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->user->{$this->map['login']};
    }

    /**
     * Get user password hex
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->user->{$this->map['password']};
    }

    /**
     *
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }


    /********************************************************************************
     * Auth Methods
     * ***************************************************************************** */

    /**
     * Проверить принадлежность роли
     *
     * @param int $role числовой индетификатор роли
     * @return boolean
     */
    public function isRole($role)
    {
        if (!is_numeric($role)) {

        }
        return (($this->user) && ($this->getRoles() & (int) $role))
            ? true
            : false;
    }

    public function isAccess($name, $value = 1)
    {
        return ( $this->user->access
                 &&array_key_exists($name, $this->user->access)
                 && $this->user->access[$name] == $value )
            ? true
            : false;
    }

    /**
     * Зарегистрироваться в системе
     *
     * @param string $login
     * @param string $password
     * @param boolean $hash использовать функцию для хеширования пароля
     * @return boolean
     * @api
     */
    public function login($login, $password, $hash = true)
    {
        // хеш пароля
        if ($hash) {
            $password = $this->app->hash($password);
            // $password = hash_hmac($this->app['settings']['crypt.method'],
            //                      $password,
            //                      $this->app['settings']['crypt.key']);
        }

        // находим пользователя из базы
        $user = $this->checkLogin($login, $password);
        if ( ! $user) {
            $this->status = self::STATUS_USERNAME_INCORRECT;
            return false;
        }

        // если сесии храняться в базе
        if ($this->map['sid']) {
            $sidName = $this->map['sid'];
            $onlineName = $this->map['online'];
            $d_time = time() - strtotime($user->$onlineName);
            // Имееться ли пользователь уже в системе
            // для это проверяем имееться ли запись о сесии в базе
            if ($d_time < $this->dTime && $user->$sidName) {
                if (!$this->checkSidAuth($user->$sidName)) {
                    $this->status = self::STATUS_SESSION_INCORRECT;
                    return false;
                }
            }
        }

        // Все хорошо - пользователь найден и он ни где не используеться
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

        $this->user = null;
    }

    /**
     * Тупо проверка верного логиа и пароля
     *
     * @param string $login
     * @param string $passhex hex string
     * @return mixed
     * @api
     */
    protected function checkLogin($login, $passhex)
    {
        $db = $this->app->db;
        $q  = $db->createSelectQuery();
        $e  = $q->expr;

        // SELECT * FROM users WHERE login = 'user' AND password = 'pass' LIMIT 1
        $q->select('*')->from($this->tableName)
                ->where($e->eq($this->map['login'], $db->quote($login)))
                ->where($e->eq($this->map['password'], $db->quote($passhex)))
                ->limit(1);
        $stmt = $q->prepare();
        $stmt->execute();

        return $stmt->fetchObject();
    }

    /**
     * Проверка сессии на принадлежнасть текущей сесии
     *
     * @param string $userSid сесия в формате 'IP;SID'
     * @return boolean
     */
    protected function checkSidAuth($userSid)
    {
        list($ip, $sid) = explode(';', $userSid);
        if ($ip == $this->app->request->getClientIp() && $sid == session_id()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return void
     */
    public function readAccess()
    {
        $db = $this->app->db;

        $file = CONFIG_DIR . '/access.php';
        if (file_exists($file)) {
            $access = include $file;
        }

        // $roles = array_keys($access);
        foreach ($access as $key => $role) {
            if ($this->isRole($role['mask'])) {
                foreach ($access[$key]['list'] as $a) {
                    $this->user->access[$a] = 1;
                }
                foreach ($access[$key]['value'] as $a => $v) {
                    $this->user->access[$a] = $v;
                }
            }
        }

        // $rows = array();
        if ($this->isRole(4 /* override */) || $this->isAccess('override')) {
            if ($stmt = $db->query("SELECT name, value FROM users_access WHERE user_id = '{$this->getId()}'")) {
                if ($rows = $stmt->fetchAll()) {
                    foreach ($rows as $value) {
                        $this->user->access[$value['name']] = $value['value'];
                    }
                }
            }
        }

        $this->logger->debug('[authenticate]:: read user Access');
    }

    /**
     * Устанавливает флаг в online
     *
     * @return void
     */
    protected function setOnline($sid = true)
    {
        if ( ! @$this->map['online'] || ! @$this->user) {
            return;
        }

        $id = $this->user->{$this->map['id']};
        $db = $this->app->db;
        $q = $db->createUpdateQuery();

        $q->update($this->tableName)
                ->set($this->map['online'], 'NOW()')
                ->where($q->expr->eq($this->map['id'], $db->quote($id)));

        if (@$this->map['sid'] && $sid) {
            $sid = $this->app->request->getClientIp() . ';' . session_id();
            $q->set($this->map['sid'], $db->quote($sid));
        }


        if ($q->prepare()->execute()) {
            $_a = $this->app->session['auth'];
            $_a['online'] = time();
            $_a['user'] = $this->user;
            $this->app->session['auth'] = $_a;
        }

        $this->logger->debug('[authenticate]:: set online');
    }

    /**
     * Механизм индетификации
     *
     * @return void
     */
    public function authenticate()
    {
        if ($this->status !== self::STATUS_AUTH_NONE) {
            return;
        }

        if ( ! is_array($this->app->session['auth']) ) {
            $this->app->session['auth'] = array();
        }
        $this->status = self::STATUS_AUTH_INCORRECT;

        $login    = $this->app->session['auth']['login'];
        $password = $this->app->session['auth']['password'];
        $online   = $this->app->session['auth']['online'];
        // $this->user = $this->app->session['auth']['user'];

        $this->logger->debug('[authenticate]:: start ', $this->app->session['auth']);

        if ($this->cacheAuth) {
            $d_time = time() - $online;
            if ($d_time < $this->dTime) {
                $this->user = $this->app->session['auth']['user'];
                $this->status = self::STATUS_AUTH_OK;
                $this->logger->debug("[authenticate]:: Login from session; online: $online ($this->cacheAuth)");
            } else {
                $this->user = null;
            }
        }

        if ( ! $this->user && $login && $password) {
            if ($user = $this->checkLogin($login, $password)) {
                if ($this->map['sid']) {
                    if ($this->checkSidAuth($user->{$this->map['sid']})) {
                        $this->user = $user;
                        $this->readAccess();
                        $this->setOnline();
                        $this->status = self::STATUS_AUTH_OK;
                    } else {
                        $this->status = self::STATUS_SESSION_INCORRECT;
                    }
                } else {
                    $this->user = $user;
                    $this->readAccess();
                    $this->setOnline();
                    $this->status = self::STATUS_AUTH_OK;
                }
            } else {
                $this->status = self::STATUS_USERNAME_INCORRECT;
            }

            $this->logger->debug("[authenticate]:: checkLogin; online: $online ($this->cacheAuth)");
        }

        $this->logger->debug("[authenticate]:: Status: '{$this->status}'");
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