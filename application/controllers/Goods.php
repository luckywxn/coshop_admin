<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2019/3/12
 * Time: 16:04
 */

class GoodsController extends Yaf_Controller_Abstract
{
    /**
     * IndexController::init()
     * @return void
     */
    public function init() {
        # parent::init();
        $user  = Yaf_Registry::get(SSN_VAR);
    }

    /**
     * 显示商品页面
     * @return string
     */
    public function listAction() {
        $params =  array();
        $this->getView()->make('goods.list',$params);
    }

    /**
     * 查询供应商数据
     */
    public function goodsListJsonAction(){
        $request = $this->getRequest();
        $search = array(
            'pageSize' => COMMON::PR(),
            'pageCurrent' => COMMON::P(),
            'goodsname' => $request->getPost('goodsname',''),
            'goodsno' => $request->getPost('goodsno',''),
            'status' => $request->getPost('status','')
        );
        $G = new GoodsModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
        $params =  $G->searchGoods($search);
        echo json_encode($params);
    }

}