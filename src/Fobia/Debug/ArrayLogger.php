<?php
/**
 * ArrayLogger class  - ArrayLogger.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Debug;

/**
 * ArrayLogger class
 *
 * @package   Fobia.Debug
 */
class ArrayLogger extends \Psr\Log\AbstractLogger
{
    protected $list = array();

    public function log($level, $message, array $context = array())
    {
        $this->list[] = array(
            'time'    => sprintf("%6s", substr(microtime(true) - TIME_START, 0, 6)),   
            'memory'  => sprintf("%6s", round(memory_get_usage() / 1024 / 1024, 2) . 'MB'),     
            'message' => $message,      
            'context' => $context      
        );
    }

    public function getRows()
    {
        return $list;
    }

}