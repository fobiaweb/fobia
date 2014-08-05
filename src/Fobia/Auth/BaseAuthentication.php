<?php
/**
 * BaseAuthentication class  - BaseAuthentication.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Auth;

use Fobia\Base\Application;
use Fobia\Auth\BaseUserIdentity as User;

/**
 * BaseAuthentication class
 *
 * @package   Fobia\Auth
 */
class BaseAuthentication
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
     * @var Fobia\Auth\BaseUserIdentity
     */
    protected $user;

    /**
     * @var bool  хранить сесию
     */
    protected $cacheAuth = true;

    /**
     * @var int интервал онлайна сесии
     */
    protected $dTime  = 300;
    protected $status = null; // 'AUTH_NONE';
    /**
     *
     * @param \Fobia\Base\Application $app
     * @internal
     */

    public function __construct(Application $app)
    {
        $this->app    = $app;
        $this->setSession('now', time());
        $this->user   = null;
        $this->status = self::STATUS_AUTH_NONE;

        if (class_exists('\Fobia\Debug\Log')) {
            $this->logger = \Fobia\Debug\Log::getLogger();
        } else {
            $this->logger = new \Psr\Log\NullLogger();
        }
    }

    /**
     * @return \Fobia\Auth\BaseUserIdentity
     */
    public function getUser()
    {
        if ( ! $this->user) {
            $this->_clearUser();
        }
        return $this->user;
    }

    public function getLogin()
    {
        return $this->getUser()->getUsername();
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
        }
        $this->_clearUser();

        $user           = new User();
        $user->login    = $login;
        $user->password = $password;
        if ( ! $user->readData()) {
            return false;
        }

        // Все хорошо - пользователь найден и он ни где не используеться
        $this->user = $user;

        if ($this->cacheAuth) {
            $this->setSession(array('user' => $user));
        } else {
            $this->setSession(array(
                'login'    => $login,
                'password' => $password
            ));
        }
        $this->setSession(array('online' => time()));

        return true;
    }

    /**
     * @return void
     * @api
     */
    public function logout()
    {
        $this->app['session']['auth'] = array();
        $this->setSession('user');

        $this->_clearUser();
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
        $this->status = self::STATUS_AUTH_INCORRECT;
        $user         = null;

        if ($this->cacheAuth) {
            $user = $this->getSession('user');   // $this->app->session['auth']['user'];
        } else {
            $login    = $this->getSession('login');    //$this->app->session['auth']['login'];
            $password = $this->getSession('password'); //$this->app->session['auth']['password'];
        }
        $online = $this->getSession('online');         //$this->app->session['auth']['online'];

        $this->logger->info('[authenticate]:: Start ');
        $this->logger->debug('[authenticate]:: session: ', $this->getSession());

        if ($user && $this->dTime) {
            $d_time = time() - (int) $online;
            if ($d_time > $this->dTime) {
                $login    = $user->getUsername();
                $password = $user->getPassword();
                $user     = null;
                $this->logger->info('[authenticate]:: превышено время кеша сессии');
            }
        }

        if ( ! $user && $login && $password) {
            $this->status = self::STATUS_USERNAME_INCORRECT;
            $this->logger->debug("[authenticate]:: checkLogin; online: $online ($this->cacheAuth)");

            $user           = new \Fobia\Auth\BaseUserIdentity();
            $user->login    = $login;
            $user->password = $password;
            if ($user->readData()) {
                $user->setOnline($this->getSidAuth());
            } else {
                $user = null;
            }
        }

        if ($user) {
            $this->user   = $user;
            $this->logger->debug("[authenticate]:: User: '{$user->getUsername()}'");
            $this->status = self::STATUS_AUTH_OK;
        } else {
            $this->_clearUser();
            if ($this->cacheAuth) {
                $this->setSession('user', null);
            }
        }

        $this->logger->debug("[authenticate]:: Status: '{$this->status}'");
    }

    /**
     * Returns true if and only if an identity is available from storage
     *
     * @return bool
     */
    public function hasIdentity()
    {
        return ($this->getUser()->getId()) ? true : false;
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

    protected function getSidAuth()
    {
        $sid = $this->app->request->getClientIp()
                . ';'
                . session_id();
        return $sid;
    }

    private function setSession($data)
    {
        $session = (array) $this->app->session['auth'];
        if (is_string($data)) {
            if (func_num_args() > 1) {
                $session[$data] = func_get_arg(1);
            } else {
                unset($session[$data]);
            }
        }

        if (is_array($data)) {
            $session = array_merge($session, $data);
        }

        $this->app->session['auth'] = $session;
    }

    private function getSession($name = null)
    {
        if ($name === null) {
            return $this->app->session['auth'];
        }
        return $this->app->session['auth'][$name];
    }

    private function _clearUser()
    {
        $this->user = new User();
        $this->user->setUserData(array(
            'id'       => 0,
            'login'    => '',
            'password' => ''
        ));

        return $this->user;
    }
}