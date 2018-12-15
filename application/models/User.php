<?php
/**
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-01
 * Time: 11:55
 */

use Nos\Comm\Db;
use Nos\Comm\Log;
use Nos\Exception\OperateFailedException;
use Nos\Exception\CoreException;

class UserModel{

    private $table = 'users';

    /**
     * @param array $select
     * @param string $ext
     * @param array $bind
     * @return mixed
     * @throws CoreException
     */
    public function getUser($select = array(), $ext = '', $bind = array()){
        if (!is_array($select)){
            $fields = $select;
        } else if (empty($select)){
           $fields = '*';
        } else{
            $fields = implode('`, `', $select);
        }
        $sql = "select {$fields} from {$this->table} " . $ext;
        return Db::fetchAll($sql, $bind);
    }

    /**
     * 通过id获取用户
     * @param $id
     * @param array $select
     * @return mixed
     * @throws CoreException
     */
    public function getUserById($id, $select = array()){
        $data = $this->getUser($select, 'where id = ?', array($id));
        return isset($data[0]) ? $data[0] : array();
    }

    /**
     * 通过uid获取用户
     * @param $uid
     * @param array $select
     * @return mixed
     * @throws CoreException
     */
    public function getUserByUid($uid, $select = array()){
        $data = $this->getUser($select, 'where uid = ?', array($uid));
        return isset($data[0]) ? $data[0] : array();
    }

    /**
     * 创建用户
     * @param $data
     * @return mixed
     * @throws OperateFailedException
     * @throws CoreException
     */
    public function create($data){
        $keys = array_keys($data);
        $vals = array_values($data);
        $paras = array_fill(0, count($keys), '?');
        $sql = "insert into {$this->table}(`" . join("`,`", $keys) . "`) values(" . join(",", $paras) . ")";
        $rows = Db::update($sql, $vals);
        if (!$rows){
            Log::fatal('userModel|create_user_failed|$data:' . json_encode($data) . '|sql:' . $sql);
            throw new OperateFailedException('用户创建失败');
        }
        return $rows;
    }

    /**
     * 更新用户
     * @param $data
     * @param string $ext
     * @param array $bind
     * @return mixed
     * @throws OperateFailedException
     * @throws CoreException
     */
    public function update($data, $ext = '', $bind = array()){
        $keys = array_keys($data);
        $vals = array_values($data);
        foreach ($keys as &$key){
            $key .= '=?';
        }
        $keyStr = join(',', $keys);
        $sql = "update {$this->table} set {$keyStr} " . $ext;
        $rows = Db::update($sql, array_merge($vals, $bind));
        if (!$rows){
            Log::fatal('userModel|update_user_failed|$data:'  . '|sql:' . $sql . '|bind:' . json_encode($bind));
            throw new OperateFailedException('用户更新失败');
        }
        return $rows;
    }
}