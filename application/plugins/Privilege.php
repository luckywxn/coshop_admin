<?php
/**
 * 系统权限插件
 * 1. 动作处理之前判断是否有权限并显示信息
 * 2. 动作处理之后判断是否需要记录日志并处理 
 *
 * @author  wuxianneng
 * @date    2016-01-10 15:00
 * @version $Id$
 */

class PrivilegePlugin extends Yaf_Plugin_Abstract
{
    /**
     * 操作对应编号
     *
     * @var integer
     */

	/**
	 * 操作正式处理之前执行，判断输出设定
	 * @return void
	 */
    public function dispatchLoopStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response)
    {
    	
        $P = new PrivilegeModel(Yaf_Registry::get('db'), Yaf_Registry::get('mc'));
        $user = Yaf_Registry::get(SSN_VAR);

        $controller =  $request->getControllerName();
        $action = $request->getActionName();

        if($action == 'login' || $action == 'userlogin' || $action == 'logintimeout' || $action == 'ajaxlogin' || $action == 'vcode')
            return;
        if(!$user){
            if($controller =='Index' && $action == 'index'){
                $response->setRedirect('/login');
            }else if($controller =='Index' && $action == 'register'){
                return;
            }else if($controller =='Index' && $action == 'sendcode'){
                return;
            }else if($controller =='Index' && $action == 'registerjson'){
                return;
            }else{
                COMMON::result(301,'登录超时，请重新登录。');
                exit;
            }
        }
        if ($P->check($controller, $action, $user ))
        {

        }else{
            COMMON::result(300,'您没有权限访问，请联系管理员。');
            exit;
        }
	}
}
