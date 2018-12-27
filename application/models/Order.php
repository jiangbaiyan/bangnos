<?php

/**
 * 订单模型
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-14
 * Time: 11:35
 */

class OrderModel extends BaseModel {

    public $table = 'orders';

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
     * 获取两个订单之间的距离
     * @param $lng1
     * @param $lat1
     * @param $lng2
     * @param $lat2
     * @return float|int
     */
    public function getDistance($lng1, $lat1, $lng2, $lat2)
    {
        $lng1 = intval($lng1);
        $lat1 = intval($lat1);
        $lng2 = intval($lng2);
        $lat2 = intval($lat2);
        $radLat1 = deg2rad($lat1); //deg2rad()函数将角度转换为弧度
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137;
        return round($s,2) . 'km';
    }

    /**
     * 根据uuid获取订单
     * @param $uuid
     * @param array $select
     * @return array
     * @throws \Nos\Exception\CoreException
     */
    public function getByUuid($uuid, $select = array()){
        $data = $this->getList($select, 'where uuid = ?', array($uuid));
        return isset($data[0]) ? $data[0] : array();
    }

}