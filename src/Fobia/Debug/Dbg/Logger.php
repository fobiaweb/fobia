<?php
/**
 * Logger class  - Logger.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Debug;

/**
 * Logger class
 *
 * @package   Fobia\Debug
 */
class Logger extends \Psr\Log\AbstractLogger
{
    protected $handle;
    protected $memory;
    protected $rows = array();


    public function __construct()
    {
        $this->handle = fopen('php://stderr', 'a+');
        $this->memory = fopen('php://memory', 'r+');
    }

    public function log($level, $message, array $context = array())
    {
        $context = ($context) ? json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : '';
        $row = array(
            'time'   => sprintf("%6s", substr(microtime(true) - TIME_START, 0, 6)),
            'memory' => sprintf("%6s", round(memory_get_usage()/1024/1024, 2).'MB'),
            'level' => $level,
            'message' => $message,
            'context' => $context
        );
        $this->rows[] = $row;
//        $string = '[' . $row['time'] . "s/" . $row['memory'] . '] '
//                . '[' . $row['level'] . '] ' . $row['message'] . ' '
//                . $row['context'] . PHP_EOL;

        $string =  sprintf("[%ss/%s] %-7s %s %s\n", $row['time'], $row['memory'], "[{$row['level']}]", $row['message'], $row['context']);
        fwrite($this->handle, $string);
        fwrite($this->memory, $string);
    }

    public function getHandle()
    {
        return $this->handle;
    }

    public function readMemory()
    {
        rewind($this->memory);
        return stream_get_contents($this->memory);
    }

    public function getRows()
    {
        return $this->rows;
    }
}