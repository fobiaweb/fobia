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
    }

    public function getRows()
    {
        return $list;
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



    public function render()
    {
        if ($this->handle || !  $this->enableRender) {
            return;
        }

        $htime = sprintf("%-8s", 'time');
        $hlevel = sprintf("%-8s", 'level');

        $html = <<<HTML
<style>
#ac-logger-switch {
  position: fixed;
  background: #FF0000;
  top: 5px;
  right: 5px;
  width: 30px;
  height: 15px;
  z-index: 1000001;
  cursor: pointer;
}
#ac-logger {
  position: fixed;
  top: 0px;
  left: 0px;
  z-index: 1000000;
  font: 9px Tahoma, Geneva, sans-serif;
  border-bottom: 3px solid black;
  height: 60%;
  width: 100%;
  display: none;
}
#ac-logger div.content {
  background: #CCC;
  overflow: auto;
  width: 100%;
  height: 100%;
}
#ac-logger table tr {
  font-family: monospace;
  font-size: 11px;
}
#ac-logger thead {
  background: #666;
}
#ac-logger thead tr th {
  vertical-align: top;
  white-space: pre;
  font-weight: bold;
  text-align: left;
}
#ac-logger thead tr td {
  vertical-align: top;
  text-align: left;
}
#ac-logger tr .number {
  color: #888a85;
}
#ac-logger tr .time {
  color: #f57900;
}
#ac-logger tr .category {
  color: #4e9a06;
}
#ac-logger tr .level {
  color: #578ed5;
  /* color: #3465a4; */
}
#ac-logger tr .messag {
  color: #888a85;
}
#ac-logger tr.error {
  background: #ffb3b3;
}
#ac-logger tr.warning {
  background-color: #e9d5ab;
}
#ac-logger tr.dump {
  /* background-color: rgb(163, 163, 163); */
}
</style>
<div id="ac-logger-switch">DBG</div>
<div id="ac-logger" class="hidden">
    <div class="content">
        <table style="width: 100%;">
            <thead>
                <tr >
                    <th class="number"   style="width: 30px;">№</th>
                    <th class="time"     style="width: 70px;">{$htime}</th>
                    <th class="level"    style="width: 85px;">{$hlevel}</th>
                    <th class="message">message</th>
                </tr>
            </thead>
            <tbody>
HTML;

        foreach ($this->list as $row) {
            $html .= '<tr>'
                . '<td class="number">' . sprintf("%'02d", ++$i) . '</td>'
                . '<td class="time">' .  sprintf("%-9s", $row['time']) . '</td>'
                . '<td class="level">' .  htmlspecialchars(sprintf("%-9s", '[' . $row['level'] . ']')) . '</td>'
                . '<td class="message">' .  $row['message'] .  $row['context'].'</td>'
                . '</tr>';
        }

        $html .= '</tbody> </table> </div> </div>';
        // $html .= '<script src="https://raw.github.com/fobiaweb/debug/develop/debug.js" ></script>';
        // $html .= '<link rel="stylesheet" href="https://raw.githubusercontent.com/fobiaweb/debug/develop/debug.css">';

        $js = file_get_contents(__DIR__ . '/debug.js');

//        $js = preg_replace(array('/\n/', '/ +/'), array('', ' '), $js);
        return $html . '<script>' . $js . '</script>';
    }

}