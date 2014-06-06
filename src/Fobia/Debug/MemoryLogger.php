<?php
/**
 * MemoryLogger class  - MemoryLogger.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Debug;

/**
 * MemoryLogger class
 *
 * @package   Fobia\Debug
 */
class MemoryLogger extends \Psr\Log\AbstractLogger
{

    protected static $handle;
    protected static $memory;
    protected $lastMsg;

    public function __construct()
    {
        if (IS_CLI) {
            // self::$handle = fopen('php://stderr', 'a+');
        }
        self::$memory = fopen('php://memory', 'r+');
        $this->lastMsg = '';
    }

    public function log($level, $message, array $context = array())
    {
        $time    = sprintf("%6s", substr(microtime(true) - TIME_START, 0, 6));
        $memory  = sprintf("%6s", round(memory_get_usage() / 1024 / 1024, 2) . 'MB');
        $context = ($context) ? @json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : '';

        $this->lastMsg = print_r($message, true)
                . " $context";

        $msg = "[{$time}s/{$memory}] "
                // . sprintf("%-9s ", "[{$level}]")
                . "[{$level}] "
                . $this->lastMsg
                . PHP_EOL;

        if (self::$handle) {
            fwrite(self::$handle, $msg);
        }
        fwrite(self::$memory, $msg);
    }

    /**
     *
     * @return string
     */
    public function getLastString()
    {
        return $this->lastMsg;
    }

    /**
     *
     * @return string
     */
    public function readMemory()
    {
        rewind(self::$memory);
        // Memory usage: 2.07MB (peak: 2.1MB), time: 0.05s

        // $time   = sprintf("%6s", substr(microtime(true) - TIME_START, 0, 6));
        // $memory = sprintf("%6s", round(memory_get_usage() / 1024 / 1024, 2) . 'MB');
        // $peak   = sprintf("%6s", round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB');

        $str = stream_get_contents(self::$memory)
                // . PHP_EOL
                // . "Memory usage: $memory (peak: $peak), time: {$time}s"
                // . PHP_EOL
                ;

        return $str;
    }

    public function __toString()
    {
        return $this->readMemory();
    }
}
