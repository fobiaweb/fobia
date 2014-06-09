<?php
/**
 * IWebUser class  - IWebUser.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Auth;

/**
 * Компонент приложения пользователь предоставляет идентификационную информацию для текущего пользователя.
 *
 * @author Dmitriy Tyurin <fobia3d@gmail.com>
 * @package Fobia.Auth
 */
interface IWebUser
{

    /**
     * Returns a value that uniquely represents the identity.
     * @return mixed a value that uniquely represents the identity (e.g. primary key value).
     */
    public function getId();

    /**
     * Returns the display name for the identity (e.g. username).
     * @return string the display name for the identity.
     */
    public function getLogin();

    public function getPassword();


    /**
     * Возвращает значение, указывающее, является ли пользователь гостем (не прошел проверку подлинности).
     * @return boolean whether the user is a guest (not authenticated)
     */
    public function getIsGuest();

    /**
     * Выполняет проверку доступа для этого пользователя.
     * @param string $operation название операции, которые должны проверку доступа.
     * @param array $params name-value пары, которые будут переданы для бизнес-правил,
     * связанных с задачами и ролей, назначенных пользователю.
     * @return boolean  могут ли операции  быть выполнены с помощью этого пользователя.
     */
    public function checkAccess($operation, $params = array());
}