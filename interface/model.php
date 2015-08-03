<?php
//error_reporting(E_ALL);
$result = array();
if(empty($_GET['userid']) || empty($_GET['model']))
{
    $result['state'] = -1;
    $result['msg'] = 'need userid||model';
    echo json_encode($result);
    exit();
}
$userid = (int)$_GET['userid'];
$model = $_GET['model'];
$sql = "select * from model where userid =".$userid;
$query = mysql_query($sql);
$rows = mysql_fetch_array($query);
if(!$rows)
{
    include '../mysql.php';
    mysql_conn();
    $sql = "insert into model(`userid`,`model`) values($userid,'$model')";
    mysql_query($sql);
}
$result['state'] = 1;
echo json_encode($result);
