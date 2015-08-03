<?php
//error_log($_FILES,3,'1.txt');
$userid = $_REQUEST['userid'];
$ip = urldecode($_REQUEST['ip']);
if(!empty($_FILES['head']['name']))
{
   
    $explode = explode('.',$_FILES['head']['name']);
    $headName = randstring().'.'.$explode[1];
    move_uploaded_file($_FILES['head']['tmp_name'],'../Public/head/'.$headName);
    $headUrl = urlencode("117.74.137.59/HY/Public/head/".$headName);
    
    $url = "http://localhost/HY/interface/forward.php?ttype=member&do=perfectinfo&account=$userid&ipaddress=$ip&avatarurl=$headUrl";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RANGE, '0-500');
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);

    $resultArray = json_decode($result,true);
    if(!empty($resultArray))
    {
        if($resultArray['result']==1)
        {
            echo json_encode(array('state'=>1,'url'=>$headUrl));
        }
        else 
        {
            echo $result;
        }
    }
    else 
    {
        echo json_encode(array('state'=>-1,'msg'=>'目标服务器返回错误'));
    }
    
    /*
    echo json_encode(array('state'=>1,'url'=>'http://106.185.42.14/HY/Public/head/'.$headName));
    */
}
else 
{
    echo -1;
}


function randstring()
{
    $string = 'abcdefghijkmnopqrstuvwxyzABCDEFGHIJKMNOPQRSTUVWXYZ0123456789';
    $size = 26*2+10;
    $str = '';
    for ($i=0;$i<=32;$i++)
    {
        $str .= $string[rand(0,$size-1)];
    }
    return $str;
}