<?php
/**
 * Log class  - Log.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  (c) 2013-2014, Dmitriy Tyurin
 */

namespace Fobia\Debug\Dbg;

/**
 * Log class
 */
class DDLog
{

    private static $_messages = array();
    public static $enable = false;

    /**
     * Добавить лог сообщения
     * @staticvar null $time_start
     * @param string $message
     * @param string $category
     * @param string $level
     */
    public static function trace($message, $category = 'log', $level = 'trace')
    {
        static $time_start = null;
        if ( ! self::$enable) {
            return;
        }
        if ($time_start === null) {
            if (defined('TIME_START')) {
                $time_start = TIME_START;
            } else {
                $time_start = microtime(true);
            }
        }

        if (is_object($category)) {
            $category = get_class($category);
        }

        self::$_messages[] = array(
            'msg'   => $message,
            'ctg'   => $category,
            'level' => strtoupper($level),
            'time'  => sprintf(" %01.6f", microtime(true) - $time_start)
        );
    }

    /**
     * Дамп переменой в лог
     * @param mixed $object
     * @param string $name
     */
    public static function dump($object, $name = null)
    {
        if ( ! self::$enable) {
            return;
        }
        ob_start();
        if(!ini_get('xdebug.coverage_enable')) {
            if (!isset($_SERVER['HTTP_HOST'])) {
                print_r($object);
            }
            // else {
            //     \CVarDumper::dump($object);
            //     $name = '<b>' . $name . '::</b>';
            // }
        } else {
            var_dump($object);
            $name = '<b>' . $name . '::</b>';
        }
        $message = $name  . ob_get_contents();
        ob_end_clean();
        self::trace($message, 'dump');
    }

    /**
     *
     * @param type $message
     * @param type $category
     */
    public static function error($message, $category = 'log')
    {
        self::trace($message, $category, 'error');
    }

    /**
     * Check enable
     * @param bool $check
     * @return bool
     */
    public static function enable($check = null)
    {
        if (func_num_args() == 0) {
            return self::$enable;
        }
        self::$enable = (bool) $check;
    }

    /**
     * Масив логов
     * @return array
     */
    public static function getLogs()
    {
        return self::$_messages;
    }

    /**
     * Вывести логи (печать)
     * @param bool $print печатать
     * @return string
     */
    public static function render($print = true)
    {
        if ( ! self::$enable) {
            return;
        }

        $Logs = self::$_messages;


        ob_start();
        $i = 0;
        if ( ! isset($_SERVER['HTTP_HOST'])) {
            foreach ($Logs as $row) {
                printf("%'02d %-9s [%s] %s: %s\n", ++ $i, $row['time'], $row['level'], $row['ctg'], $row['msg']);
            }
        } else {
            include 'view/view.php';
        }

        $content = ob_get_contents();
        ob_end_clean();

        if ($print) {
            echo $content;
        } else {
            return $content;
        }
    }
}