<?php
/**
 * access.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
return array(
    // root
    'root'  => array(
        'mask'  => 1,
        'list'  => array(
            'ACCESS_1',
            'ACCESS_2'
        ),
        'value' => array(
            'ACCESS_3' => 11
        )
    ),
    // 2 ---
    // override
    'override'  => array(
        'mask'  => 4,
        'list'  => array(),
        'value' => array(),
    ),
    // 8 ---
    // admin
    'admin' => array(
        'mask' => 16,
        'list'  => array(),
        'value' => array(),
    ),
    // user
    'user' => array(
        'mask' => 32,
        'list'  => array(),
        'value' => array(),
    ),
    // library
    'library' => array(
        'mask'  => 64,
        'list'  => array(),
        'value' => array(),
    ),
);
