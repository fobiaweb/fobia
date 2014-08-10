<?php
/**
 * db.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
require_once __DIR__ . '/../vendor/autoload.php';

use Fobia\DataBase\Query\QuerySelect;
use Fobia\DataBase\Handler\ExtendsMySQLHandler;

$dsn = 'mysql:dbname=test;host=127.0.0.1';
$user = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}


$stmt = $pdo->query("SELECT VERSION()");
$r = $stmt->fetch();
print_r($r);

$db = new ExtendsMySQLHandler($pdo);

//$q = new QuerySelect($pdo);
$q = $db->createSelectQuery();
$q->select('*')->from('users')->where($q->expr->eq('id', $pdo->quote('no name')));

$s = $q->prepare();
echo $s->queryString;


