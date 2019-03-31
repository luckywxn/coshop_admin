<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2019/3/12
 * Time: 16:06
 */

class GoodsModel
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
     * 查询商品列表
     */
    public function searchGoods($params)
    {
        $filter = array();
        if (isset($params['goodsname']) && $params['goodsname'] != '') {
            $filter[] = " `goodsname` LIKE '%".$params['goodsname']."%' ";
        }
        if (isset($params['goodsno']) && $params['goodsno'] != '') {
            $filter[] = " `goodsno` LIKE '%".$params['goodsno']."%' ";
        }
        if (isset($params['status']) && $params['status'] != '') {
            $filter[] = " `status` = {$params['status']} ";
        }
        $where =" WHERE `ok_del` = 0 ";
        if (1 <= count($filter)) {
            $where .= "AND ". implode(' AND ', $filter);
        }

        $result = $params;
        $sql = "SELECT COUNT(*)  from goods $where ";
        $result['totalRow'] = $this->dbh->select_one($sql);
        $result['list'] = array();
        if ($result['totalRow'])
        {
            if( isset($params['page'] ) && $params['page'] == false){
                $sql = "select * from goods $where ";
                $arr = 	$this->dbh->select($sql);
                $result['list'] = $arr;
            }else{
                $result['totalPage'] =  ceil($result['totalRow'] / $params['pageSize']);
                $this->dbh->set_page_num($params['pageCurrent'] );
                $this->dbh->set_page_rows($params['pageSize'] );
                $sql = "select * from goods $where ";
                $arr = 	$this->dbh->select_page($sql);
                $result['list'] = $arr;
            }
        }

        return $result;
    }



}