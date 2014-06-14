<?php

/**
 * Возвращает список стран.
 * --------------------------------------------
 *
 * PARAMS:
 * ------
 * need_all        1 — вернуть список всех стран.
 * offset          отступ, необходимый для выбора определенного подмножества стран.
 *                 положительное число
 * count           количество стран, которое необходимо вернуть.
 *                 положительное число, по умолчанию 100, максимальное значение 1000
 * --------------------------------------------
 *
 * @param  array $params
 * @return mixed
 * @api
 */

$params = $this->prepare(func_get_args());
extract($params);

$q = $this->getDb()->createSelectQuery();
$q->from('st_countries')->select('id')->select('name_rus AS title');
if (!$need_all) {
    if (!$count) {
        $count = 100;
    }
    if (!$offset) {
        $offset = 0;
    }
    
    $q->limit((int)$count, (int)$offset);
}
$stmt = $q->prepare();
$stmt->execute();
$items = $stmt->fetchAll();

$stmt = $this->getDb()->query('SELECT COUNT(*) AS `count` FROM st_countries');
$row = $stmt->fetch();

return array('count' => (int)$row['count'], 'items' => $items);
