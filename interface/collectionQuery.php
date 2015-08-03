<?php
$result = array();
if(empty($_GET['userid']))
{
    $result['state'] = -1;
    $result['msg'] = 'need userid';
    echo json_encode($result);
    exit();
}
$userid = (int)$_GET['userid'];
include '../mysql.php';
mysql_conn();
$sql = "select `collection` from collection where userid =".$userid;
$query = mysql_query($sql);
$collection = array();
$rows = array();
while ($rows = mysql_fetch_array($query))
{
    $collection[] = (int)$rows['collection'];
}

$result['state'] = 1;
$result['collection'] = $collection;
echo json_encode($result);