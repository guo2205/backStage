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
$sql = "select `integral` from integral where userid =".$userid;
$query = mysql_query($sql);
$rows = mysql_fetch_array($query);

if($rows)
{
    $result['state'] = 1;
    $result['integral'] = (int)$rows['integral'];
}
else 
{
    $result['state'] = 1;
    $result['integral'] = 0;
}

echo json_encode($result);
exit();