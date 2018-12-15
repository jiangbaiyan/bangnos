<?php

use Nos\Comm\Db;
use Nos\Comm\Log;
use Nos\Exception\CoreException;
use Nos\Exception\OperateFailedException;

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
     * @throws OperateFailedException
     * @throws CoreException
     */
    public function create($data){
        $keys = array_keys($data);
        $vals = array_values($data);
        $paras = array_fill(0, count($keys),'?');
        $sql = "insert into {$this->table} (`" . join("`,`", $keys) . "`) values(" . join(",", $paras) . ")";
        $rows = Db::update($sql, $vals);
        if (!$rows){
            Log::fatal('orderModel|create_order_failed|$data:' . json_encode($data) . '|sql:' . $sql);
            throw new OperateFailedException('订单创建失败');
        }
        return $rows;
    }

    /**
     * 删除订单
     * @param bool $isSoft
     * @param string $ext
     * @param array $bind
     * @return mixed
     * @throws OperateFailedException
     * @throws CoreException
     */
    public function delete($isSoft = false, $ext = '', $bind = array()){
        if ($isSoft){
            $time = date('Y-m-d H:i:s');
            $this->update(array(
                'deleted_at' => $time,
                'updated_at' => $time
            ), $ext, $bind);
        } else{
            $sql = "delete from {$this->table} " . $ext;
            $rows = Db::update($sql, $bind);
            if (!$rows){
                Log::fatal('orderModel|delete_order_failed|$data:'  . '|sql:' . $sql . '|bind:' . json_encode($bind));
                throw new OperateFailedException('订单删除失败');
            }
            return $rows;
        }
    }

    /**
     * 获取订单
     * @param array $select
     * @param string $ext
     * @param array $bind
     * @return mixed
     * @throws CoreException
     */
    public function getOrder($select = array(), $ext = '', $bind = array()){
        if (!is_array($select)){
            $fields = $select;
        } else if (empty($select)){
            $fields = '*';
        } else{
            $fields = implode('`, `', $select);
        }
        $sql = "select `{$fields}` from {$this->table} " . $ext;
        return Db::fetchAll($sql, $bind);
    }

    /**
     * 更新订单
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
        var_dump($sql);
        $rows = Db::update($sql, array_merge($vals, $bind));
        if (!$rows){
            Log::fatal('orderModel|update_order_failed|$data:'  . '|sql:' . $sql . '|bind:' . json_encode($bind));
            throw new OperateFailedException('订单更新失败');
        }
        return $rows;
    }

    /**
     * 获取两个订单之间的距离
     * @param $lng1
     * @param $lat1
     * @param $lng2
     * @param $lat2
     * @return float|int
     */
    public function getDistance($lng1, $lat1, $lng2, $lat2)
    {
        $radLat1 = deg2rad($lat1); //deg2rad()函数将角度转换为弧度
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137;
        return round($s,2) . 'km';
    }


}