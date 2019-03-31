<?php
use Gregwar\Captcha\CaptchaBuilder;

class IndexController extends Yaf_Controller_Abstract {

	/**
	 * IndexController::init()
	 * @return void
	 */
	public function init() {
		# parent::init();
        $user  = Yaf_Registry::get(SSN_VAR);
    }

	/**
	 * 显示整个后台页面框架及菜单
	 *
	 * @return string
	 */
	public function IndexAction() {
		$params = array();
		$S = new SystemModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
		$params['user']  = Yaf_Registry::get(SSN_VAR);
		$params['navtab']  =  $S->getPrivilegeListByPidUid($params['user']['sysno'],0);

		$params['role'] = $S->getRoleByUserId($params['user']['sysno']);
//		$travelplans =  $T->getTravellist2($params['user']['sysno'],2);
//		$travelhistorys =  $T->getTravellist2($params['user']['sysno'],1);
//		foreach($travelplans as $item){
//			$params['travelplan'][] = $item['placename'];
//		}
//		foreach($travelhistorys as $item){
//			$params['travelhistory'][] = $item['placename'];
//		}

		$this->getView()->make('index.index',$params);
	}

	public function demo_listAction(){
		$params = array();
		$this->getView()->make('index.datagrid',$params);
	}

	public function LoginAction() {
		$params = array();
		$this->getView()->make('index.login',$params);
	}

	public function LogintimeoutAction() {
		$params = array();
		$this->getView()->make('index.logintimeout',$params);
	}

	public function UserLoginAction()
	{
		$request = $this->getRequest();
		$params['username'] = $request->getpost('username','');
		$params['userpwd'] = $request->getpost('passwordhash','');

		$captcha = $request->getpost('captcha','');
		$session = Yaf_Session::getInstance();
		$phrase = $session->get('phrase');
		if($phrase != $captcha){
			$messgin = array();
			$messgin['msg'] = "验证码错误";
			$this->getView()->make('index.login',$messgin);
			return;
		}

		$S = new UserModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));

		if($user = $S->userLogin($params))
		{
			$ip = COMMON::getclientIp();
			$userUpdate = array('lastlogintime'=>'=NOW()','lastloginip'=>$ip);
			if($S->setUserInfo($userUpdate,$user['sysno']))
			{
				unset($user['userpwd']);
				setcookie ( "u_id", $user['sysno'], 0, "/", '.' . WEB_DOMAIN );
				Yaf_Session::getInstance ()->set ( SSN_VAR, $user );
			}
			header("Location: /" );
		}else{
			$messgin = array();
			$messgin['msg'] = "用户名或密码错误";
			$this->getView()->make('index.login',$messgin);
		}

	}

	public function ajaxLoginAction()
	{
		$request = $this->getRequest();
		$params['username'] = $request->getpost('username','');
		$params['userpwd'] = $request->getpost('passwordhash','');
		$S = new UserModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
		if($user = $S->userLogin($params))
		{
			$ip = COMMON::getclientIp();
			$userUpdate = array('lastlogintime'=>'=NOW()','lastloginip'=>$ip);

			if($S->setUserInfo($userUpdate,$user['sysno']))
			{
				unset($user['userpwd']);
				setcookie ( "u_id", $user['sysno'], 0, "/", '.' . WEB_DOMAIN );
				Yaf_Session::getInstance ()->set ( SSN_VAR, $user );
			}
			COMMON::result(200,'登陆成功');
		}else{

			COMMON::result(300,'用户名密码错误');
		}

	}

	public function changepasswordAction() {
        $user = Yaf_Registry::get(SSN_VAR);
		$id = $user['sysno'];
		if(!$id)
		{
			COMMON::result(300,'请重新登录');
			return;
		}
		$U = new UserModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
		$action = "/user/passwordEditJson/";
        $params = $U->getUserById($id);
        $params['userRoles'] = $U->getUserPrivilege($id);
        $params['id'] =  $id;
        $params['action'] =  $action;

		$this->getView()->make('index.changepassword',$params);
	}

	public function  navtabAction(){
		$res = array();
		$request = $this->getRequest();
		$id = $request->getParam('id',0);

		if(!$id){
			 echo json_encode($res);
			 return;
		}
		$user  = Yaf_Registry::get(SSN_VAR);

		$S = new SystemModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
		$arr =  $S->getPrivilegeListByPidUid($user['sysno'],$id,1);

		if(count($arr) >0)
			foreach($arr as $item){
				$menu = array();
				$menu['name'] = $item['privilegename'];
				$children = $S->getPrivilegeListByPidUid($user['sysno'],$item['sysno'],1);

				if(count($children) > 0){
					foreach($children as $child){
						$row = array();
						$row['id'] =  'navab'.$child['sysno'];
						$row['name'] =   $child['privilegename'];
						$row['target'] =  "navtab";
						$row['url'] =   trim($child['privilegeresource']);

						$menu['children'][] = $row;
					}
				}else{
					$menu['url'] = trim($item['privilegeresource']);
					$menu['id'] = 'menu'.$item['sysno'];
				}

				$res[] = $menu;
			}
		echo json_encode($res);

	}

	public function logOutAction()
	{
		$arr = array ();
		Yaf_Session::getInstance ()->set ( SSN_VAR, $arr );
		header("Location: /login");
	}

	public function vcodeAction()
	{
		$builder = new CaptchaBuilder;
		$builder->build();
		$session = Yaf_Session::getInstance();
		$session->set('phrase',$builder->getPhrase());
		header('Content-type: image/jpeg');
		$builder->output();
	}

}
