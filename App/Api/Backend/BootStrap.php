<?php

namespace App\Api\Backend;
use App\Lib\Helper\SessionHelper;

class BootStrap
{
    public static function init()
    {
        SessionHelper::init('backend');
    }

    public static function initSession()
    {
        $sessionPath = DEV ? "/tmp/backend/dev/" : "/tmp/backend/produce";
        ini_set('session.save_path', $sessionPath);
        ini_set('session.gc_maxlifetime', 3600 * 4);
    }
}