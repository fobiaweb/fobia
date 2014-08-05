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
        return $this->user;
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
        $this->user                   = null;
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

        if ($this->cacheAuth) {
            $this->user = $this->getSession('user'); // $this->app->session['auth']['user'];
        } else {
            $login    = $this->getSession('login'); //$this->app->session['auth']['login'];
            $password = $this->getSession('password'); //$this->app->session['auth']['password'];
        }
        $online = $this->getSession('online'); //$this->app->session['auth']['online'];

        $this->logger->info('[authenticate-2]:: Start ');
        $this->logger->debug('[authenticate-2]:: session: ', $this->getSession());

        if ($this->user && $this->dTime) {
            $d_time = time() - (int) $online;
            if ($d_time > $this->dTime) {
                $login      = $this->user->getUsername();
                $password   = $this->user->getPassword();
                $this->user = null;
                $this->logger->info('[authenticate-2]:: превышено время сессии');
            }
        }

        if ( ! $this->user && $login && $password) {
            $this->status = self::STATUS_USERNAME_INCORRECT;
            $this->logger->debug("[authenticate-2]:: checkLogin; online: $online ($this->cacheAuth)");

            $user           = new \Fobia\Auth\BaseUserIdentity();
            $user->login    = $login;
            $user->password = $password;
            if ($user->readData()) {
                $this->user = $user;
                $this->user->setOnline($this->getSidAuth());
            }
        }

        if ($this->user) {
            $this->logger->debug("[authenticate-2]:: User: '{$this->user->getUsername()}'");
            $this->status = self::STATUS_AUTH_OK;
        } else {
            if ($this->cacheAuth) {
                $this->setSession('user', null);
            }
        }

        $this->logger->debug("[authenticate-2]:: Status: '{$this->status}'");
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
        $session = (array) $this->app->session['auth-2'];
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

        $this->app->session['auth-2'] = $session;
    }

    private function getSession($name = null)
    {
        if ($name === null) {
            return $this->app->session['auth-2'];
        }
        return $this->app->session['auth-2'][$name];
    }

    /**
     * Returns true if and only if an identity is available from storage
     *
     * @return bool
     */
    public function hasIdentity()
    {
        return ($this->user && $this->user->getId()) ? true : false;
    }
}