<?php
//require '../Debug.php';
$result = array();
$data = array();
if(!empty($_GET['id'])&& !empty($_GET['system']))
{
    if($_GET['system']=='ios')
    {
        $system = 1;
    }
    elseif($_GET['system']=='android')
    {
        $system = 2;
    }
    else 
    {
        $result = array(
            'status'=>1,
            'message'=>'ERROR:Not found system'
        );
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }
    $id = $_GET['id'];
    require '../mysql.php';
    mysql_conn();
    mysql_query("set character set 'utf8'");
    mysql_query('SET NAMES utf8');
    $sql = "select * from loupan_main where id=$id";
    $query = mysql_query($sql);
    $rows = mysql_fetch_array($query,MYSQL_ASSOC);
    $data += $rows;
    
    $sql = "select * from loupan_huanjing where mainid=$id";
    $query = mysql_query($sql);
    $envpic = array();
    while ($rows = mysql_fetch_array($query,MYSQL_ASSOC))
    {
        $envpic[] = $rows['value'];
    }
    $data['envpic'] = $envpic;
    
    $sql = "select * from loupan_zhoubian where mainid=$id";
    $query = mysql_query($sql);
    $rows = mysql_fetch_array($query,MYSQL_ASSOC);
    $data['cofpic'] = $rows['value'];
    
    $sql = "select * from loupan_dating where mainid=$id";
    $query = mysql_query($sql);
    $rows = mysql_fetch_array($query,MYSQL_ASSOC);
    if($system==1)
    {
        $data['dt'] = array(
            'name'=>$rows['name'],
            'index'=>$rows['ios']
        );
    }
    elseif($system==2)
    {
        $data['dt'] = array(
            'name'=>$rows['name'],
            'index'=>$rows['android']
        );
    }
    
    $sql = "select * from loupan_huxing where mainid=$id";
    $query = mysql_query($sql);
    $hx = array();
    while ($rows = mysql_fetch_array($query,MYSQL_ASSOC))
    {
        if($system==1)
        {
            $hx[] = array(
                'name'=>$rows['name'],
                'size'=>$rows['size'],
                'pos'=>$rows['pos'],
                'pic'=>$rows['pic'],
                'rot'=>$rows['rot'],
                'index'=>$rows['ios']
            );
        }
        elseif($system==2)
        {
            $hx[] = array(
                'name'=>$rows['name'],
                'size'=>$rows['size'],
                'pos'=>$rows['pos'],
                'pic'=>$rows['pic'],
                'rot'=>$rows['rot'],
                'index'=>$rows['android']
            );
        }
    }
    $data['hx'] = $hx;
}
$result = array(
    'status'=>0,
    'data'=>$data
);
echo json_encode($result,JSON_UNESCAPED_UNICODE);