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
        $sql = "select {$fields} from {$this->table}";
        if (!empty($ext)){
            $sql .= ' ' . $ext;
        }
        return Db::fetchAll($sql, $bind);
    }

}