<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/15 0015
 * Time: 10:40
 */
class LogModel
{
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
     * @param   object $dbh
     * @return  void
     */
    public function __construct($dbh, $mch = null)
    {
        $this->dbh = $dbh;
        $this->mch = $mch;
    }

    /**
     * 查询岗位列表
     * @author hanshutan
     */
    public function searchPrivilegeLog($params)
    {

        $filter = array();

        if (isset($params['bar_controller']) && $params['bar_controller'] != '') {
            $filter[] = " p.controller LIKE '%" . $params['bar_controller'] . "%' ";
        }
        if (isset($params['bar_action']) && $params['bar_action'] != '') {
            $filter[] = " p.action LIKE '%" . $params['bar_action'] . "%' ";
        }
        if (isset($params['bar_realname']) && $params['bar_realname'] != '') {
            $filter[] = " p.user_realname LIKE '%" . $params['bar_realname'] . "%' ";
        }

        $where = '1';
        if (1 <= count($filter)) {
            $where .= ' AND ' . implode(' AND ', $filter);
        }

        $sql = "SELECT COUNT(*)  from strongculture_system_log_privilege p where  {$where} ";

        $result = $params;

        $result['totalRow'] = $this->dbh->select_one($sql);

        $result['list'] = array();
        if ($result['totalRow'])
        {
            $result['totalPage'] =  ceil($result['totalRow'] / $params['pageSize']);

            $this->dbh->set_page_num($params['pageCurrent'] );
            $this->dbh->set_page_rows($params['pageSize'] );


            $sql = "select p.* from strongculture_system_log_privilege p where {$where} ";
            if($params['orders'] != '')
                $sql .= " order by ".$params['orders'] ;
            else
                $sql .= " order by  created_at desc ";

            $arr = 	$this->dbh->select_page($sql);


            $result['list'] = $arr;
        }

        return $result;
    }

    public function searchDocLog($params)
    {

        $filter = array();

        if (isset($params['bar_id']) && $params['bar_id'] != '-100') {
            $filter[] = " l.`doc_sysno`='{$params['bar_id']}'";
        }
        if (isset($params['bar_doctype']) && $params['bar_doctype'] != '-100') {
            $filter[] = " l.`doctype`='{$params['bar_doctype']}'";
        }

        $where = '1';
        if (1 <= count($filter)) {
            $where .= ' AND ' . implode(' AND ', $filter);
        }

        $sql = "SELECT COUNT(*)  from strongculture_doc_log l where  {$where} ";

        $result = $params;

        $result['totalRow'] = $this->dbh->select_one($sql);

        $result['list'] = array();
        if ($result['totalRow']) {

            if( isset($params['page'] ) && $params['page'] == false){
                $sql = "select l.* from strongculture_doc_log l where {$where} ";
                if($params['orders'] != '')
                    $sql .= " order by ".$params['orders'] ;

                $arr =  $this->dbh->select($sql);


                $result['list'] = $arr;
            }else{
                $result['totalPage'] =  ceil($result['totalRow'] / $params['pageSize']);
                
                $this->dbh->set_page_num($params['pageCurrent']);
                $this->dbh->set_page_rows($params['pageSize']);

                $sql = "select l.* from strongculture_doc_log l where {$where} ";
                if ($params['orders'] != '') {
                    $sql .= " order by " . $params['orders'];
                }

                $arr = $this->dbh->select_page($sql);

                $result['list'] = $arr;
            }
        }

        return $result;
    }


}