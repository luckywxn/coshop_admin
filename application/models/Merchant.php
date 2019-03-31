<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2019/3/11
 * Time: 11:59
 */

class MerchantModel
{
    /**
     * 数据库类实例
     * @var object
     */
    public $dbh = null;
    public $mch = null;

    /**
     * Constructor
     * @param   object $dbh
     * @return  void
     */
    public function __construct($dbh, $mch = null)
    {
        $this->dbh = $dbh;
        $this->mch = $mch;
    }

    /**
     * 查询供应商列表
     */
    public function searchMerchant($params)
    {
        $filter = array();
        if (isset($params['companyabbreviation']) && $params['companyabbreviation'] != '') {
            $filter[] = " `companyabbreviation` LIKE '%".$params['companyabbreviation']."%' ";
        }
        if (isset($params['companycreditcode']) && $params['companycreditcode'] != '') {
            $filter[] = " `companycreditcode` LIKE '%".$params['companycreditcode']."%' ";
        }
        if (isset($params['status']) && $params['status'] != '') {
            $filter[] = " `status` = {$params['status']} ";
        }
        $where =" WHERE `ok_del` = 0 ";
        if (1 <= count($filter)) {
            $where .= "AND ". implode(' AND ', $filter);
        }

        $result = $params;
        $sql = "SELECT COUNT(*)  from user_merchant $where ";
        $result['totalRow'] = $this->dbh->select_one($sql);
        $result['list'] = array();
        if ($result['totalRow'])
        {
            if( isset($params['page'] ) && $params['page'] == false){
                $sql = "select * from user_merchant $where ";
                $arr = 	$this->dbh->select($sql);
                $result['list'] = $arr;
            }else{
                $result['totalPage'] =  ceil($result['totalRow'] / $params['pageSize']);
                $this->dbh->set_page_num($params['pageCurrent'] );
                $this->dbh->set_page_rows($params['pageSize'] );
                $sql = "select * from user_merchant $where ";
                $arr = 	$this->dbh->select_page($sql);
                $result['list'] = $arr;
            }
        }

        return $result;
    }

    /**
     * 根据id查询供应商
     * @param $id
     * @return mixed
     */
    public function getMerchantById($id){
        $sql = "select * from user_merchant WHERE sysno = $id ";
        return $this->dbh->select_row($sql);
    }

    /**添加供应商
     * @param $data
     * @return bool
     */
    public function addMerchant($data){
        $this->dbh->begin();
        try{
            $res = $this->dbh->insert('user_merchant', $data);
            if (!$res) {
                $this->dbh->rollback();
                return false;
            }
            $this->dbh->commit();
            return $res;
        } catch (Exception $e) {
            $this->dbh->rollback();
            return false;
        }
    }

    public function updateMerchant($id,$data){
        $this->dbh->begin();
        try {
            $res = $this->dbh->update('user_merchant', $data, 'sysno=' . intval($id));
            if (!$res) {
                $this->dbh->rollback();
                return false;
            }
            $this->dbh->commit();
            return true;
        } catch (Exception $e) {
            $this->dbh->rollback();
            return false;
        }
    }




}