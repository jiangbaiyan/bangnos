<?php

use Nos\Comm\Page;
use Nos\Comm\Validator;
use Nos\Http\Request;
use Nos\Http\Response;

/**
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-14
 * Time: 20:03
 */

class HelpOthers_GetReleasedOrderListController extends BaseController{

    private $orderModel;

    public function checkParam()
    {
        Validator::make($this->params = Request::all(),array(
            'longitude' => 'required',
            'latitude'  => 'required'
        ));
        $this->params['page'] = isset($this->params['page']) ? $this->params['page'] : 1;
        $this->params['size'] = isset($this->params['size']) ? $this->params['size'] : 10;
    }

    protected function loadModel()
    {
        $this->orderModel = new OrderModel();
    }

    public function indexAction()
    {
        $page = $this->params['page'];
        $size = $this->params['size'];
        $offset = Page::getLimitData($page,$size);
        $now = date('Y-m-d H:i:s');
        $select = array('id','title','content','price','longitude','latitude','created_at','sender_id');
        if (!isset($this->params['type'])){
            $ext = "where status = ? and begin_time < ? and end_time > ? order by created_at desc limit {$offset},{$size}";
            $bind = array(OrderModel::STATUS_RELEASED, $now, $now);
        } else{
            $ext = "where status = ? and type = ? and begin_time < ? and end_time > ? order by created_at desc limit {$offset},{$size}";
            $bind = array(OrderModel::STATUS_RELEASED, $this->params['type'], $now, $now);
        }
        $orders = $this->orderModel->getOrder($select, $ext ,$bind);
        if (empty($orders)){
            Response::apiSuccess();
        }
        $curLng = $this->params['longitude'];
        $curLat = $this->params['latitude'];
        foreach ($orders as &$order){
            $orderLng = $orders['longitude'];
            $orderLat = $orders['latitude'];
            $order['$distance'] = $this->orderModel->getDistance($curLng, $curLat, $orderLng, $orderLat);
        }
        array_multisort(array_column($orders, 'distance'), SORT_ASC, SORT_NUMERIC, $orders);
        $count = count($orders);
        $pageData = Page::paginate($count, $page, $size);
        Response::apiSuccess(array_merge(array('data' => $orders), $pageData));
    }
}