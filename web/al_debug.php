<?php
/**
 * al_debug.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

require_once __DIR__ . '/../app/bootstrap.php';

header('Content-Type:text/plain; charset=utf-8');

$file = LOGS_DIR . '/debug/' . @$_GET['f'] . '.log';
if (file_exists($file)) {
    echo file_get_contents(LOGS_DIR . '/debug/' . $f . '.log');
} else {
    echo "No log session.";
}
