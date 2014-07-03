<?php
/**
 * ArrayLogger class  - ArrayLogger.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Debug;

use \Psr\Log\AbstractLogger;
use \Psr\Log\LogLevel;

/**
 * ArrayLogger class
 *
 * @package   Fobia.Debug
 */
class ArrayLogger extends AbstractLogger
{
    protected $list = array();
    public $level = 0;
    public $enableRender = true;
    protected $display;
    protected $handle;

    public function __construct()
    {
        if ( IS_CLI && !defined('TEST_BOOTSTRAP_FILE')) {
            $this->handle = fopen('php://stderr', 'a+');
        }
    }

    public function log($level, $message, array $context = array())
    {
        if (self::getLevelCode($level) > $this->level) {
            return;
        }

        $row = array(
            'time'    => sprintf("%6s", substr(microtime(true) - TIME_START, 0, 6)),
            'memory'  => sprintf("%6s", round(memory_get_usage() / 1024 / 1024, 2) . 'MB'),
            'level'   => $level,
            'message' => $message,
            'context' => ($context) ? json_encode($context) : ''
        );

        $this->list[] = $row;
        // error_log($message, 3, LOGS_DIR . '/error.log');

        if ($this->handle) {
            $string =  sprintf("%-7s %s %s\n", "[{$row['level']}]", $row['message'], $row['context']);
            fwrite($this->handle, $string);
        }

        return $row;
    }

    public function getRows()
    {
        return $this->list;
    }

    public static function getLevelCode($level)
    {
        switch ($level) {
            case LogLevel::EMERGENCY:
                $l = 600;
                break;
            case LogLevel::ALERT:
                $l = 550;
                break;
            case LogLevel::CRITICAL:
                $l = 500;
                break;
            case LogLevel::ERROR:
                $l = 400;
                break;
            case LogLevel::WARNING:
                $l = 300;
                break;
            case LogLevel::NOTICE:
                $l = 250;
                break;
            case LogLevel::INFO:
                $l = 200;
                break;
            case LogLevel::DEBUG:
                $l = 100;
                break;
            default:
                $l = 50;
        }
        return $l;
    }

    public static function getLevelName($code)
    {
        $code = (int) $code;
        if ($code <= 100) {
            return LogLevel::DEBUG;
        }
        if ($code <= 200) {
            return LogLevel::INFO;
        }
        if ($code <= 250) {
            return LogLevel::NOTICE;
        }
        if ($code <= 300) {
            return LogLevel::WARNING;
        }
        if ($code <= 400) {
            return LogLevel::ERROR;
        }
        if ($code <= 500) {
            return LogLevel::CRITICAL;
        }
        if ($code <= 550) {
            return LogLevel::ALERT;
        }
        if ($code <= 600) {
            return LogLevel::EMERGENCY;
        }
    }

    public function render($format = 'html')
    {
        echo "handle: {$this->handle};  enableRender: {$this->enableRender}" .BR;
        if ($this->handle || !  $this->enableRender) {
            return;
        }
        $file = __DIR__ . '/view/' . $format . '.php';
        if (!file_exists($file)) {
            trigger_error("Файл не найден '{$file}'", E_USER_WARNING);
        }
        ob_start();
        include $file;
        return ob_get_clean();
    }


    public function registry()
    {
        register_shutdown_function(function(){
            register_shutdown_function(function(){
                echo $this->render('html');
            });
        });
    }
}