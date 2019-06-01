<?php


namespace App\Api\Backend\Controller;


use Illuminate\Support\Facades\App;

class BaseController
{
    public function __construct()
    {
        \App\Api\Backend\BootStrap::init();
    }

    public function jsonSuccess($data, $msg = '操作成功')
    {
        \Flight::json(['errno' => 0, 'errmsg' => $msg, 'data' => $data]);
    }

    public function jsonError($code, $msg, $data = [])
    {
        \Flight::json(['errno' => $code, 'errmsg' => $msg, 'data' => $data]);
    }
}