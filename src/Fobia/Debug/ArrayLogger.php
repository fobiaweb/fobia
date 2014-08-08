<?php
/**
 * ArrayLogger class  - ArrayLogger.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Debug;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

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

    public function __construct($level = 0)
    {
        $this->level = $level;

        $cli = defined('IS_CLI') ? IS_CLI : !isset($_SERVER['HTTP_HOST']);
        if ( $cli && !defined('TEST_BOOTSTRAP_FILE') && !@$_ENV['no_stderr']) {
            $this->handle = fopen('php://stderr', 'a+');
        }
    }

    public function log($level, $message, array $context = array())
    {
        if (self::getLevelCode($level) < $this->level) {
            return;
        }

        $row = array(
            'time'    => sprintf("%6s", substr(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 0, 6)),
            'memory'  => sprintf("%6s", round(memory_get_usage() / 1024 / 1024, 2) . 'MB'),
            'level'   => $level,
            'message' => $message,
            'context' => ($context) ? json_encode($context) : ''
        );

        $this->list[] = $row;

        if ($this->handle) {
            /*
            $color = array(
                'black'     => array('set' => 30, 'unset' => 39),
                'red'       => array('set' => 31, 'unset' => 39),
                'green'     => array('set' => 32, 'unset' => 39),
                'yellow'    => array('set' => 33, 'unset' => 39),
                'blue'      => array('set' => 34, 'unset' => 39),
                'magenta'   => array('set' => 35, 'unset' => 39),
                'cyan'      => array('set' => 36, 'unset' => 39),
                'white'     => array('set' => 37, 'unset' => 39)
            );
            */
            switch ($level) {
                case 'emergency':
                case 'alert':
                case 'critical':
                case 'error':
                    $color = 31;
                    break;
                case 'warning':
                    $color = 33;
                    break;
                case 'notice':
                    $color = 35;
                    break;
                case 'info':
                    $color = 36;
                    break;
                case 'debug':
                    $color = 37;
                    break;
            }
            $string =  sprintf("%-7s %s %s\n", "[{$row['level']}]", $row['message'], $row['context']);
            $string =  sprintf("\033[%sm%s\033[%sm", $color, $string, "00");
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
            case LogLevel::EMERGENCY: return 600;
            case LogLevel::ALERT:     return 550;
            case LogLevel::CRITICAL:  return 500;
            case LogLevel::ERROR:     return 400;
            case LogLevel::WARNING:   return 300;
            case LogLevel::NOTICE:    return 250;
            case LogLevel::INFO:      return 200;
            case LogLevel::DEBUG:     return 100;
            default:                  return 50;
        }
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
        // echo "handle: {$this->handle};  enableRender: {$this->enableRender}" .BR;
        if ($this->handle || !  $this->enableRender) {
            return;
        }
        $file = __DIR__ . '/view/bootstrap/' . $format . '.php';
        if (!file_exists($file)) {
            trigger_error("Файл не найден '{$file}'", E_USER_WARNING);
        }
        ob_start();
        include $file;
        return ob_get_clean();
    }

    /*
    public function registry()
    {
        register_shutdown_function(function(){
            register_shutdown_function(function(){
                echo $this->render('html');
            });
        });
    }
    /* */
}