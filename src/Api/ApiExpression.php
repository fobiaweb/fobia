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
        $array = explode(',', str_replace(' ', '', $var));
        array_walk($array,
                   function(&$item) {
            $item = (int) $item;
            $item = ($item >= 0) ? : null;
        });
        array_unshift($array, null);
        $array = array_unique($array);
        array_shift($array);
        return $array;
    }

    public function fields($var)
    {
        $array = explode(',', str_replace(' ', '', $var));
        array_unshift($array, null);
        $array = array_unique($array);
        array_shift($array);
        return $array;
    }


    public function date($var)
    {

    }


    public function datetime($var)
    {

    }


    public function time($var)
    {

    }
}