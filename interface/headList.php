<?php
$userid = $_GET['userid'];

include '../mysql.php';
mysql_conn();
$sql = "select * from head where id=".$userid;
$query = mysql_query($sql);
$rows = mysql_fetch_array($query);
if($rows)
{
    $url = "http://localhost/HY/Public/head/".$rows['fileName'];
}
else 
{
    $url = "";
}
echo json_encode(array("state"=>1,"url"=>$url));

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