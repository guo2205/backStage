<?php
require '../mysql.php';
mysql_conn();
mysql_query("set character set 'utf8'");
mysql_query('SET NAMES utf8');
$data2 = array();
$type = array('房产','人物','教育','金融');
foreach ($type as $key => $value)
{
    $sql = "select * from news where type = '$value'";
    $query = mysql_query($sql);
    $data = array();
    while ($rows = mysql_fetch_array($query,MYSQL_ASSOC))
    {
        $data[] = array(
            "title"=>$rows['title'],
            "path"=>"http://117.74.137.59/HY/Public/newsPic/".$rows['picPath'],
            "content"=>$rows['details'],
            "text_title"=>$rows['details'],
            "text_time"=>$rows['comeFrom'],
            "text_content"=>$rows['details']
        );
    } 
    $data2[$key+1] = $data;
}

echo json_encode($data2,JSON_UNESCAPED_UNICODE);
