<?php


namespace App\Model;

use App\Lib\Helper\SessionHelper;

/**
 * 用户通用类
 * Class User
 * @package App\Model
 */
class User extends BaseModel
{
    public function __construct()
    {
        parent::__construct('user_list');
    }

    public function addUser($data)
    {

    }

    /**
     * 登录
     * @param $userName
     * @param $userPass
     * @return array
     */
    public function login($userName, $userPass)
    {
        $data = $this->select('*', ['user_name' => $userName], true);
        if(empty($data))
        {
            return $this->returnError(404, '找不到该用户');
        }

        if(md5($userPass) != $data['user_pass'])
        {
            return $this->returnError(404, '用户名或密码错误');
        }
        //初始化Session
        foreach($data as $key => $value)
        {
            SessionHelper::set($key, $value);
        }
        $this->mapUserInfo($data);
        SessionHelper::set('user_id', $data['id']);
        return $this->returnSuccess($data);
    }

    /**
     * 返回当前用户
     */
    public function currentUser()
    {
        $data = SessionHelper::getAll();
        if(empty($data) || empty($data['user_id']))
        {
            return $this->returnError(404, '未登录');
        }
        else
        {
            unset($data['user_id']);
            $this->mapUserInfo($data);
            return $this->returnSuccess($data);
        }
    }

    public function getUser($id)
    {
        if($this->id($id))
        {
            $this->mapUserInfo($this->data);
            return $this->returnSuccess($this->data);
        }
        else
            return $this->returnError(404, '用户不存在');
    }

    protected function mapUserInfo(&$data)
    {
        $data['cre_time'] = strtotime($data['cre_time']) * 1000;
        $data['last_login_time'] = strtotime($data['cre_time']) * 1000;
    }
}