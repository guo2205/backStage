<?php
require '../mysql.php';
mysql_conn();
mysql_query("set character set 'utf8'");
mysql_query('SET NAMES utf8');
$sql = "select * from activity";
$query = mysql_query($sql);
$data = array();
$data['2'] = array();
while ($rows = mysql_fetch_array($query,MYSQL_ASSOC))
{
    $sql2 = "select * from loupan_main where id=".$rows['mainid'];
    $query2 = mysql_query($sql2);
    $rows2 = mysql_fetch_array($query2,MYSQL_ASSOC);
    $data['2'][] = array('20'.$rows['mainid']=>array(
        'title'=>$rows2['itemname'],
        'address'=>$rows2['SalesOfficeAddress'],
        'path'=>'http://117.74.137.59/HY/Public/work/android/envcof/'.$rows2['hudpic'],
        'content'=>$rows['content'],
        'text_content'=>$rows2['Price'],
        'price'=>$rows2['Price'],
        'active'=>$rows['content'],
        'housetype1'=>'',
        'housetype2'=>'',
        'housetype3'=>'',
        'id'=>$rows['mainid']
    ));
}

echo json_encode($data,JSON_UNESCAPED_UNICODE);