<?php
/**
 * config.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
return array(
    // Application
    'mode' => 'development',

    // Вьюшки, Директория шаблонов
    'templates.path' => 'app/view',
    'view' => null,
    'smarty.left_delimiter'  => '{{',
    'smarty.right_delimiter' => '}}',

    // Логер
    'debug'       => true,
    'log.enabled' => true,
    'log.level'   => 600,

    // Cookies
    'cookies.encrypt'  => false,
    'cookies.lifetime' => '20 minutes',
    'cookies.path'     => '/',
    'cookies.domain'   => null,
    'cookies.secure'   => false,
    'cookies.httponly' => false,

    // Encryption
    'crypt.key'    => 'A9s_lWeIn7cML8M]S6Xg4aR^GwovA&UN',
    'crypt.cipher' => MCRYPT_RIJNDAEL_256,
    'crypt.mode'   => MCRYPT_MODE_CBC,
    'crypt.method' => 'sha256',

    // Session
    'session.handler'   => null,
    'session.flash_key' => 'slimflash',
    'session.encrypt'   => false,

    // HTTP
    'http.version' => '1.1',

    // DataBase
    'database.dns'     => 'mysql://root@localhost/test',
    // 'database.dns'     => 'mysql://srv55412_ab@localhost/srv55412_ab',
    // 'database.password'     => 'abpass',
    'database.charset' => 'utf8',

    // Controller
    'controller.prefix' => 'Controller\\',

);
