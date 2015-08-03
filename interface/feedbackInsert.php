<?php
$result = array();
if(empty($_GET['userid']) || empty($_GET['feedback']) || empty($_GET['iphone']))
{
    $result['state'] = -1;
    $result['msg'] = 'need userid';
    echo json_encode($result);
    exit();
}

$userid = (int)$_GET['userid'];
$feedback = $_GET['feedback'];
$feedback = urldecode($feedback);
$iphone = (int)$_GET['iphone'];
include '../mysql.php';
mysql_conn();
mysql_query("set character set 'utf8'");
mysql_query('SET NAMES utf8');
$sql = "insert into feedback(`userid`,`feedback`,`iphone`,`createtime`) values($userid,'".$feedback."',$iphone,now())";
$query = mysql_query($sql);

$result['state'] = 1;

echo json_encode($result);


