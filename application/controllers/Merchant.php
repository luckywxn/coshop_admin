<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2019/3/11
 * Time: 11:59
 */
class MerchantController extends Yaf_Controller_Abstract {

    /**
     * IndexController::init()
     * @return void
     */
    public function init() {
        # parent::init();
        $user  = Yaf_Registry::get(SSN_VAR);
    }

    /**
     * 显示供应商页面
     * @return string
     */
    public function listAction() {
        $params =  array();
        $this->getView()->make('merchant.list',$params);
    }

    /**
     * 查询供应商数据
     */
    public function merchantListJsonAction(){
        $request = $this->getRequest();
        $search = array(
            'pageSize' => COMMON::PR(),
            'pageCurrent' => COMMON::P(),
            'merchant_no' => $request->getPost('merchant_no',''),
            'merchant_name' => $request->getPost('merchant_name',''),
            'status' => $request->getPost('status','')
        );

        $M = new MerchantModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
        $params =  $M->searchMerchant($search);
        echo json_encode($params);
    }

    /**
     * 显示供应商编辑页面
     */
    public function merchanteditAction(){
        $request = $this->getRequest();
        $id = $request->getParam('id',0);
        $S = new MerchantModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));

        if(!$id){
            $action = "/merchant/merchantNewJson/";
            $params =  array ();
        } else {
            $action = "/merchant/merchantEditJson/";

            $params = $S->getMerchantById($id);
        }
        $params['id'] =  $id;
        $params['action'] =  $action;

        $this->getView()->make('merchant.edit',$params);
    }

    /**
     * 添加供应商
     */
    public function merchantNewJsonAction(){
        $request = $this->getRequest();
        $S = new MerchantModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
        $input = array(
            'merchant_no'      =>  $request->getPost('merchant_no',''),
            'merchant_name'       =>  $request->getPost('merchant_name',''),
            'contact_name'      =>  $request->getPost('contact_name',''),
            'contact_tel'      =>  $request->getPost('contact_tel',''),
            'contact_email'      =>  $request->getPost('contact_email',''),
            'contact_address'      =>  $request->getPost('contact_address',''),
            'status'        =>  $request->getPost('status','1'),
            'isdel'         =>  $request->getPost('isdel','0'),
            'created_at'	=>'=NOW()'
        );

        if($S->addMerchant($input)){
            COMMON::result(200,'添加成功');
        }else{
            COMMON::result(300,'添加失败');
        }

    }

    /**
     * 编辑供应商
     */
    public function merchantEditJsonAction(){
        $request = $this->getRequest();
        $id = $request->getPost('id',0);
        $S = new MerchantModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
        $input = array(
            'merchant_no'      =>  $request->getPost('merchant_no',''),
            'merchant_name'       =>  $request->getPost('merchant_name',''),
            'contact_name'      =>  $request->getPost('contact_name',''),
            'contact_tel'      =>  $request->getPost('contact_tel',''),
            'contact_email'      =>  $request->getPost('contact_email',''),
            'contact_address'      =>  $request->getPost('contact_address',''),
            'status'        =>  $request->getPost('status','1'),
            'isdel'         =>  $request->getPost('isdel','0'),
            'updated_at'	=>'=NOW()'
        );

        if($S->updateMerchant($id,$input)){
            $row = $S->getMerchantById($id);
            COMMON::result(200,'更新成功',$row);
        }else{
            COMMON::result(300,'更新失败');
        }
    }

    /**
     * 删除供应商
     */
    public function merchantdeljsonAction(){
        $request = $this->getRequest();
        $id = $request->getPost('sysno',0);
        $S = new MerchantModel(Yaf_Registry::get("db"),Yaf_Registry::get('mc'));
        $input = array(
            'isdel'         =>  1,
            'updated_at'	=>'=NOW()'
        );
        if($S->updateMerchant($id,$input))
        {
            $row = $S->getMerchantById($id);
            COMMON::result(200,'删除成功',$row);
        }else{
            COMMON::result(300,'删除失败');
        }
    }


}