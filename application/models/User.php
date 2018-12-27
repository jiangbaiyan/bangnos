<?php
/**
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-01
 * Time: 11:55
 */

use Nos\Exception\CoreException;

class UserModel extends BaseModel {

    public $table = 'users';

    /**
     * 通过uid获取用户
     * @param $uid
     * @param array $select
     * @return mixed
     * @throws CoreException
     */
    public function getByUid($uid, $select = array()){
        $data = $this->getList($select, 'where uid = ?', array($uid));
        return isset($data[0]) ? $data[0] : array();
    }

    /**
     * 根据openid获取用户
     * @param $openid
     * @param array $select
     * @return array
     * @throws CoreException
     */
    public function getByOpenid($openid, $select = array()){
        $data = $this->getList($select, 'where openid = ?', array($openid));
        return isset($data[0]) ? $data[0] : array();
    }

    /**
     * 根据openid更新用户
     * @param $openid
     * @param $data
     * @return mixed
     * @throws CoreException
     * @throws \Nos\Exception\OperateFailedException
     */
    public function updateByOpenid($openid, $data){
        return $this->update($data, 'where openid = ?', $openid);
    }

}