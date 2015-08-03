<?php
if(!empty($_GET['userid']))
{
    $userid = $_GET['userid'];
    $data = array();
    require_once '../mysql.php';

    mysql_conn();

    $sql = "select * from message where member=".$userid;
    mysql_query("set character set 'utf8'");
    mysql_query('SET NAMES utf8');
    $query = mysql_query($sql);
    while ($rows=mysql_fetch_array($query))
    {
        $data[] = array('content'=>urlencode($rows['content']),'time'=>$rows['time']);
    }
    $result = array("result"=>1,"msg"=>$data);
}
else 
{
    $result = array("result"=>-1);
}
echo json_encode($result,JSON_UNESCAPED_UNICODE);