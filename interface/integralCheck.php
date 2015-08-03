<?php
ini_set('date.timezone','Asia/Shanghai');
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
$sql = "select `integral`,`checkTime`,`continuous` from integral where userid =".$userid;
$query = mysql_query($sql);
$rows = mysql_fetch_array($query);

$data = array();
$data[1] = 10;
$data[2] = 15;
$data[3] = 20;
$data[4] = 25;
$data[5] = 30;
$data[6] = 35;
$data[7] = 40;

if($rows)
{
    $time = time();
    $date = date("Y-m-d");
    //echo $rows['checkTime'].'----';
    //echo $date;
    if($rows['checkTime'] == $date)
    {
        $result['state'] = -1;
        $result['msg'] = 'no check';
        echo json_encode($result);
        exit();
    }
    else 
    {
        if(date("Y-m-d",strtotime("+1 day",strtotime($rows['checkTime']))) == $date)
        {
            $continuous = (int)$rows['continuous'] + 1;
            if($continuous>=7)
            {
                $continuous = 7;
            }
        }
        else 
        {
            $continuous = 1;
        }
        $integral = $rows['integral'] += $data[$continuous];
        $sql = "update integral set integral = $integral , continuous = $continuous , checkTime = now() where userid =".$userid;
        mysql_query($sql);
        $result['state'] = 1;
        $result['integral'] = $integral;
        $result['add'] = $data[$continuous]; 
    }
}
else 
{
    $continuous = 1;
    $integral += $data[$continuous];
    $sql = "insert into integral(`userid`,`integral`,`checkTime`,`continuous`) values($userid,$integral,now(),$continuous)";
    mysql_query($sql);
    $result['state'] = 1;
    $result['integral'] = $integral;  //签到一次加五点
    $result['add'] = $data[$continuous];
}

echo json_encode($result);
exit();