<?php
$result = array();
$areaData = array(
    'YangPuDistrict'=>'杨浦区',
    'FengXianDistrict'=>'奉贤区',
    'JiaDingDistrict'=>'嘉定区',
    'SongJiangDistrict'=>'松江区',
    'ChangNingDistrict'=>'长宁区',
    'CongMingDistrict'=>'崇明县',
    'HongKouDistrict'=>'虹口区',
    'HuangPuDistrict'=>'黄浦区',
    'JingAnDistrict'=>'静安区',
    'JinShanDistrict'=>'金山区',
    'MinXingDistrict'=>'闵行区',
    'PuDongDistrict'=>'浦东新区',
    'PuTuoDistrict'=>'普陀区',
    'QingPuDistrict'=>'青浦区',
    'XuHuiDistrict'=>'徐汇区',
	'BaoShanDistrict'=>'宝山区',
    'ZhaBeiDistrict'=>'闸北区'
);
require '../mysql.php';
mysql_conn();
mysql_query("set character set 'utf8'");
mysql_query('SET NAMES utf8');
$data2 = array();
foreach ($areaData as $key => $value)
{
    $sql = "select * from loupan_main where area='$value'";
    $query = mysql_query($sql);
    $data = array();
    while ($rows = mysql_fetch_array($query))
    {
        $data[] = array(
            'id'=>$rows['id'],
            'loupantxt'=>$rows['itemname'],
            'hudpos'=>$rows['hudpos'],
            'hudpic'=>$rows['hudpic'],
            'area'=>$rows['area']
        );
    }
    $data2[$key] = $data;
}

echo json_encode($data2,JSON_UNESCAPED_UNICODE);