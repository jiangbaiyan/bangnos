<?php
/**
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-01
 * Time: 11:55
 */

use Nos\Comm\Db;

class UserModel{

    private $table = 'users';

    /**
     * @param array $select
     * @param string $ext
     * @param array $bind
     * @return mixed
     * @throws \Nos\Exception\CoreException
     */
    public function getUser($select = array(), $ext = '', $bind = array()){
        if (!is_array($select)){
            $fields = $select;
        } else if (empty($select)){
           $fields = '*';
        } else{
            $fields = implode('`, `', $select);
        }
        $sql = "select {$fields} from {$this->table} ";
        if (!empty($ext)){
            $sql .= ' ' . $ext;
        }
        return Db::fetchAll($sql, $bind);
    }

    /**
     * 通过id获取用户
     * @param $id
     * @param array $select
     * @return mixed
     * @throws \Nos\Exception\CoreException
     */
    public function getUserById($id, $select = array()){
        return $this->getUser($select, 'where id = ?', array($id));
    }

    /**
     * 通过uid获取用户
     * @param $uid
     * @param array $select
     * @return mixed
     * @throws \Nos\Exception\CoreException
     */
    public function getUserByUid($uid, $select = array()){
        return $this->getUser($select, 'where uid = ?', array($uid));
    }

    /**
     * 创建用户
     * @param $data
     * @return mixed
     * @throws \Nos\Exception\CoreException
     */
    public function create($data){
        $keys = array_keys($data);
        $vals = array_values($data);
        $paras = array_fill(0, count($keys), '?');
        $sql = "insert into {$this->table}(`" . join("`,`", $keys) . "`) values(" . join(",", $paras) . ")";
        return Db::update($sql, $vals);
    }

    /**
     * 更新用户
     * @param $data
     * @param string $ext
     * @return mixed
     * @throws \Nos\Exception\CoreException
     */
    public function update($data, $ext = ''){
        $keys = array_keys($data);
        $vals = array_values($data);
        foreach ($keys as &$key){
            $key .= '=?';
        }
        $keyStr = join(',', $keys);
        $sql = "update {$this->table} set {$keyStr} " . $ext;
        return Db::update($sql, $vals);
    }
}