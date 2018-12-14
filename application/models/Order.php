<?php

use Nos\Comm\Db;

/**
 * 订单模型
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-14
 * Time: 11:35
 */

class OrderModel{

    private $table = 'orders';

    /**
     * 订单状态
     */
    const
        STATUS_NOT_RELEASED = 0,//草稿(暂时用不到)
        STATUS_RELEASED = 1,//已发布
        STATUS_RUNNING = 2,//正在服务
        STATUS_WAITING_COMMENT = 3,//服务完成等待评价
        STATUS_FINISHED = 4,//评价完成
        STATUS_CANCELED = 5;//订单取消


    /**
     * 订单类别
     */
    const
        TYPE_RUN = 0,//跑腿
        TYPE_ASK = 1,//悬赏提问
        TYPE_STUDY = 2,//学习辅导
        TYPE_TECH = 3,//技术服务
        TYPE_DAILY = 4,//生活服务
        TYPE_OTHER = 5;//其他

    /**
     * 奖励积分数量
     */
    const
        AWARD_SENDER = 1,
        AWARD_RECEIVER = 5;

    /**
     * 创建一条订单
     * @param $data
     * @return mixed
     * @throws \Nos\Exception\CoreException
     */
    public function create($data){
        $keys = array_keys($data);
        $vals = array_values($data);
        $paras = array_fill(0, count($keys),"?");
        $sql = "insert into {$this->table} (`" . join("`,`", $keys) . "`) values(" . join(",", $paras) . ")";
        return Db::update($sql, $vals);
    }

    /**
     * 删除订单
     * @param bool $isSoft
     * @param string $ext
     * @param array $bind
     * @return mixed
     * @throws \Nos\Exception\CoreException
     */
    public function delete($isSoft = false, $ext = '', $bind = array()){
        if ($isSoft){
            $time = date('Y-m-d H:i:s');
            $sql = "update {$this->table} set 'deleted_at' = {$time}" . ' ' . $ext;
        } else{
            $sql = "delete from {$this->table}" . ' ' . $ext;
        }
        return Db::update($sql, $bind);
    }

    /**
     * 获取订单
     * @param array $select
     * @param string $ext
     * @param array $bind
     * @return mixed
     * @throws \Nos\Exception\CoreException
     */
    public function get($select = array(), $ext = '', $bind = array()){
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

    public function update($fields = array(), $ext = '', $bind = array()){


    }

}