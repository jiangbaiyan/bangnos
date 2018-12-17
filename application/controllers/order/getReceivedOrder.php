<?php

use Nos\Comm\Page;
use Nos\Http\Request;
use Nos\Http\Response;

/**
 * 获取自己接到的订单
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-14
 * Time: 20:04
 */
class Order_GetReceivedOrderController extends BaseController
{

    public $needAuth = true;

    private $orderModel;

    private $userModel;

    public function checkParam()
    {
        $page = Request::get('page');
        $size = Request::get('size');
        $this->params['page'] = !empty($page) ? $page : 1;
        $this->params['size'] = !empty($size) ? $size : 10;
    }

    public function loadModel()
    {
        $this->orderModel = new OrderModel();
        $this->userModel = new UserModel();
    }


    public function indexAction()
    {
        $size = $this->params['size'];
        $select = array('id', 'title', 'status', 'content', 'price', 'updated_at', 'sender_id');
        $offset = Page::getLimitData($this->params['page'], $size);
        $ext1 = "where sender_id = ? and `deleted_at` is null order by updated_at desc limit {$offset},{$size}";
        $orders = $this->orderModel->getList($select, $ext1, array($this->user->id));
        $ext2 = "where sender_id = ? and `deleted_at` is null";
        $count = $this->orderModel->getTotal($ext2, array($this->user->id));
        foreach ($orders as &$v) {
            $v['content'] = $this->limit($v['content'], 100, '...');
            if (!empty($v['sender_id'])) {
                $sender = $this->userModel->getById($v['sender_id'], array('avatar'));
                $v['sender_avatar'] = $sender['avatar'];
            }
            unset($v['sender_id']);
        }
        $pageData = Page::paginate($count, $this->params['page'], $size);
        Response::apiSuccess(array_merge(array('data' => $orders), $pageData));
    }

    /**
     * 裁剪字符串
     *
     * @param  string $value
     * @param  int $limit
     * @param  string $end
     * @return string
     */
    private function limit($value, $limit = 100, $end = '...')
    {
        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return $value;
        }
        return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')) . $end;
    }
}