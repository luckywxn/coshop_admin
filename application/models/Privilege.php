<?php
/**
 * 权限 Model
 * @author  wuxianneng
 * @date    2016-01-09 10:00
 * @version $Id$
 */
class PrivilegeModel
{
    /**
     * 数据库类实例
     * @var object
     */
    public $dbh = null;
    /**
     * 缓存类实例
     * @var object
     */
    public $mch = null;


    /**
     * Constructor
     * @param   object  $dbh
     * @param   object  $mch
     * @return  void
     */
    public function __construct($dbh, $mch)
    {
        $this->dbh = $dbh;
        $this->mch = $mch;
    }

	/**
	 * 判断用户是否对某项操作有权限
	 *
	 * @param  string   $m
	 * @param  string   $a
	 * @param  boolean  $online
	 * @param  array    $user
	 * @return boolean
	 */
	public function check($controller, $action, $user)
    {
        $res = true; //默认未记录之操作均允许操作

        if(!$user){
            return false;
        }
				
        $sql = "SELECT * FROM system_privilege WHERE privilegecontroller='#1' AND privilegeaction='#2' and status in (1,2) and isdel = 0 ";
        $rows = $this->dbh->select($sql, $controller, $action);
        if (!empty($rows))
        {
            foreach ($rows as $row) {
                if( !$row['needcheck'])
                return $res;
                    
                 $sql = "select count(rp.sysno) from  `system_role_r_privilege` rp , `system_user_r_role` ur where rp.privilege_sysno = '#1' and rp.role_sysno = ur.role_sysno and ur.user_sysno = '#2' ";

                $cnt = $this->dbh->select_one($sql, $row['sysno'], $user['sysno']);
                 
                 if($cnt > 0){
                        $input= array(
                        'user_sysno'  =>  $user['sysno'],
                        'user_realname'  =>  $user['realname'],
                        'loginip'  => COMMON::getclientIp(),
                        'controller' => $controller,
                        'action' => $action,
                        'url'    => $_SERVER["REQUEST_URI"],
                        'created_at'  =>  '=NOW()'
                      );
                      
                      $this->addPrivilegeLog($input);
                      $res = true;
                      break;
                
                 }else{
                    $res = false;
                 }
            }
            
        }

        return $res;
	}
 

	/**
	 * 记录操作日志(依据编号)
	 *
	 * @param  integer      $fid
	 * @param  array        $user
	 * @param  string       $remark
	 * @return void
	 */
	public function addPrivilegeLog($input =array())
    {
        $this->dbh->insert('system_log_privilege', $input);
    }
    
}
