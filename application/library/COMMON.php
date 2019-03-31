<?php
/**
 * 公共方法类
 *
 * @author  wuxianneng
 * @date    2011-06-15 15:00
 * @version $Id$
 */

class COMMON
{
    // 页码
    private static $page  = 1;
    // 每页显示记录数量
    private static $rows  = 20;

    /**
     * Constructor
     *
     * @param   void
     * @return  void
     */
    public function __construct()
    {
        //construct class
    }

    /**
     * return result data
     *
     * @param   int         $code  200 ok  300 failed 301 timeout
     * @param   string      $msg
     * @return  string
     */
    public static function result($code, $msg='',$exten = array())
    {
        $res = array(
            "statusCode"    => $code, //"200"
            "message"       => $msg,
        ) ;
        if(is_array($exten) && count($exten) > 0)
            $res  += $exten ;
        echo json_encode($res);
    }

    /**
     * page number
     *
     * @param   int         $val
     * @return  int
     */
    public static function P($val = null)
    {
        if (isset($_REQUEST['pageCurrent']) && is_numeric($_REQUEST['pageCurrent']))
        {
            self::$page = $_REQUEST['pageCurrent'];
        }
        else if (null !== $val)
        {
            self::$page = intval($val);
        }
        return self::$page;
    }

    /**
     * rows per page
     *
     * @param   int         $val
     * @return  int
     */
    public static function PR($val = null)
    {
        if (isset($_REQUEST['pageSize']) && is_numeric($_REQUEST['pageSize']))
        {
            self::$rows = $_REQUEST['pageSize'];
        }
        else if (null !== $val)
        {
            self::$rows = intval($val);
        }
        return self::$rows;
    }

    /**
     * 获取ip
     */
    public static function getclientIp(){

        if (getenv('HTTP_CLIENT_IP')) { 
            $ip = getenv('HTTP_CLIENT_IP'); 
        } 
            elseif (getenv('HTTP_X_FORWARDED_FOR')) { 
        $ip = getenv('HTTP_X_FORWARDED_FOR'); 
        } 
        elseif (getenv('HTTP_X_FORWARDED')) { 
            $ip = getenv('HTTP_X_FORWARDED'); 
        } 
        elseif (getenv('HTTP_FORWARDED_FOR')) { 
            $ip = getenv('HTTP_FORWARDED_FOR'); 
        } 
        elseif (getenv('HTTP_FORWARDED')) { 
            $ip = getenv('HTTP_FORWARDED'); 
        } 
        else { 
            $ip = $_SERVER['REMOTE_ADDR']; 
        } 

        return $ip;     
    }

    /**
     * 随机获得编码id
     */
    public static function getCodeId($prefix='') {
        list($min,$sec) = explode(" ",microtime());
        $min = substr($min,2,6);
        $id = $prefix.$sec.$min.mt_rand(100,999);
        return $id;
    }

    /**
     * 计算相差几天
     * #$diffday = count_days(strtotime(date("Ymd")),strtotime(SERVERSTART));
     */
    public static function count_days($a,$b){
        $a_dt=getdate($a);
        $b_dt=getdate($b);
        $a_new=mktime(12,0,0,$a_dt['mon'],$a_dt['mday'],$a_dt['year']);
        $b_new=mktime(12,0,0,$b_dt['mon'],$b_dt['mday'],$b_dt['year']);
        return round(($a_new-$b_new)/86400);
    }

    /**
     * 新建目录
     */
    public static function makeDir($folder)
    {
        $reval = false;
        if (!file_exists($folder)) {
            @umask(0);
            preg_match_all('/([^\/]*)\/?/i', $folder, $atmp);
            $base = ($atmp[0][0] == '/') ? '/' : '';
            foreach ($atmp[1] AS $val) {
                if ('' != $val) {
                    $base .= $val;
                    if ('..' == $val || '.' == $val) {
                        $base .= '/';
                        continue;
                    }
                } else {
                    continue;
                }

                $base .= '/';

//         echo $base."<br>";
                if (!file_exists($base)) {
                    if (@mkdir(rtrim($base, '/'), 0777)) {
                        @chmod($base, 0777);
                        $reval = true;
                    }
                }
            }
        } else {
            $reval = is_dir($folder);
        }

        clearstatcache();
        return $reval;
    }

    public static function getExamStatus($status ='') {
        switch ($status){
            case '1':
                return '新建';
            case '2':
                return '暂存';
            case '3':
                return '已提交';
            case '4':
                return '已审核';
            case '5':
                return '已完成';
            case '6':
                return '作废';
            default:
                return '新建';
        }
    }
}
