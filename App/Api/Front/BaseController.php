<?php


namespace App\Api\Front;


use App\Lib\Helper\SessionHelper;

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

    public function checkUser($level = 2, $uid = 0)
    {
        $user_id = SessionHelper::get("user_id");
        if(empty($user_id))
        {
            $this->jsonError(1001, '未登录');
        }
        if($level == 2)
        {
            if(SessionHelper::get("is_super_user") != 1)
            {
                $this->jsonError(404, '没有权限');
            }
        }
        if($level == 1)
        {
            if($user_id != $uid)
            {
                $this->jsonError(404, '没有权限');
            }
        }
        return true;
    }
}