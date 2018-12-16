<?php

use Nos\Comm\Validator;
use Nos\Http\Request;
use Nos\Http\Response;

/**
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-14
 * Time: 13:50
 */

class AskForHelp_CancelOrderController extends BaseController{

    public $needAuth = true;

    public $user;
    
    private $orderModel;

    public function checkParam()
    {
        Validator::make($this->params = Request::all(), array(
            'id' => 'required'
        ));
    }

    public function loadModel()
    {
        $this->orderModel = new OrderModel();
    }

    public function indexAction()
    {
        $id = $this->params['id'];
        $ext = 'where id = ?';
        $this->orderModel->delete(true, $ext, array($id));
        $this->orderModel->update(array(
            'status' => OrderModel::STATUS_CANCELED,
        ), $ext, array($id));
        Response::apiSuccess();
    }
}