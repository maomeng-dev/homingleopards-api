<?php


namespace App\Lib\Helper;


class SessionHelper
{
    static $pre = null;
    public static function init($key)
    {
        if(self::$pre == null)
        {
            if(DEV)
            {
                self::$pre = $key . "_dev";
            }
            else
            {
                self::$pre = $key;
            }
        }
        session_start();
    }

    public static function set($name, $value)
    {
        var_dump($_SESSION[self::$pre][$name]);
        $_SESSION[self::$pre][$name] = $value;
    }

    public static function get($name)
    {
        if(!isset($_SESSION[self::$pre][$name]))
        {
            return false;
        }
        return $_SESSION[self::$pre][$name];
    }

    public static function getAll()
    {
        if(!isset($_SESSION[self::$pre]))
        {
            echo 'not exit';
            return false;
        }
        return $_SESSION[self::$pre];
    }

    public static function unsetSession()
    {
        foreach($_SESSION[self::$pre] as $key => $value)
        {
            unset($_SESSION[self::$pre][$key]);
        }
    }

}