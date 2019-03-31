<?php
/**
 * 系统资源 Model
 *
 * @author  wuxianneng
 * @date    2016-07-10 16:33
 *
 */

class SystemModel {
	/**
	 * 数据库类实例
	 *
	 * @var object
	 */
	public $dbh = null;

	public $mch = null;

	/**
	 * Constructor
	 *
	 * @param   object  $dbh
	 * @return  void
	 */
	public function __construct($dbh, $mch = null) {
		$this->dbh = $dbh;
		$this->mch = $mch;
	}

	/**
	 * 根据父id获得children权限
	 * pid: 父id
	 * type: 查询类型 0 全部 1菜单2显示权限3操作权限
	 * @return 数组
	 */
	public function getPrivilegeListByPid($pid = 0, $type = 0) {
        $filter = array();
        if($pid > -1){
            $filter[] = " `parent_sysno` = $pid ";
        }
        if($type){
            $filter[] = " `parentsysnotype` = $type ";
        }

        if (1 <= count($filter)) {
            $where = ' WHERE ' . implode(' AND ', $filter);
        }

        $sql = "select * from system_privilege ".$where;
        return  $this->dbh->select($sql);
	}

	/**
	 * 根据父id获得children权限
	 * pid: 父id
	 * type: 查询类型 0 全部 1菜单2显示权限3操作权限
	 * @return 数组
	 */
	public function getPrivilegeListByPidUid($uid =0,$pid = 0, $type = 0) {
		$arr = array();
		if($uid){
			$sql = "select distinct p.* from `system_privilege` p ,   `system_role_r_privilege` rp , `system_user_r_role` ur where ((rp.privilege_sysno = p.sysno and rp.role_sysno = ur.role_sysno and ur.user_sysno = '".$uid."') or p.needcheck = 0 ) and p.isdel = 0 and p.status = 1 ";
			if ($pid > -1) {
				$sql .= " and p.parent_sysno = $pid ";
			}
			if ($type) {
				$sql .= " and p.parentsysnotype = $type ";
			}

			$sql .= "  order by p.menuorder desc ";
			
		}else{
			$sql = "select * from system_privilege where isdel = 0 and status = 1";
			if ($pid > -1) {
				$sql .= " and parent_sysno = $pid ";
			}
			if ($type) {
				$sql .= " and parentsysnotype = $type ";
			}
		}

		$arr = $this->dbh->select($sql);
		return $arr;
	}

	public function addPrivilege($data) {

		return $this->dbh->insert('system_privilege', $data);
	}

	public function updatePrivilege($id = 0, $data = array()) {
		return $this->dbh->update('system_privilege', $data, 'sysno=' . intval($id));
	}

	/**
	 * 根据id获得权限细节
	 * id: 权限id
	 * @return 数组
	 */
	public function getPrivilegeById($id = 0) {
		$sql = "select p.*,(select privilegename from system_privilege pp where pp.sysno = p.parent_sysno) as parent_privilegename from system_privilege p where sysno = $id ";

		return $this->dbh->select_row($sql);
	}

	/**
	 * 根据条件显示权限列表
	 * @return 数组
	 */
	public function searchPrivilege($params) {
		$filter = array();

		if (isset($params['bar_name']) && $params['bar_name'] != '') {
			$filter[] = " p.privilegename LIKE '%" . $params['bar_name'] . "%' ";
		}
		if (isset($params['bar_parentid']) && $params['bar_parentid'] != '-100') {
			$filter[] = "p.parent_sysno  = '" . $params['bar_parentid'] . "'";
		}
		if (isset($params['bar_status']) && $params['bar_status'] != '-100') {
			$filter[] = "p.status  = '" . $params['bar_status'] . "'";
		}

		$where = 'where isdel =0';
		if (1 <= count($filter)) {
			$where .= ' AND ' . implode(' AND ', $filter);
		}

		$sql = "SELECT COUNT(*)  from system_privilege p {$where} ";

		$result = $params;

		$result['totalRow'] = $this->dbh->select_one($sql);

		$result['list'] = array();
		if ($result['totalRow'])
		{

			if( isset($params['page'] ) && $params['page'] == false){
				$sql = "select p.*,(select privilegename from system_privilege pp where pp.sysno = p.parent_sysno) as parent_privilegename from system_privilege p {$where} ";
				if($params['orders'] != '')
					$sql .= " order by ".$params['orders'] ;

				$arr = 	$this->dbh->select($sql);


				$result['list'] = $arr;
			}else{
				$result['totalPage'] =  ceil($result['totalRow'] / $params['pageSize']);

				$this->dbh->set_page_num($params['pageCurrent'] );
				$this->dbh->set_page_rows($params['pageSize'] );


				$sql = "select p.*,(select privilegename from system_privilege pp where pp.sysno = p.parent_sysno) as parent_privilegename from system_privilege p {$where} ";
				if($params['orders'] != '')
					$sql .= " order by ".$params['orders'] ;

				$arr = 	$this->dbh->select_page($sql);


				$result['list'] = $arr;
			}

		}

        //如果在所有列的子菜单中找不到父菜单，则将它赋值为空
        for($i=0;$i<count($result['list']);$i++){
            $flag='no';
            for($j=0;$j<count($result['list']);$j++){
                if($result['list'][$i]['parent_sysno']==$result['list'][$j]['sysno']){
                    $flag='yes';
                    break;
                }
            }
            if($result['list'][$i]['parent_sysno']==0||$flag=='no'){
                $result['list'][$i]['parent_sysno']=null;
            }
        }

		return $result;
	}

	public function addRole($data, $privileges = "") {
		$this->dbh->begin();
		try {
			$res = $this->dbh->insert('system_role', $data);
			if (!$res) {
				$this->dbh->rollback();
				return false;
			}

			$id = $res;
			$res = $this->dbh->delete('system_role_r_privilege', 'role_sysno=' . intval($id));

			if (!$res) {
				$this->dbh->rollback();
				return false;
			}

			if ($privileges !== "") {
				$privilegeArr = explode(",", $privileges);

				if (!empty($privilegeArr)) {
					foreach ($privilegeArr as $value) {
						$privilegesdata = array(
							'role_sysno' => $id,
							'privilege_sysno' => $value,
						);
						//system_role_r_privilege insert
						$res = $this->dbh->insert('system_role_r_privilege', $privilegesdata);

						if (!$res) {
							$this->dbh->rollback();
							return false;
						}
					}

				}
			}

			$this->dbh->commit();
			return $id;

		} catch (Exception $e) {
			$this->dbh->rollback();
			return false;
		}
	}

	public function updateRole($id = 0, $data = array(), $privileges = "") {
		$this->dbh->begin();
		try {
			$res = $this->dbh->update('system_role', $data, 'sysno=' . intval($id));

			if (!$res) {
				$this->dbh->rollback();
				return false;
			}
			$res = $this->dbh->delete('system_role_r_privilege', 'role_sysno=' . intval($id));
			if (!$res) {
				$this->dbh->rollback();
				return false;
			}

			if ($privileges !== "") {
				$privilegeArr = explode(",", $privileges);
				if (!empty($privilegeArr)) {
					foreach ($privilegeArr as $value) {
						$privilegesdata = array(
							'role_sysno' => $id,
							'privilege_sysno' => $value,
						);
						$res = $this->dbh->insert('system_role_r_privilege', $privilegesdata);

						if (!$res) {
							$this->dbh->rollback();
							return false;
						}
					}

				}
			}
			$this->dbh->commit();
			return true;

		} catch (Exception $e) {
			$this->dbh->rollback();
			return false;
		}
	}

	/**
	 * 所有权限
	 */
	public function getAllPrivilege($params) {
		if (isset($params['bar_parentid']) && $params['bar_parentid'] != '-100') {
			$filter[] = "p.parent_sysno  = '" . $params['bar_parentid'] . "'";
		}

		$where = 'p.status = 1 and p.isdel = 0';

		if (1 <= count($filter)) {
			$where .= ' AND ' . implode(' AND ', $filter);
		}

		$sql = "select p.*,(select privilegename from system_privilege pp where pp.sysno = p.parent_sysno) as parent_privilegename from system_privilege p where {$where} ";

		return $this->dbh->select($sql);
	}

	/**
	 * 角色对应权限by数据库
	 */
	public function getRolePrivilege($id = 0) {
		$sql = "select p.* from `system_role_r_privilege` p where role_sysno = $id ";

		return $this->dbh->select($sql);
	}

	/**
	 * 角色对应权限by视图
	 */
	public function getRoleViewPrivilege($roleprivileges = array()) {
		$search = array();
		$privileges = $this->getAllPrivilege($search);
		$privilegesview = array();
		$module = array();

		foreach ($privileges as $privilege) {

			$privilege['check'] = false;
			foreach ($roleprivileges as $roleprivilege) {
				if ($roleprivilege['privilege_sysno'] == $privilege['sysno']) {
					$privilege['check'] = true;
					break;
				}
			}

			$privilegesview[] = $privilege;

			if ($privilege['parent_sysno'] == 0) {
				$module[] = array('mval' => $privilege['privilegename'], 'msysno' => $privilege['sysno'], 'check' => $privilege['check']);
			}
		}

		foreach ($module as $mgroup) {
			foreach ($privilegesview as $group) {
				if($group['parent_sysno']==$mgroup['msysno'])
				{
					$privilegesview2[] = $group;
					foreach ($privilegesview as $group2) {
						if($group2['parent_sysno']==$group['sysno'])
							{
								$privilegesview3[] = $group2;
							}
					}
				}
			}
		}

		$out['privileges'] = $privilegesview;
		$out['privileges2'] = $privilegesview2;
		$out['privileges3'] = $privilegesview3;
		$out['module'] = $module;

		return $out;
	}

	/**
	 * 根据id获得角色细节
	 * id: 权限id
	 * @return 数组
	 */
	public function getRoleById($id = 0) {
		$sql = "select p.* from system_role p where sysno = $id ";

		return $this->dbh->select_row($sql);
	}

	public function getRoleByUserId($userid){
		$sql = "select sr.rolename
				from system_role sr
				left join `system_user_r_role` urr on urr.role_sysno = sr.sysno
				where urr.user_sysno = $userid ";
		return $this->dbh->select_one($sql);
	}

	/**
	 * 根据条件显示角色列表
	 * @return 数组
	 */
	public function searchRole($params) {
		$filter = array();
        if (isset($params['bar_no']) && $params['bar_no'] != '') {
            $filter[] = " p.roleno LIKE '%" . $params['bar_no'] . "%' ";
        }
		if (isset($params['bar_name']) && $params['bar_name'] != '') {
			$filter[] = " p.rolename LIKE '%" . $params['bar_name'] . "%' ";
		}
		if (isset($params['bar_status']) && $params['bar_status'] != '-100') {
			$filter[] = "p.status  = '" . $params['bar_status'] . "'";
		}

		$where = 'p.isdel = 0';
		if (1 <= count($filter)) {
			$where .= ' AND ' . implode(' AND ', $filter);
		}

		$sql = "SELECT COUNT(*)  from system_role p where  {$where} ";

		$result = $params;

		$result['totalRow'] = $this->dbh->select_one($sql);


		$result['list'] = array();
		if ($result['totalRow']) {

			if( isset($params['page'] ) && $params['page'] == false){
				$sql = "select p.* from system_role p where {$where} ";
				if($params['orders'] != '')
					$sql .= " order by ".$params['orders'] ;

				$arr = 	$this->dbh->select($sql);


				$result['list'] = $arr;
			}else{
				$result['totalPage'] =  ceil($result['totalRow'] / $params['pageSize']);
				
				$this->dbh->set_page_num($params['pageCurrent']);
				$this->dbh->set_page_rows($params['pageSize']);

				$sql = "select p.* from system_role p where {$where} ";
				if ($params['orders'] != '') {
					$sql .= " order by " . $params['orders'];
				}

				$arr = $this->dbh->select_page($sql);

				$result['list'] = $arr;
			}
		}

		return $result;
	}

	/**
	 * 记录业务操作日志(依据编号)
	 *
	 * @param  integer      $fid
	 * @param  array        $user
	 * @param  string       $remark
	 * @return void
	 */
	public function addDocLog($input =array())
    {
        return $this->dbh->insert('strongculture_doc_log', $input);
    }
}