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

    /**
     * 保存用户信息
     * @param     $data
     * @param int $uid
     * @return array
     */
    public function saveUser($data, $uid = 0)
    {
        if($uid == 0)
        {
            $uid = $this->addUser($data);
            if($uid == 0)
            {
                return $this->returnError(1001, '新增用户失败');
            }
        }
        else
        {
            $result = $this->updateUser($data);
            if(empty($result))
            {
                return $this->returnError(1002, '更新用户失败');
            }
        }
        $this->id($uid);
        $this->mapUserInfo($this->data);
        $this->returnSuccess($this->data);

    }


    protected function addUser($data)
    {

    }

    protected function updateUser($data, $uid)
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
            return $this->returnError(1003, '找不到该用户');
        }

        if(md5($userPass) != $data['user_pass'])
        {
            return $this->returnError(1004, '用户名或密码错误');
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
            return $this->returnError(1005, '未登录');
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
            return $this->returnError(1003, '用户不存在');
    }

    public function mapUserInfo(&$data)
    {
        $data['id'] = intval($data['id']);
        $data['cre_time'] = strtotime($data['cre_time']) * 1000;
        $data['last_login_time'] = strtotime($data['cre_time']) * 1000;
    }
}