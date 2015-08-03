<?php
$result = array();
if(empty($_GET['userid']) && empty($_GET['collection']) && ($_GET['type']!='add' || $_GET['type']!='del'))
{
    $result['state'] = -1;
    $result['msg'] = 'need userid';
    echo json_encode($result);
    exit();
}
$userid = (int)$_GET['userid'];
$collection = (int)$_GET['collection'];

include '../mysql.php';
mysql_conn();
$sql = "select id from collection where userid=$userid and collection=$collection";
$query = mysql_query($sql);
if(mysql_num_rows($query))
{
    $flg = true;
}
else 
{
    $flg = false;
}
if($_GET['type']=='add')
{
    if($flg==false)
    {
        $sql = "insert into collection(`userid`,`collection`,`date`) value($userid,$collection,now())";
    }
}
elseif($_GET['type']=='del')
{
    if($flg==true)
    {
        $sql = "delete from collection where userid = $userid and collection = $collection";
    }
}
$query = mysql_query($sql);
$result['state'] = 1;
echo json_encode($result);

