<?php


namespace App\Api\Backend\Controller;

use App\Lib\Helper\SessionHelper;
use \App\Model\User as UserModel;

class User extends BaseController
{
    public function login()
    {
        $user_name = \Flight::request()->data->user_name;
        $user_pass = \Flight::request()->data->user_pass;
        if(empty($user_name) || empty($user_pass))
        {
            $this->jsonError(404, '参数不全');
        }
        $user = new UserModel();
        $result = $user->login($user_name, $user_pass);
        if(!$result['success'] || empty($result['data']))
        {
            $this->jsonError($result['code'], $result['msg']);
        }
        $this->jsonSuccess($result['data']);
    }

    public function list()
    {
        $page = \Flight::request()->data->page;
        $pageSize = \Flight::request()->data->pageSize;
        $this->checkUser();
        $user = new UserModel();
        $result = $user->getList("*", [], $page, $pageSize);


    }

    public function current()
    {
        $user = new UserModel();
        $result = $user->currentUser();
        if(!$result['success'] || empty($result['data']))
        {
            $this->jsonError($result['code'], $result['msg']);
        }
        $this->jsonSuccess($result['data']);
    }

    public function info()
    {
        $id = \Flight::request()->query->id;
        if(empty($id))
        {
            $this->jsonError(404, '参数不全');
        }
        $this->checkUser(1, $id);
        $user = new UserModel();
        $info = $user->getUser($id);
        if(empty($info['data']))
        {
            $this->jsonError($info['code'], $info['msg']);
        }
        $this->jsonSuccess($info['data']);
    }

    public function save()
    {
        $this->checkUser();
        $data['nickname'] = \Flight::request()->data->nickname;
        $data['user_name'] = \Flight::request()->data->user_name;
        $data['password'] = \Flight::request()->data->password;
        $data['comment'] = \Flight::request()->data->comment;
        $uid = \Flight::request()->data->id;
        $user = new UserModel();
        if(!empty($uid))
        {
            //更新原有用户
        }
        else
        {
            //新增用户
        }
    }

    public function checkUser($level = 2, $uid = 0)
    {
        $user_id = SessionHelper::get("user_id");
        if(empty($user_id))
        {
            $this->jsonError(404, '未登录');
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

    /**
     * 退出登录
     */
    public function logout()
    {
        SessionHelper::unsetSession();
        $this->jsonSuccess([]);
    }

}