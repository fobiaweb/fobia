<?php
/**
 * MySessionHandler class  - MySessionHandler.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

/**
 * MySessionHandler class
 *
 * @package
 */
class MySessionHandler
{

    private $savePath;

    public function __construct()
    {
        session_set_save_handler(
                array($this, "open"), array($this, "close"),
                array($this, "read"), array($this, "write"),
                array($this, "destroy"), array($this, "gc")
        );

        register_shutdown_function('session_write_close');

        file_put_contents(LOGS_DIR . '/session.log', '');

        @session_start();
    }

    public function log($message)
    {
        // echo $message;
        file_put_contents(LOGS_DIR . '/session.log', $message, FILE_APPEND);
    }

    public function open($savePath, $sessionName)
    {
        $this->savePath = $savePath;
        if ( ! is_dir($this->savePath)) {
            mkdir($this->savePath, 0777);
        }

        $this->log( __FUNCTION__ . "  savePath: $savePath\n");
        $this->log( __FUNCTION__ . "  sessionName: $sessionName\n");

        return true;
    }

    public function close()
    {
        return true;
    }

    public function read($id)
    {
        $this->log( __FUNCTION__ . "  id: $id\n");
        return (string) @file_get_contents("$this->savePath/sess_$id");
    }

    public function write($id, $data)
    {
        $this->log( __FUNCTION__ . "  id: $id\n");
        $this->log( __FUNCTION__ . "  data: $data\n");
        return file_put_contents("$this->savePath/sess_$id", $data) === false ? false : true;
    }

    public function destroy($id)
    {
        $this->log( __FUNCTION__ . "  id: $id\n");

        $file = "$this->savePath/sess_$id";
        if (file_exists($file)) {
            unlink($file);
        }

        return true;
    }

    public function gc($maxlifetime)
    {
        $this->log( __FUNCTION__ . "  maxlifetime: $maxlifetime\n");
        foreach (glob("$this->savePath/sess_*") as $file) {
            if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
                unlink($file);
            }
        }

        return true;
    }
}