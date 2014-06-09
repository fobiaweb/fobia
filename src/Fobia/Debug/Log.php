<?php
/**
 * Log class  - Log.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Debug;

use \Psr\Log\LogLevel;
use \Psr\Log\LoggerInterface;

/**
 * Log class
 *
 * @package  Fobia.Debug
 */
class Log
{
    /**
     * @var LoggerInterface
     */
    public static $logger = null;

    /**
     * @param LoggerInterface $logger
     */
    public static function setLogger(LoggerInterface $logger)
    {
        self::$logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    public static function getLogger()
    {
        if(!self::$logger) {
            self::$logger = new \Fobia\Debug\MemoryLogger();
        }
        return self::$logger;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function emergency($message, array $context = array())
    {
        self::log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function alert($message, array $context = array())
    {
        self::log(LogLevel::ALERT, $message, $context);
    }

    /**
     * Критические условия.
     *
     * Пример: компонент Application недоступен, непредвиденное исключение.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function critical($message, array $context = array())
    {
        self::log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function error($message, array $context = array())
    {
        self::log(LogLevel::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Пример: Использование устаревшего API, плохое использование API,
     * нежелательные вещи, которые не обязательны.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function warning($message, array $context = array())
    {
        self::log(LogLevel::WARNING, $message, $context);
    }

    /**
     * Нормальные, но значимые события.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function notice($message, array $context = array())
    {
        self::log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Интересные события.
     *
     * Пример: журналирование SQL.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function info($message, array $context = array())
    {
        self::log(LogLevel::INFO, $message, $context);
    }

    /**
     * Подробная информация отладки.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function debug($message, array $context = array())
    {
        self::log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * VarDump
     *
     * @param string $var
     * @param mixed  $message
     * @return null
     */
    public static function dump($var, $message = null)
    {
        if ($message) {
            $message = "({$message}) ";
        }
        $message = "VarDump {$message}::";

        $var_str = print_r($var, true);
        $dump = preg_replace("/\n/", "\n# #", '# #' . trim($var_str)) ;

        self::log(LogLevel::DEBUG, $message . PHP_EOL . $dump );
        return($var_str);

        // $h = fopen('php://stderr', 'a');
        // fwrite($h, $dump);
        // fclose($h);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function log($level, $message, array $context = array())
    {
        self::getLogger()->log($level, $message, $context);
    }

}