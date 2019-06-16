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
        $this->setLoginCookie($result['data'], 1);
        $this->jsonSuccess($result['data']);
    }

    public function list()
    {
        $page = \Flight::request()->data->page;
        $pageSize = \Flight::request()->data->pageSize;
        $page = intval($page) ? intval($page) : 1;
        $pageSize = intval($pageSize) ? intval($pageSize) : 10;
        $this->checkUser();
        $user = new UserModel();
        $result = $user->getList("*", [], $page, $pageSize);
        if(!$result['success'])
        {
            $this->jsonError(502, '服务器内部错误');
        }
        $data['page']['current'] = $page;
        $data['page']['size'] = $pageSize;
        $data['page']['total'] = $result['count'];
        $data['page']['page_total'] = ceil($result['count'] / $pageSize);
        if(empty($result['data']))
        {
            $this->jsonError(401, $result['msg'], []);
        }
        foreach($result['data'] as $item)
        {
            $user->mapUserInfo($item);
            $data['list'][] = $item;
        }
        $this->jsonSuccess($data);
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
            $this->current();
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

    }

    public function checkUser($level = 2, $uid = 0)
    {
        return true;
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
        $this->setLoginCookie([], 2);
        $this->jsonSuccess([]);
    }

    /**
     * @param $data
     * @param $type
     */
    protected function setLoginCookie($data, $type)
    {
        $key = DEV ? 'DT_Mark_F' : 'T_Mark_F';
        if($type == 2)
        {
            //clear cookie;
            setcookie($key, '', -1, 'homingleopards.org');
            return true;
        }
        $rand = rand(100000, 999999);
        //set cookie;
        $content = 'homingleopards' . "," . $data['id'] . "," . $rand . "," . md5(getConfig('common.salt'));
        setcookie($key, $content, time() + 14400, 'homingleopards.org');
    }

}