<?php 
require '../mysql.php';
mysql_conn();
if(!empty($_GET['uid']) && !empty($_GET['ip']))
{
    $uid = $_GET['uid'];
    $ip = $_GET['ip'];
    $time = date('Y-m-d G:i:s');
    $sql = "insert into login(`uid`,`ip`,`time`) values($uid,'$ip','$time')";
    mysql_query($sql);
}
echo json_encode(array('status'=>0));