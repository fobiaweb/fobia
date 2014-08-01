<?php
/**
 * StderrProcessor class  - StderrProcessor.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Debug\Processor;

/**
 * StderrProcessor class
 *
 * @package   
 */
class StderrProcessor
{

    protected $handle = null;

    public function __construct()
    {
        if (IS_CLI && ! defined('TEST_BOOTSTRAP_FILE')) {
            $this->handle = fopen('php://stderr', 'a+');
        }
    }

    /**
     * @param  array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        if ( $this->handle) {
            $this->write($record);
        }
        return $record;
    }

    public function write($record)
    {
        $string = sprintf("%-7s %s %s\n",
                          "[{$record['level']}]",
                          $record['message'],
                          $record['context']);
        fwrite($this->handle, $string);
    }

        /**
     * Returns true if current environment supports writing console output to
     * STDOUT.
     *
     * IBM iSeries (OS400) exhibits character-encoding issues when writing to
     * STDOUT and doesn't properly convert ASCII to EBCDIC, resulting in garbage
     * output.
     *
     * @return bool
     */
    protected function hasStdoutSupport()
    {
        return ('OS400' != php_uname('s'));
    }


    /**
     * Возвращает истину, если поток поддерживает раскрашивание.
     *
     * Раскрашивание отключается, если не поддерживается потока:
     *
     *  -  Windows без Ansicon и ConEmu 
     *  -  Не являющиеся TTY консоли
     *
     * @return bool    true if the stream supports colorization, false otherwise
     */
    protected function hasColorSupport()
    {
        if (DIRECTORY_SEPARATOR == '\\') {
            return false !== getenv('ANSICON') || 'ON' === getenv('ConEmuANSI');
        }

        return function_exists('posix_isatty') && @posix_isatty($this->handle);
    }
}