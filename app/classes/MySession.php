<?php
/**
 * MySession class  - MySession.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

/**
 * MySession class
 *
 * @package   
 */
class MySession
{

    public function regenerate_id()
    {
        session_write_close();
        session_regenerate_id();
    }

    public function id()
    {
        
    }
    
    public function unsetAll()
    {
        
    }

    public function start()
    {
        @session_start();
    }

    public function destroy()
    {

    }

    public function status()
    {

    }
}