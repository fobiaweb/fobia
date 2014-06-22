<?php
/**
 * ApiExpression class  - ApiExpression.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Api;

/**
 * ApiExpression class
 *
 * @package   Api
 */
class ApiExpression
{

    public function numbers($var)
    {
        if (is_string($var)) {
            $array = explode(',', str_replace(' ', '', $var));
        }

        array_walk($array, function(&$item) {
            $item = (int) $item;
            $item = ($item >= 0) ? : null;
        });
        array_unshift($array, null);
        $array = array_unique($array);
        array_shift($array);
        sort($array);
        return $array;
    }

    public function fields($var)
    {
        if (is_string($var)) {
            $array = explode(',', $var);
        }
        array_walk($array, function(&$item) {
            $item = trim( $item );
        });
        array_unshift($array, '');
        $array = array_unique($array);
        array_shift($array);
        sort($array);
        return $array;
    }


    public function date($var)
    {
        list($y, $m, $d) =  explode('-', $var);
    }


    public function datetime($var)
    {

    }


    public function time($var)
    {

    }


    public function valRequired($value, $name, $options = null)
    {
        if (!$value) {
            throw new \Api\Exception\BadRequest($name);
        }
    }
}