<?php
function buildUrl($url,array $data)
{
    //$cdkey = "test";
    $cdkey = '2f719196a4304168808a269b997dbb1f';
    if(!empty($data) || !empty($url))
    {
        $url2 = '?';
        $url3 = '';
        $url4 = '';
        ksort($data);
        $count = count($data);
        $num = 0;
        foreach ($data as $key => $value)
        {
            $num++;
            $url3 .= $key."=".$value;
            $url4 .= $key."=".urlencode($value);
            if($num!=$count)
            {
                $url3 .= '&';
                $url4 .= '&';
            }
        }
        //echo '$url3='.$url3."---------";
        //echo '$url4='.$url4."---------";
        $sign = md5($cdkey.md5($url3.$cdkey));
        return $url.$url2.$url4.'&sign='.$sign;
    }
}
//$url = 'http://123.57.33.12/';
$url = 'http://10.3.4.69/';
if(!empty($_REQUEST['ttype']))
{
    if ($_REQUEST['do']=='getuser')
    {
        $url = 'http://117.74.137.62/';
    }
    $url .= $_REQUEST['ttype'].'/';
    $ttype = $_REQUEST['ttype'];
    unset($_REQUEST['ttype']);
    $_REQUEST['appid'] = 100003;
    $_REQUEST['version'] = '1.0.0';
    if(isset($_REQUEST['debug']))
    {
        $debug = 1;
        unset($_REQUEST['debug']);
    }
    $url = buildUrl($url, $_REQUEST);
    if(isset($debug))
    {
        echo '--url='.$url.'----------';
    } 
    
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $result = curl_exec($ch);
    curl_close($ch);

    if($ttype=='member' && $_REQUEST['booktype']=='online')
    {
        $json = json_decode($result,true);
        if($json['result']==1)
        {
            //echo 1;
            require_once '../mysql.php';
            mysql_conn();
            $uid = $_REQUEST['uid'];
            mysql_query("set character set 'utf8'");
            mysql_query('SET NAMES utf8');
            $sql = "insert into message(`content`,`type`,`member`,`time`) values('您已成功预约看房，我们为您准备的专车将会于6月20日14：00前准时抵达您的位置。',0,$uid,now())";
            mysql_query($sql);
        }
    }
    
    echo $result;
}
else 
{
    $result['result'] = -1;
    echo json_encode($result);
}

