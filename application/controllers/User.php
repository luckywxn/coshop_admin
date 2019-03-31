<?php
/**
 * 用户信息
 * User: Administrator
 * Date: 2016/11/15 0015
 * Time: 17:09
 */

class UserController extends Yaf_Controller_Abstract
{
    /**
     * IndexController::init()
     *
     * @return void
     */
    public function init()
    {
        # parent::init();
    }

    /**
     * 系统管理-用户列表
     */
    public function listAction()
    {
        $request = $this->getRequest();
        $search = array(
            'realname' =>  $request->getPost('realname',''),
            'pageSize' =>COMMON::PR(),
            'pageCurrent' =>COMMON::P()
        );

        $U = new UserModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
        $params =  $U->getSystemUser($search);
        $this->getView()->make('user.userlist',$params);
    }

    /**
     * 查询用户列表
     */
    public function userlistJsonAction()
    {
        $request = $this->getRequest();
        $search = array(
            'username' => $request->getPost('username',''),
            'realname' => $request->getPost('realname',''),
            'status' => $request->getPost('bar_status',''),
            'pageSize' => COMMON::PR(),
            'pageCurrent' =>COMMON::P()
        );

        $U = new UserModel(Yaf_Registry::get("db"),Yaf_Registry::get('mc'));
        $list =  $U->getSystemUser($search);

        echo json_encode($list);
    }


    /***
     * 添加/修改用户
     */
    public function usereditAction()
    {
        $request = $this->getRequest();
        $id = $request->getParam('id',0);
        $U = new UserModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));

        if(!$id){
            $action = "/user/userNewJson/";
            $params =  array ();
            $params['userRoles'] = array();
        } else {
            $action = "/user/userEditJson/";

            $params = $U->getUserById($id);
            $params['userRoles'] = $U->getUserPrivilege($id);
        }
        $params['id'] =  $id;
        $params['action'] =  $action;

        $roleprivileges = $U->roleList();

        $params['rolelist'] = $roleprivileges;
        $params['module'] = $roleprivileges['module'];

        $this->getView()->make('user.useredit',$params);
    }


    /***
     * 添加新用户的操作
     * @author Alan
     * @time 2016-11-14 11:18:53
     */

    public function userNewJsonAction()
    {
        $request = $this->getRequest();
        $privileges = $request->getPost('role','');
        $U = new UserModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
        $password = $request->getPost('userpwd','');
        $hash = password_hash($password, 1, ['cost' => 10]);

        $input = array(
            'username'      =>  $request->getPost('username',''),
            'userpwd'       =>  $hash,
            'realname'      =>  $request->getPost('realname',''),
            'status'        =>  $request->getPost('status','1'),
            'isdel'         =>  $request->getPost('isdel','0'),
            'created_at'	=>'=NOW()',
            'updated_at'	=>'=NOW()'
        );

        switch ($id = $U->addUser($input,$privileges)) {
            case 'existence':
                COMMON::result(300,'账号已存在');
                break;
            case false:
                COMMON::result(300,'添加失败');
                break;                          
            default:
                $row = $U->getUserById($id);
                COMMON::result(200,'添加成功',$row);
                break;
        }
    }

    /**
     * 编辑用户
     */
    public function userEditJsonAction()
    {
        $request = $this->getRequest();
        $id = $request->getPost('id',0);
        $privileges = $request->getPost('role',"");
        if(!is_array($privileges)){
            $privileges = array(
                'role_sysno' =>$request->getPost('role',"")
            );
        }
        $U = new UserModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
        $password = $request->getPost('userpwd','');
        if($password == '')
        {
            $input = array(
                'nickname'    =>  $request->getPost('nickname',''),
                'username'      =>  $request->getPost('username',''),
                'realname'      =>  $request->getPost('realname',''),
                'sex' => $request->getPost('sex',''),
                'telephone' => $request->getPost('telephone',''),
                'email' => $request->getPost('email',''),
                'birthday' => $request->getPost('birthday',''),
                'nation' => $request->getPost('nation',''),
                'origin' => $request->getPost('origin',''),
                'marriage' => $request->getPost('marriage',''),
                'politics' => $request->getPost('politics',''),
                'education' => $request->getPost('education',''),
                'major' => $request->getPost('major',''),
                'university' => $request->getPost('university',''),
                'address' => $request->getPost('address',''),
                'idcard' => $request->getPost('idcard',''),
                'bankaccount' => $request->getPost('bankaccount',''),
                'memo' => $request->getPost('memo',''),
                'status'        =>  $request->getPost('status','1'),
                'isdel'         =>  $request->getPost('isdel','0'),
                'updated_at'	=>'=NOW()'
            );
        }else{
            $hash = password_hash($password, 1, ['cost' => 10]);
            $input = array(
                'username'      =>  $request->getPost('username',''),
                'userpwd'       =>  $hash,
                'realname'      =>  $request->getPost('realname',''),
                'status'        =>  $request->getPost('status','1'),
                'isdel'         =>  $request->getPost('isdel','0'),
                'updated_at'	=>'=NOW()'
            );
        }

        if($U->updateUser($id,$input,$privileges)){
            $row = $U->getUserById($id);
            COMMON::result(200,'更新成功',$row);
        }else{
            COMMON::result(300,'更新失败');
        }
    }

    public function userDelJsonAction()
    {
        $request = $this->getRequest();
        $id = $request->getPost('sysno',0);

        $U = new UserModel(Yaf_Registry::get("db"),Yaf_Registry::get('mc'));
        if($U->delUser($id))
        {
            $row = $U->getUserById($id);
            COMMON::result(200,'更新成功',$row);
        }else{
            COMMON::result(300,'更新失败');
        }

    }

    public function passwordEditJsonAction()
    {
        $request = $this->getRequest();
        $id = $request->getPost('id',0);
        if(!$id)
        {
            COMMON::result(300,'参数错误');
            return;
        }
        $privileges = "";
        $U = new UserModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));

        $oldpassword = $request->getPost('olduserpwd','');
        $newpassword = $request->getPost('newuserpwd','');

        $row = $U->getUserById($id);

        if(password_verify($oldpassword, $row['userpwd']))
        {
            
        }else{
            COMMON::result(300,'旧密码错误');
            return;
        }

        if(!$newpassword)
        {
            COMMON::result(300,'密码不能为空',$row);
            return;
        }
        $hash = password_hash($newpassword, 1, ['cost' => 10]);
        $input = array(
            'userpwd'       =>  $hash,
            'updated_at'    =>'=NOW()'
        );

        if($U->updateUser($id,$input,$privileges)){
            COMMON::result(200,'更新成功');
        }else{
            COMMON::result(300,'更新失败');
        }
    }

    public function UserinfoAction(){
        $user  = Yaf_Registry::get(SSN_VAR);
        $U = new UserModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
        $user = $U ->getUserById($user['sysno']);
        $user['role'] = $U->getRoleByUserId($user['sysno']);

        $this->getView()->make('user.userinfo',$user);
    }

    public function ajaxUploadAction(){
        $request = $this->getRequest();
        $backid=$request->getPost('backid','');

        $result = array(
            'statusCode'=>'200',
            'message'=>'上传成功',
            'backid'=>$backid,
            'backval'=>''
        );

        $path = "upload/user/";
        $up = new FileUpload;
        //设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
        $up->set("path", $path);
        $up->set("maxsize", 2000000);
        $up->set("allowtype", array("gif", "png", "jpg", "jpeg"));
        $up->set("israndname", true);

        //使用对象中的upload方法， 就可以上传文件， 方法需要传一个上传表单的名子 pic, 如果成功返回true, 失败返回false
        if ($up->upload('file')) {
            $result['backval'] = $path . $up->getFileName();
        } else {
            $result['statusCode']='300';
            $result['message']='上传失败';
        }
        echo json_encode($result);
    }

}