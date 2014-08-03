<?php
/**
 * IUserIdentity class  - IUserIdentity.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Auth;

/**
 * Интерфейс IUserIdentity реализуется классом идентификации пользователя.
 * Удостоверение представляет собой способ проверки подлинности пользователя и извлекать информацию,
 * необходимую для однозначной идентификации пользователя.
 *
 * @package Fobia.Auth
 */
interface IUserIdentity
{

    /**
     * Возвращает ID
     *
     * @return mixed a value that uniquely represents the identity (e.g. primary key value).
     */
    public function getId();

    /**
     * Возвращает отображаемое имя для идентификации (например, login).
     *
     * @return string отображаемое имя для идентификации.
     */
    public function getUsername();

    /**
     * Возвращает пароль в шифрованом виде
     *
     * @return string
     */
    public function getPassword();
}
