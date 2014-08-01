<?php
/**
 * CliLogger class  - CliLogger.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Debug;
use \Psr\Log\AbstractLogger;
use \Psr\Log\LogLevel;
/**
 * CliLogger class
 *
 * @package   Fobia\Debug
 */
class CliLogger extends AbstractLogger
{
    public function log($level, $message, array $context = array())
    {
        if (ArrayLogger::getLevelCode($level) > $this->level) {
            return;
        }

        $this->list[] = array(
            'time'    => sprintf("%6s", substr(microtime(true) - TIME_START, 0, 6)),
            'memory'  => sprintf("%6s", round(memory_get_usage() / 1024 / 1024, 2) . 'MB'),
            'level'   => $level,
            'message' => $message,
            'context' => $context
        );
    }
}