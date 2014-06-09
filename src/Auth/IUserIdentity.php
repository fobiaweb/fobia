<?php
/**
 * IUserIdentity class  - IUserIdentity.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Auth;

/**
 * Интерфейс IUserIdentity реализуется классом идентификации пользователя.
 * Удостоверение представляет собой способ проверки подлинности пользователя и извлекать информацию,
 * необходимую для однозначной идентификации пользователя.
 * Обычно он используется с {@link Application::user user application component}.
 *
 * @author Dmitriy Tyurin <fobia3d@gmail.com>
 * @package Auth
 */
interface IUserIdentity
{

    /**
     * Проверку подлинности пользователя.
     * Информация, необходимая для аутентификации пользователя, как правило, при условии, в конструкторе.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate();

    /**
     * Возвращает значение, указывающее, подлинности ли идентичность.
     * @return boolean whether the identity is valid.
     */
    public function getIsAuthenticated();

    /**
     * Returns a value that uniquely represents the identity.
     * @return mixed a value that uniquely represents the identity (e.g. primary key value).
     */
    public function getId();

    /**
     * Returns the display name for the identity (e.g. username).
     * @return string the display name for the identity.
     */
    public function getName();

    /**
     * Returns the additional identity information that needs to be persistent during the user session.
     * @return array additional identity information that needs to be persistent during the user session (excluding {@link id}).
     */
    public function getPersistentStates();
}