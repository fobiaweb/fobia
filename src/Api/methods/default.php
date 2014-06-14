<?php
/**
 * default.php file
 *
 * Название метода
 * --------------------------------------------
 *
 * PARAMS:
 * ------
 *  offset      отступ, необходимый для получения определенного подмножества.
 *  count       количество записей, которые необходимо вернуть.
 *
 * --------------------------------------------
 *
 * RESULT
 * ------
 * Возвращаемый результат
 * --------------------------------------------
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 *
 * @api
 */

/* @var $this   \Api\ApiMethod */
/* @var $params array */

if (! $this instanceof \Api\ApiMethod) {
    throw new \Exception('Нельзя прос так выполнить этот файл');
}


$db = $this->app->db;

$this->error('test');

return;

$this->response = 1;
return 5;
