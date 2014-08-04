<?php
/**
 * BaseAuthentication class  - BaseAuthentication.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Auth;

use Fobia\Base\Application;

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
     * @var Fobia\Auth\IUserIdentity
     */
    protected $user;

    /**
     * @var bool  хранить сесию
     */
    protected $cacheAuth = true;

    /**
     * @var int интервал онлайна сесии
     */
    protected $dTime = 300;

    protected $status = null; // 'AUTH_NONE';


    /**
     *
     * @param \Fobia\Base\Application $app
     * @internal
     */
    public function __construct(Application $app, IUserIdentity $user)
    {
        $this->app = $app;
        $this->user = $user;

        $this->status = self::STATUS_AUTH_NONE;

        if (class_exists('\Fobia\Debug\Log')) {
            $this->logger = \Fobia\Debug\Log::getLogger();
        } else {
            $this->logger = new \Psr\Log\NullLogger();
        }
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
        $this->logger->debug('[authenticate]:: start ', $this->app->session['auth']);

        if ($this->cacheAuth) {
            $d_time = time() - $online;
            if ($d_time < $this->dTime) {
                $this->user = $this->app->session['auth']['user'];
                $this->status = self::STATUS_AUTH_OK;
            } else {
                $this->user = null;
            }
        }

        if ( ! $this->user && $login && $password) {
            if ($user = $this->checkLogin($login, $password)) {
                if ($this->map['sid']) {
                    if ($this->checkSidAuth($user->{$this->map['sid']})) {
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

            $this->logger->debug("[authenticate]:: checkLogin; online: $online ($this->cacheAuth)");
        }

        $this->logger->debug("[authenticate]:: Status: '{$this->status}'");
        // $this->user = $this->app->session['auth']['user'];
        // SELECT roles, (roles & 4) AS r FROM users WHERE roles & (SELECT SUM(id) FROM `roles` WHERE name IN(  'login', 'admin'))
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


    
}