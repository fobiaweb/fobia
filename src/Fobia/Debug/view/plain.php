<?php
/**
 * plain.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
echo "\n";
foreach ($this->list as $row) {
    echo sprintf("%s  %s  %s\n",
            $row['time'],
            "[{$row['level']}]",
            $row['message'] . $row['context']);
}