<?php
class WebAction extends Action {
    public function memberManage()
    {   
        if (!pfModel::isNode($_SESSION['group'],'member_select'))
        {
            $this->error('没有权限');
        }
        $this->areaData = array(
            '杨浦区','奉贤区','嘉定区','松江区','长宁区','崇明县','虹口区','黄浦区','静安区','金山区','闵行区','浦东新区','普陀区','青浦区','徐汇区','宝山区'
        );
        if(empty($_GET['pageno']))
            $pageno = 1;
        else 
            $pageno = $_GET['pageno'];
        $data2 = array();
        if (!empty($_POST['startDate']))
        {
            $data2['startDate'] = $_POST['startDate'];
            if (!empty($_POST['endDate']))
            {
                $data2['endDate'] = $_POST['endDate'];
                $s = $_POST['startDate'];
                $e = $_POST['endDate'];
                if (!empty($_POST['text']))
                {
                    $data2['text'] = $_POST['text'];
                    $account_mobile = $_POST['text'];
                    $url = "http://loogia.cc/3dApp.php?ttype=member&do=getuser&endtime=$e&pageno=$pageno&pagesize=10&source=3&starttime=$s&account_mobile=$account_mobile";
                }
                else
                    $url = "http://loogia.cc/3dApp.php?ttype=member&do=getuser&endtime=$e&pageno=$pageno&pagesize=10&source=3&starttime=$s";
            }
        }
        elseif (!empty($_POST['text']))
        {
            $data2['text'] = $_POST['text'];
            $account_mobile = $_POST['text'];
            $url = "http://loogia.cc/3dApp.php?ttype=member&do=getuser&pageno=$pageno&pagesize=10&source=3&account_mobile=$account_mobile";
        }
        else 
            $url = "http://loogia.cc/3dApp.php?ttype=member&do=getuser&pageno=$pageno&pagesize=10&source=3";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result,true);
        if($result['result']=='error' || $result['result']==0)
        {
            $this->error($url.'通信错误');
            exit();
        }
        $result = $result['data'];
        $count = $result['recordcount']; //总条数
        $data = array();
        foreach ($result['userinfo'] as $key => $value)
        {
            if($value['status']==1)
                $status = '启用';
            else
                $status = '禁用';
            $data[] = array(
                'uid'=>$value['uid'],
                'account_mobile'=>$value['account_mobile'],
                'registerDate'=>$value['register_time'],
                'status'=>$status
            );
        }
        $_SESSION['memberData'] = $result['userinfo'];
        
        $this->count = $count;
        $this->thisPageno = $pageno;
        $this->pageno = pfModel::pagenoArray($pageno,$count);
        $this->ksPageno = array(
            $pageno-9>0?$pageno-9:1,
            $pageno+9<ceil($count/10)?$pageno+9:ceil($count/10)
        );
        $this->data2 = $data2;
        $_SESSION['where']['member'] = $data2;
        $this->data = $data;
        $this->display();
    }
    public function memberList()
    {
        if (!pfModel::isNode($_SESSION['group'],'member_select'))
        {
            $this->error('没有权限');
        }
        
        if(!empty($_GET['account_mobile']))
        {
            $data = $_SESSION['memberData'];
            foreach ($data as $key => $value)
            {
                if($value['account_mobile']== $_GET['account_mobile'])
                {
                    $data = $data[$key];
                    break;
                }
            }
            $this->data = $data;
            
            $login = M('login');
            $l = $login->where(array('uid'=>$_GET['account_mobile']))->select();
            $this->data2 = $l;
    
            $this->display();
        }
        else 
            $this->error('错误');
    }
    public function memberEdit()
    {
        /*
        if(!pfModel::isNode($_SESSION['group'],'member_update'))
        {
            $this->error('没有权限');
        }
        */
        $collection = M('collection');
        $data = $collection->where(array('userid'=>$_GET['account_mobile']))->select();
        $this->userid = $_GET['account_mobile'];
        $loupan = M('loupan_main');
        foreach ($data as $key => $value)
        {
            $data[$key]['collection'] = substr($value['collection'],2);
            $loupanName = $loupan->where(array('id'=>$data[$key]['collection']))->find()['itemname'];
            if (!empty($loupanName))
                $data[$key]['collection'] = $loupanName;
            else 
                unset($data[$key]);
        }
        $this->data = $data;
        $this->display(); 
    }
    public function collectionDel()
    {
        /*
        if (!pfModel::isNode($_SESSION['gourp'],'member_delete'))
        {
            $this->error('没有权限');
        }
        */
        $collection = M('collection');
        $collection->where(array('id'=>$_GET['id']))->delete();
        $this->success('删除成功');
    }
    public function realEstateManagement()//查询楼盘页面
    {
        $this->cityData = array(
            '杨浦区','奉贤区','嘉定区','松江区','长宁区','崇明县','虹口区','黄浦区','静安区','金山区','闵行区','浦东新区','普陀区','青浦区','徐汇区','宝山区'
        );
        $loupan = M('loupan_main');
        $where = array();
        $flg = false;
        if(!empty($_POST['province']))
        {
            if (!empty($_POST['city']))
            {
                $city = $_POST['city'];
                $where['Area'] = $city;
                $flg = true;
            }
        }
        if (!empty($_POST['isDisplay']))
        {
            $isDisplay = $_POST['isDisplay'];
            if($isDisplay==2)
                $isDisplay = 0;
            $where['isDisplay'] = $isDisplay;
            $flg = true;
        }
        if(!empty($_POST['isRecommend']))   
        {
            $isRecommend = $_POST['isRecommend'];
            if($isRecommend == 2)
                $isRecommend = 0;
            $where['isRecommend'] = $isRecommend;
            $flg = true;
        }
        if(!empty($_POST['isNew']))   
        {
            $isNew = $_POST['isNew'];
            if($isNew == 2)
                $isNew = 0;
            $where['isNew'] = $isNew;
            $flg = true;
        }
        if (!empty($_POST['SaleState']))
        {
            $SaleState = $_POST['SaleState'];
            $where['SaleState'] = $SaleState;
            $flg = true;
        }
        if(!empty($_POST['text']))
        {
            $text = $_POST['text'];
            if($_POST['searchType']==1)
            {
                $where['id'] = $text;
                $flg = true;
            }
            elseif ($_POST['searchType']==2)
            {
                $like = '%'.$text.'%';
                $where['itemname'] = array('like',$like);
                $flg = true;
            }
        }
        /*
        if ($flg == false && !empty($_SESSION['where']['loupan']))
        {
            $where = $_SESSION['where']['loupan'];
        }
        */
        $data2 = $where;
        if (!empty($data2['itemname'][1]))
            $data2['itemname'] = str_replace(array("%"),"",$data2['itemname'][1]);
        if (!empty($data2['Area']))
            $data2['province'] = 1;
        if ($data2['isDisplay'] === 0)
            $data2['isDisplay'] = 2;
        if ($data2['isRecommend'] === 0)
            $data2['isRecommend'] = 2;
        if ($data2['isNew'] === 0)
            $data2['isNew'] = 2;
        $this->data2 = $data2;
        if (!empty($_GET['pageno']))
        {
            $pageno = $_GET['pageno'];
            $startRow = ($_GET['pageno']-1)*10;
        }
        else
            $startRow = 0;
        $count = $loupan->where($where)->count();
        if ($count<=10)
            $loupanQuery = $loupan->where($where)->order('id desc')->select();
        else
            $loupanQuery = $loupan->where($where)->order('id desc')->limit($startRow,10)->select();
        
        $this->count = $count;
        $this->thisPageno = $pageno;
        $this->pageno = pfModel::pagenoArray($pageno,$count);
        $this->ksPageno = array(
            $pageno-9>0?$pageno-9:1,
            $pageno+9<ceil($count/10)?$pageno+9:ceil($count/10)
        );
        $_SESSION['where']['loupan'] = $where;
        
        foreach($loupanQuery as $key => $value)
        {
            if($value['isNew'] == 1)
            {
                $loupanQuery[$key]['isNew'] = '是';
            }
            else
            {
                $loupanQuery[$key]['isNew'] = '否';
            }
            if($value['isRecommend'] == 1)
            {
                $loupanQuery[$key]['isRecommend'] = '是';
            }
            else 
            {
                $loupanQuery[$key]['isRecommend'] = '否';
            }
            if($value['isDisplay'] == 1)
            {
                $loupanQuery[$key]['isDisplay'] = '是';
            }
            else 
            {
                $loupanQuery[$key]['isDisplay'] = '否';
            }
            if($value['SaleState'] == 1)
            {
                $loupanQuery[$key]['SaleState'] = '在售';
            }
            elseif($value['SaleState'] == 2)
            {
                $loupanQuery[$key]['SaleState'] = '待售';
            }
            elseif($value['SaleState'] == 3)
            {
                $loupanQuery[$key]['SaleState'] = '尾售';
            }
            elseif($value['SaleState'] == 4)
            {
                $loupanQuery[$key]['SaleState'] = '售完';
            }
            if($value['status'] == 1)
            {
                $loupanQuery[$key]['status'] = '在售';
            }
            else 
            {
                $loupanQuery[$key]['status'] = '停售';
            }
            if($value['toExamine'] == 1)
            {
                $loupanQuery[$key]['toExamine'] = '已审核';
            }
            else 
            {
                $loupanQuery[$key]['toExamine'] = '未审核';
            }
        }
        $this->data = $loupanQuery;
        $this->display();

    }
    public function realEstateAdd()//添加楼盘页面
    {
        $this->display();
    }
    public function realEstateSave()
    {        
        $huxing_name = $_POST['huxing_name'];
        unset($_POST['huxing_name']);
        $size = $_POST['size'];
        unset($_POST['size']);
        $dating_name = $_POST['dating_name'];
        unset($_POST['dating_name']);
        $hx_pic = $_POST['hx_pic'];
        unset($_POST['hx_pic']);
        $rot = $_POST['rot'];
        unset($_POST['rot']);
        $pos = $_POST['pos'];
        unset($_POST['pos']);
        
        //基本信息
        $main = M('loupan_main');
        if(!empty($_FILES['hudpic']['name']))
        {
            //首页照片
            $hudpicFilePath = 'Public/work/android/envcof/';
            $explode = explode('.',$_FILES['hudpic']['name']);
            $picName = pfModel::randstring().'.'.$explode[count($explode)-1];
            move_uploaded_file($_FILES['hudpic']['tmp_name'],$hudpicFilePath.$picName);
            $_POST['hudpic'] = $picName;   
        }
        
        $main->add($_POST);
        $mainID = $main->getLastInsID();
        
        //环境
        $huanjingFilePath = 'Public/work/android/envcof/';
        $huanjing = M('loupan_huanjing');
        
        foreach ($_FILES['huanjing']['name'] as $key => $value)
        {
            if(!empty($value))
            {
                $explode = explode('.',$value);
                $picName = pfModel::randstring().'.'.$explode[count($explode)-1];
                move_uploaded_file($_FILES['huanjing']['tmp_name'][$key],$huanjingFilePath.$picName);
                $huanjing->add(array('mainid'=>$mainID,'value'=>$picName));
            }
            else 
            {
                $this->error('错误');
                exit();
            }
        }
        
        //周边
        $zhoubianFilePath = 'Public/work/android/envcof/';
        $zhoubian = M('loupan_zhoubian');
        if(!empty($_FILES['zhoubian']['name']))
        {
            $explode = explode('.',$_FILES['zhoubian']['name']);
            $picName = pfModel::randstring().'.'.$explode[count($explode)-1];
            move_uploaded_file($_FILES['zhoubian']['tmp_name'],$zhoubianFilePath.$picName);   
            $zhoubian->add(array('mainid'=>$mainID,'value'=>$picName));
        }
        
        //户型
        if(!empty($huxing_name))
        {
            $ioshuxingdatingPath = 'Public/work/ios/huxingdating/';
            $androidhuxingdatingPath = 'Public/work/android/huxingdating/';
            $huxing = M('loupan_huxing');
            $flg = false;
            foreach ($_FILES['huxing_ios']['name'] as $key => $value)
            {
                if(!empty($value))
                {
                    if($value=='index.txt')
                    {
                        $flg = true;
                        $indexName = pfModel::randstring().'.txt';
                        move_uploaded_file($_FILES['huxing_ios']['tmp_name'][$key],$ioshuxingdatingPath.$indexName);
                        $iosFileName = $indexName;
                    }
                    else 
                    {
                        move_uploaded_file($_FILES['huxing_ios']['tmp_name'][$key],$ioshuxingdatingPath.$_FILES['huxing_ios']['name'][$key]);
                    } 
                }
                else
                {
                    $this->error('错误');
                    exit();
                }
            }
            
            
            
            $flg = false;
            foreach ($_FILES['huxing_android']['name'] as $key => $value)
            {
                if(!empty($value))
                {
                    if($value=='index.txt')
                    {
                        $flg = true;
                        $indexName = pfModel::randstring().'.txt';
                        move_uploaded_file($_FILES['huxing_android']['tmp_name'][$key],$androidhuxingdatingPath.$indexName);
                        $androidFileName = $indexName;
                    }
                    else
                    {
                        move_uploaded_file($_FILES['huxing_android']['tmp_name'][$key],$androidhuxingdatingPath.$_FILES['huxing_android']['name'][$key]);
                    }
                }
                else
                {
                    $this->error('错误');
                    exit();
                }
            }
            if(!flg)
            {
                $this->error('错误，缺少index.txt');
            }
            $huxingData = array(
                'name'=>$huxing_name,
                'mainid'=>$mainID,
                'size'=>$size,
                'rot'=>$rot,
                'pic'=>$hx_pic,
                'pos'=>$pos,
                'ios'=>$iosFileName,
                'android'=>$androidFileName
            );
            $huxing->add($huxingData);
            
            
            //大厅
            
            $flg = false;
            foreach ($_FILES['dating_ios']['name'] as $key => $value)
            {
                if(!empty($value))
                {
                    if($value=='index.txt')
                    {
                        $flg = true;
                        $indexName = pfModel::randstring().'.txt';
                        move_uploaded_file($_FILES['dating_ios']['tmp_name'][$key],$ioshuxingdatingPath.$indexName);
                        $iosFileName = $indexName;
                    }
                    else
                    {
                        move_uploaded_file($_FILES['dating_ios']['tmp_name'][$key],$ioshuxingdatingPath.$_FILES['dating_ios']['name'][$key]);
                    }
                }
                else
                {
                    $this->error('错误');
                    exit();
                }
            }
            
            
            $flg = false;
            foreach ($_FILES['dating_android']['name'] as $key => $value)
            {
                if(!empty($value))
                {
                    if($value=='index.txt')
                    {
                        $flg = true;
                        $indexName = pfModel::randstring().'.txt';
                        move_uploaded_file($_FILES['dating_android']['tmp_name'][$key],$androidhuxingdatingPath.$indexName);
                        $androidFileName = $indexName;
                    }
                    else
                    {
                        move_uploaded_file($_FILES['dating_android']['tmp_name'][$key],$androidhuxingdatingPath.$_FILES['dating_android']['name'][$key]);
                    }
            
            
                }
                else
                {
                    $this->error('错误');
                    exit();
                }
            }
            $dating = M('loupan_dating');
            $datingData = array(
                'mainid'=>$mainID,
                'name'=>$dating_name,
                'ios'=>$iosFileName,
                'android'=>$androidFileName
            );
            $dating->add($datingData);
        }
        $this->success("操作成功！",__URL__.'/realEstateManagement');
    }
    public function loupanDisplay()
    {
        $type = $_GET['type'];
        $explode = explode(',', $_GET['id']);
        $where = array();
        for ($i=0;$i<count($explode);$i++)
        {
            if(!empty($explode[$i]))
            $where['id'][] = array('eq',$explode[$i]);
        }
        $where['id'][] = 'or';
        $loupan = M('loupan_main');
        if($type==1)
        {
            $loupan->where($where)->save(array('isDisplay'=>1));
        }
        elseif($type==2)
        {
            $loupan->where($where)->save(array('isDisplay'=>0));
        }
        $this->success('修改成功');
    }
    public function loupanToExamine()
    {
        $type = $_GET['type'];
        $explode = explode(',', $_GET['id']);
        $where = array();
        for ($i=0;$i<count($explode);$i++)
        {
            if(!empty($explode[$i]))
            $where['id'][] = array('eq',$explode[$i]);
        }
        $where['id'][] = 'or';
        $loupan = M('loupan_main');
        if($type==1)
        {
            $loupan->where($where)->save(array('toExamine'=>1));
        }
        elseif($type==2)
        {
            $loupan->where($where)->save(array('toExamine'=>0));
        }
        $this->success('修改成功');
    }
    public function loupanList()
    {
        if(!empty($_GET['id']))
        {
            $loupanMain = M('loupan_main');
            $loupan = $loupanMain->where(array('id'=>$_GET['id']))->find();
            if ($loupan['SaleState']==1)
                $loupan['SaleState']='在售';
            elseif ($loupan['SaleState']==2)
                $loupan['SaleState']='待售';
            elseif ($loupan['SaleState']==3)
                $loupan['SaleState']='尾售';
            elseif ($loupan['SaleState']==4)
                $loupan['SaleState']='售完';
            
            $loupanHuxing = M('loupan_huxing');
            $huxingData = $loupanHuxing->where(array('mainid'=>$_GET['id']))->select();
            $array = array();
            foreach ($huxingData as $key => $value)
            {
                $array[] = array(
                    'name'=>$value['name'],
                    'size'=>$value['size'],
                    'pic'=>$value['pic'],
                    'pos'=>$value['pos'],
                    'rot'=>$value['rot']
                );
            }
            $this->huxingList = $array;
            
            $loupanZhoubian = M('loupan_zhoubian');
            $zhoubianData = $loupanZhoubian->where(array('mainid'=>$_GET['id']))->find();
            $loupan['zhoubian'] = $zhoubianData['value'];
            
            $array = array();
            $loupanHuanjing = M('loupan_huanjing');
            $huanjingData = $loupanHuanjing->where(array('mainid'=>$_GET['id']))->select();
            foreach ($huanjingData as $key => $value)
            {
                $array[] = $value['value'];
            }
            $loupan['huanjing'] = $array;
            
            $loupanDating = M('loupan_dating');
            $datingData = $loupanDating->where(array('mainid'=>$_GET['id']))->find();
            $loupan['dating_name'] = $datingData['name'];
            $this->data = $loupan;
            $this->display();
        }
        else
        {
            $this->error('错误');
        }
    }
    public function loupanEdit()
    {
        if (!empty($_GET['id']));
        $_SESSION['loupanID'] = $_GET['id'];
        redirect('index.php?s=Web/loupanMainEdit');
    }
    public function loupanMainEdit()
    {
        if (!empty($_SESSION['loupanID']))
        {
            $loupanID = $_SESSION['loupanID'];
            $loupanMain = M('loupan_main');
            $this->data = $loupanMain->where(array('id'=>$loupanID))->find();
            $this->display();
        }
    }
    public function loupanMainSave()
    {
        if (!empty($_POST['id']))
        {
            $loupanMain = M('loupan_main');
            //首页照片
            if (!empty($_FILES['hudpic']['name']))
            {
                $hudpicFilePath = 'Public/work/android/envcof/';
                $explode = explode('.',$_FILES['hudpic']['name']);
                $picName = pfModel::randstring().'.'.$explode[count($explode)-1];
                move_uploaded_file($_FILES['hudpic']['tmp_name'],$hudpicFilePath.$picName);
                $_POST['hudpic'] = $picName;
            }
            $loupanMain->where(array('id'=>$_POST['id']))->save($_POST);
            $this->success('保存成功');
        }
    }  
    public function loupanHuanjingEdit()
    {
        if (!empty($_SESSION['loupanID']))
        {
            $huanjing = M('loupan_huanjing');
            $this->data = $huanjing->where(array('mainid'=>$_SESSION['loupanID']))->select();
            $this->display();
        }
        
    }
    public function loupanHuanjingSave()
    {
        $huanjingFilePath = 'Public/work/android/envcof/';
        $huanjing = M('loupan_huanjing');
        //删除之前的照片
        $huanjing->where(array('mianid'=>$_SESSION['loupanID']))->delete();
        
        foreach ($_FILES['huanjing']['name'] as $key => $value)
        {
            if(!empty($value))
            {
                $explode = explode('.',$value);
                $picName = pfModel::randstring().'.'.$explode[count($explode)-1];
                move_uploaded_file($_FILES['huanjing']['tmp_name'][$key],$huanjingFilePath.$picName);
                $huanjing->add(array('mainid'=>$_SESSION['loupanID'],'value'=>$picName));
            }
            else
            {
                $this->error('错误');
                exit();
            }
        }
    }
    public function loupanZhoubianEdit()
    {
        if (!empty($_SESSION['loupanID']))
        {
            $zhoubian = M('loupan_zhoubian');
            $this->data = $zhoubian->where(array('mainid'=>$_SESSION['loupanID']))->find();
            $this->display();
        }
    }
    public function loupanZhoubianSave()
    {
        if(!empty($_FILES['zhoubian']['name']))
        {
            
            $zhoubianFilePath = 'Public/work/android/envcof/';
            $zhoubian = M('loupan_zhoubian');
            $zhoubian->where(array('mainid'=>$_SESSION['loupanID']))->delete();
            $explode = explode('.',$_FILES['zhoubian']['name']);
            $picName = pfModel::randstring().'.'.$explode[count($explode)-1];
            move_uploaded_file($_FILES['zhoubian']['tmp_name'],$zhoubianFilePath.$picName);
            $zhoubian->add(array('mainid'=>$_SESSION['loupanID'],'value'=>$picName));
            $this->success('修改成功');
        }
    }
    public function loupanHuxingEdit()
    {
        if (!empty($_SESSION['loupanID']))
        {
            $huxing = M('loupan_huxing');
            $this->data = $huxing->where(array('mainid'=>$_SESSION['loupanID']))->select();
            $this->display();
        }
    }
    public function addHuxing()
    {
        $this->display();
    }
    public function addHuxingSave()
    {
        $huxing_name = $_POST['huxing_name'];
        unset($_POST['huxing_name']);
        $size = $_POST['size'];
        unset($_POST['size']);
        $hx_pic = $_POST['hx_pic'];
        unset($_POST['hx_pic']);
        $rot = $_POST['rot'];
        unset($_POST['rot']);
        $pos = $_POST['pos'];
        unset($_POST['pos']);
        
        if(!empty($huxing_name))
        {
            $ioshuxingdatingPath = 'Public/work/ios/huxingdating/';
            $androidhuxingdatingPath = 'Public/work/android/huxingdating/';
            $huxing = M('loupan_huxing');
            $flg = false;
            foreach ($_FILES['huxing_ios']['name'] as $key => $value)
            {
                if(!empty($value))
                {
                    if($value=='index.txt')
                    {
                        $flg = true;
                        $indexName = pfModel::randstring().'.txt';
                        move_uploaded_file($_FILES['huxing_ios']['tmp_name'][$key],$ioshuxingdatingPath.$indexName);
                        $iosFileName = $indexName;
                    }
                    else
                    {
                        move_uploaded_file($_FILES['huxing_ios']['tmp_name'][$key],$ioshuxingdatingPath.$_FILES['huxing_ios']['name'][$key]);
                    }
                }
                else
                {
                    $this->error('错误');
                    exit();
                }
            }
            
            $flg = false;
            foreach ($_FILES['huxing_android']['name'] as $key => $value)
            {
                if(!empty($value))
                {
                    if($value=='index.txt')
                    {
                        $flg = true;
                        $indexName = pfModel::randstring().'.txt';
                        move_uploaded_file($_FILES['huxing_android']['tmp_name'][$key],$androidhuxingdatingPath.$indexName);
                        $androidFileName = $indexName;
                    }
                    else
                    {
                        move_uploaded_file($_FILES['huxing_android']['tmp_name'][$key],$androidhuxingdatingPath.$_FILES['huxing_android']['name'][$key]);
                    }
                }
                else
                {
                    $this->error('错误');
                    exit();
                }
            }
            if(!flg)
            {
                $this->error('错误，缺少index.txt');
            }
            $huxingData = array(
                'name'=>$huxing_name,
                'mainid'=>$_SESSION['loupanID'],
                'size'=>$size,
                'rot'=>$rot,
                'pic'=>$hx_pic,
                'pos'=>$pos,
                'ios'=>$iosFileName,
                'android'=>$androidFileName
            );
            $huxing->add($huxingData);
            $this->success('添加成功');
        }
    }
    public function huxingDel()
    {
        if (!empty($_GET['id']))
        {
            $huxing = M('loupan_huxing');
            $huxing->where(array('id'=>$_GET['id']))->delete();
            $this->success('删除成功');
        }
    }
    public function huxingEdit()
    {
        if (!empty($_GET['id']))
        {
            $huxing = M('loupan_huxing');
            $this->data = $huxing->where(array('id'>$_GET['id']))->find();
            $this->display();
        }
    }
    public function huxingSave()
    {
        if (!empty($_POST['id']))
        {
            $huxing = M('loupan_huxing');
            $huxing->where(array('id'=>$_POST['id']))->save($_POST);
            $this->success('修改成功',__APP__.'/Web/loupanHuxingEdit');
        }
    }
    public function loupanDatingEdit()
    {
        if (!empty($_SESSION['loupanID']))
        {
            $dating = M('loupan_dating');
            $this->data = $dating->where(array('mainid'=>$_SESSION['loupanID']))->find();
            $this->display();
        }
    }
    public function loupanDatingSave()
    {
        $ioshuxingdatingPath = 'Public/work/ios/huxingdating/';
        $androidhuxingdatingPath = 'Public/work/android/huxingdating/';
        
        $dating_name = $_POST['dating_name'];
        $dating = M('loupan_dating');
        $datingData = array(
            'name'=>$dating_name
        );
        if(!empty($_FILES['dating_ios']['name']))
        {
            $flg = false;
            foreach ($_FILES['dating_ios']['name'] as $key => $value)
            {
                if(!empty($value))
                {
                    if($value=='index.txt')
                    {
                        $flg = true;
                        $indexName = pfModel::randstring().'.txt';
                        move_uploaded_file($_FILES['dating_ios']['tmp_name'][$key],$ioshuxingdatingPath.$indexName);
                        $datingData['ios'] = $indexName;
                    }
                    else
                    {
                        move_uploaded_file($_FILES['dating_ios']['tmp_name'][$key],$ioshuxingdatingPath.$_FILES['dating_ios']['name'][$key]);
                    }
                }
                else
                {
                    $this->error('错误');
                    exit();
                }
            }
        }
        
        if(!empty($_FILES['dating_android']['name']))
        {
            $flg = false;
            foreach ($_FILES['dating_android']['name'] as $key => $value)
            {
                if(!empty($value))
                {
                    if($value=='index.txt')
                    {
                        $flg = true;
                        $indexName = pfModel::randstring().'.txt';
                        move_uploaded_file($_FILES['dating_android']['tmp_name'][$key],$androidhuxingdatingPath.$indexName);
                        $datingData['android'] = $indexName;
                    }
                    else
                    {
                        move_uploaded_file($_FILES['dating_android']['tmp_name'][$key],$androidhuxingdatingPath.$_FILES['dating_android']['name'][$key]);
                    }
            
            
                }
                else
                {
                    $this->error('错误');
                    exit();
                }
            }
        }
        $dating->where(array('mainid'=>$_SESSION))->save($datingData);
        $this->success('修改成功');
    }
    public function loupanDel()
    {
        if(!empty($_GET['id']))
        {
            M('loupan_main')->where(array('id'=>$_GET['id']))->delete();
            M('loupan_huxing')->where(array('mainid'=>$_GET['id']))->delete();
            M('loupan_zhoubian')->where(array('mainid'=>$_GET['id']))->delete();
            M('loupan_dating')->where(array('mainid'=>$_GET['id']))->delete();
            M('loupan_huanjing')->where(array('mainid'=>$_GET['id']))->delete();
            $this->success('删除成功!',__URL__.'/realEstateManagement');
        }
        else 
        {
            $this->error('错误');
        }
    }
    public function message()
    {
        $message = M("message");
        $where = array();
        $flg = false;
        if (!empty($_POST['time']))
        {
            $time = $_POST['time'];
            $where['time'] = $time;
            $flg = true;
        }
        if (!empty($_POST['text']))
        {
            $where['id'] = $_POST['text'];
            $flg = true;
        }
        /*
        if ($flg == false && !empty($_SESSION['where']['message']))
        {
            $where = $_SESSION['where']['message'];
        }
        */
        $this->data2 = $where;
        if (!empty($_GET['pageno']))
        {
            $pageno = $_GET['pageno'];
            $startRow = ($_GET['pageno']-1)*10;
        }
        else 
        {
            $startRow = 0;
        }
        $count = $message->where($where)->count();
        if ($count<=10)
        {
            $mSQL = $message->where($where)->order('id desc')->select();
        }
        else 
        {
            $mSQL = $message->where($where)->order('id desc')->limit($startRow,10)->select();
        }
        
        
        $this->count = $count;
        $this->thisPageno = $pageno;
        $this->pageno = pfModel::pagenoArray($pageno,$count);
        $this->ksPageno = array(
            $pageno-9>0?$pageno-9:1,
            $pageno+9<ceil($count/10)?$pageno+9:ceil($count/10)
        );
        $_SESSION['where']['message'] = $where;
        
        foreach($mSQL as $key => $value)
        {
            if($value['type']==0)
            {
                $mSQL[$key]['type'] = '系统';
            }
            elseif($value['type']==1)
            {
                $mSQL[$key]['type'] = '推送';
            }
            if($value['sh']==0)
            {
                $mSQL[$key]['sh'] = '未审核';
            }
            elseif ($value['sh']==1)
            {
                $mSQL[$key]['sh'] = '已审核';
            }
            if($value['member'] == 0)
            {
                $mSQL[$key]['member'] = '所有人';
            }
        }
        
        $this->data = $mSQL;
        $this->display();
    }
    public function messageToExamine()
    {
        $type = $_GET['type'];
        $explode = explode(',', $_GET['id']);
        $where = array();
        for ($i=0;$i<count($explode);$i++)
        {
            if(!empty($explode[$i]))
            $where['id'][] = array('eq',$explode[$i]);
        }
        $where['id'][] = 'or';
        $message = M('message');
        if($type==1)
        {
            $message->where($where)->save(array('sh'=>1));
        }
        elseif($type==2)
        {
            $message->where($where)->save(array('sh'=>0));
        }
        $this->success('修改成功');
    }
    public function addMesage()
    {
        $this->display(); 
    }
    public function addMessageFunction()
    {
        if(!empty($_POST['member']) || $_POST['member']==0)
        {
            $_POST['time'] = date("Y-m-d");
            $message = M("message");
            $mSQL = $message->add($_POST);
            $this->success('保存成功!',__URL__.'/message');
        }
        else 
        {
            $this->error('请输入发送人');   
        }
    }
    public function messageList()
    {
        $message = M("message");
        $mSQL = $message->where(array('id'=>$_GET['id']))->find();
        if($mSQL['type']==0)
        {
            $mSQL['type'] = '系统消息';
        }
        elseif($mSQL['type']==1)
        {
            $mSQL['type'] = '推送消息';
        }
        if($mSQL['sh']==0)
        {
            $mSQL['sh'] = "未审核";
        }
        else 
        {
            $mSQL['sh'] = "已审核";
        }
        $this->data = $mSQL;
        $this->display();
    }
    public function messageEdit()
    {
        $message = M('message');
        $mSQL = $message->where(array('id'=>$_GET['id']))->find();
        $this->data = $mSQL;
        $this->display();
    }
    public function messageEditSave()
    {
        if(!empty($_POST['id']))
        {
            $message = M('message');
            $message->where(array('id'=>$_POST['id']))->save($_POST);
            $this->success('保存成功',__APP__.'/Web/message');
        }
        else 
        {
            $this->error('错误');
        }
    }
    public function messageDel()
    {
        if(!empty($_GET['id']))
        {
            $message = M('message');
            $message->where(array('id'=>$_GET['id']))->delete();
            $this->success('删除成功');
        }
    }
    public function news()
    {
        $news = M("news");
        $where = array();
        $flg = false;
        
        if(!empty($_POST['text']))
        {
            if($_POST['searchType']==1)
            {
                $where['id'] = $_POST['text'];
                $flg = true;
            }
            elseif ($_POST['searchType']==2)
            {
                $like = '%'.$_POST['text'].'%';
                $where['type'] = array('like',$like);
                $flg = true;
            }
        }
        /*
        if ($flg == false && !empty($_SESSION['where']['news']))
        {
            $where = $_SESSION['where']['news'];
        }
        */
        $data2 = $where;
        if (!empty($data2['type'][1]))
        {
            $data2['type'] = str_replace(array("%"),"",$data2['type'][1]);
        }
        $this->data2 = $data2;
        
        if (!empty($_GET['pageno']))
        {
            $pageno = $_GET['pageno'];
            $startRow = ($_GET['pageno']-1)*10;
        }
        else
        {
            $startRow = 0;
        }
        $count = $news->where($where)->count();
        if ($count<=10)
            $nSQL = $news->where($where)->order('id desc')->select();
        else
            $nSQL = $news->where($where)->order('id desc')->limit($startRow,10)->select();
        
        $this->count = $count;
        $this->thisPageno = $pageno;
        $this->pageno = pfModel::pagenoArray($pageno,$count);
        $this->ksPageno = array(
            $pageno-9>0?$pageno-9:1,
            $pageno+9<ceil($count/10)?$pageno+9:ceil($count/10)
        );
        $_SESSION['where']['news'] = $where;
        foreach ($nSQL as $key => $value)
        {
            if($value['display']==1)
            {
                $nSQL[$key]['display'] = '显示';
            }
            else 
            {
                $nSQL[$key]['display'] = '隐藏';
            }
        }
        $this->data = $nSQL;
        $this->display();
    }
    public function newsEdit()
    {
        if(!empty($_GET['id']))
        {
            $news = M('news');
            $nSQL = $news->where(array('id'=>$_GET['id']))->find();
            $this->data = $nSQL;
            
            $newType = M("newtype");
            $tSQL = $newType->select();
            $this->type = $tSQL;
            
            $this->display();
        }
    }
    public function newsEditSave()
    {
        if(!empty($_POST['title']) && !empty($_POST['id']))
        {
            if(!empty($_FILES['picPath']['name']))
            {
                $explode = explode('.',$_FILES['picPath']['name']);
                $picName = pfModel::randstring().'.'.$explode[1];
                move_uploaded_file($_FILES['picPath']['tmp_name'],'Public/newsPic/'.$picName);
                $_POST['picPath'] = $picName;
            }
            $news = M("news");
            $nSQL = $news->where(array('id'=>$_POST['id']))->save($_POST);
            $this->success('操作成功!',__URL__.'/news');
        }
        else
        {
            $this->error('请输入标题');
        }
    }
    public function newsList()
    {
        $news = M("news");
        $nSQL = $news->where(array('id'=>$_GET['id']))->find();
        if($nSQL['display']=='1')
        {
            $nSQL['display'] = '显示';
        }
        else 
        {
            $nSQL['display'] = '隐藏';
        }
        $this->data = $nSQL;
        $this->display();
    }
    public function newsDel()
    {
        if(!empty($_GET['id']))
        {
            M('news')->where(array('id'=>$_GET['id']))->delete();
            $this->success('删除成功 ');
        }
        else 
        {
            $this->error('错误');
        }
    }
    public function newsType()
    {
        $nT = M('newtype');
        $nTSQL = $nT->select();
        $this->data = $nTSQL;
        $this->display();
    }
    public function newTypeEdit()
    {
        $nT = M('newtype');
        $this->data = $nT->where(array('id'=>$_GET['id']))->find();
        $this->display();
    }
    public function newsTypeEditSave()
    {
        $nT = M('newtype');
        $type = $nT->where(array('id'=>$_POST['id']))->find()['value'];
        $nT->where(array('id'=>$_POST['id']))->save($_POST);
        $news = M('news');
        $news->where(array('type'=>$type))->save(array('type'=>$_POST['value']));
        $this->success('修改成功');
    }
    public function newsTypeSave()
    {
        $nT = M('newtype');
        $nTSQL = $nT->add($_POST);
        $this->success('保存成功');
    }
    public function addnews()
    {
        $newType = M("newtype");
        $tSQL = $newType->select();
        $this->type = $tSQL;
        $this->display();
    }
    public function newTypeDel()
    {
        $nT = M('newtype');
        $nTSQL = $nT->where(array('id'=>$_GET['id']))->delete();
        $this->success('删除成功');
        
    }
    public function addnewsFunction()
    {
        if(!empty($_POST['title']))
        {        
            $explode = explode('.',$_FILES['picPath']['name']);
            $picName = pfModel::randstring().'.'.$explode[1];
            move_uploaded_file($_FILES['picPath']['tmp_name'],'Public/newsPic/'.$picName);
            
            
            $news = M("news");
            $_POST['picPath'] = $picName;
            $nSQL = $news->add($_POST);
            $this->success('操作成功!',__URL__.'/news');
        }
        else 
        {
            $this->error('请输入标题');
        }
    }
    public function activity()
    {
        $this->areaData = array(
            '杨浦区','奉贤区','嘉定区','松江区','长宁区','崇明县','虹口区','黄浦区','静安区','金山区','闵行区','浦东新区','普陀区','青浦区','徐汇区','宝山区'
        );
        $a = M('activity');
        $where = array();
        $flg = false;
        if(!empty($_POST['city']))
        {
            if(!empty($_POST['area']))
            {
                $area = $_POST['area'];
                $where['area'] = $area;
                $flg = true;
            }
        }
        if(!empty($_POST['status']))
        {
            $status = $_POST['status'];
            if ($status == 2)
            {
                $status = 0;
            }
            $where['status'] =  $status;
            $flg = true;
        }
        if(!empty($_POST['display']))
        {
            $display = $_POST['display'];
            if($display == 2)
            {
                $display = 0;
            }
            $where['display'] = $display;
            $flg = true;
        }
        if(!empty($_POST['text']))
        { 
            $text = $_POST['text'];
            if ($_POST['searchType']==1)
            {
                $where['id']= $text;
                $flg = true;
            }
            elseif($_POST['searchType']==2)
            {
                $like = '%'.$text.'%';
                $where['loupan'] = array('like',$like);
                $flg = true;
            }
        }
        /*
        if ($flg == false && !empty($_SESSION['where']['activity']))
        {
            $where = $_SESSION['where']['activity'];
        }
        */
        $data2 = $where;
        
        if (!empty($data2['loupan'][1]))
        {
            $data2['loupan'] = str_replace(array("%"),"",$data2['loupan'][1]);
        }
        if (!empty($data2['area']))
        {
            $data2['city'] = 1;
        }
        if ($data2['status'] === 0)
        {
            $data2['status'] = 2;
        }
        if ($data2['display'] === 0)
        {
            $data2['display'] = 2;
        }
        
        $this->data2 = $data2;
        
        if (!empty($_GET['pageno']))
        {
            $pageno = $_GET['pageno'];
            $startRow = ($_GET['pageno']-1)*10;
        }
        else
        {
            $startRow = 0;
        }
        $count = $a->where($where)->count();
        if ($count<=10)
            $aSQL = $a->where($where)->order('id desc')->select();
        else
            $aSQL = $a->where($where)->order('id desc')->limit($startRow,10)->select();
        
        $this->count = $count;
        $this->thisPageno = $pageno;
        $this->pageno = pfModel::pagenoArray($pageno,$count);
        $this->ksPageno = array(
            $pageno-9>0?$pageno-9:1,
            $pageno+9<ceil($count/10)?$pageno+9:ceil($count/10)
        );
        $_SESSION['where']['activity'] = $where;
        
        foreach ($aSQL as $key => $value)
        {
            if($value['display']==1)
            {
                $aSQL[$key]['display'] = '显示';
            }
            else 
            {
                $aSQL[$key]['display'] = '隐藏';
            }
            if($value['status']==1)
            {
                $aSQL[$key]['status'] = '正在进行';
            }
            else 
            {
                $aSQL[$key]['status'] = '结束';
            }
            if ($value['toExamine']==1)
            {
                $aSQL[$key]['toExamine'] = '已审核';
            }
            else 
            {
                $aSQL[$key]['toExamine'] = '未审核';
            }
        }
        $this->data = $aSQL;
        $this->display();
    }
    public function activityList()
    {
        if(!empty($_GET['id']))
        {
            $a = M('activity');
            $aSQL = $a->where(array('id'=>$_GET['id']))->find();
            if($aSQL['status']==1)
            {
                $aSQL['status'] = '正在进行';
            }
            else 
            {
                $aSQL['status'] = '结束';
            }
            $this->data = $aSQL;
            $this->display();
        }
        else
        {
            $this->error('错误');
        }
    }
    public function activityEdit()
    {
        if(!empty($_GET['id']))
        {
            $activity = M('activity');
            $data = $activity->where(array('id'=>$_GET['id']))->find();
            $this->data = $data;
            $loupanMain = M('loupan_main');
            $loupanData = $loupanMain->select();
            $this->loupanV = $loupanData;
            
            $this->display();
        }
        else
        {
            $this->error('错误');
        }
    }
    public function activityEditSave()
    {
        if(!empty($_POST['id']))
        {
            if(!empty($_FILES['imgurl']['name']))
            {
                $explode = explode('.',$_FILES['imgurl']['name']);
                $picName = pfModel::randstring().'.'.$explode[count($explode)-1];
                move_uploaded_file($_FILES['imgurl']['tmp_name'],'Public/activityPic/'.$picName);
                $_POST['imgurl'] = 'http://117.74.137.59/HY/Public/activityPic/'.$picName;
            }
            else
            {
                unset($_POST['imgurl']);
            }
            
            $a = M('activity');
            $a->where(array('id'=>$_POST['id']))->save($_POST);
            $this->success('修改成功',__APP__.'/Web/activity');
        }
        else 
        {
            $this->error('错误');
        }
    }
    public function addActivity()
    {
        //SQL查询
        $loupanMain = M('loupan_main');
        $loupanData = $loupanMain->select();   
        $this->loupanV = $loupanData;
        $this->display();
    }
    public function addActivitySave()
    {
        if(!empty($_POST['title']))
        {
            if(!empty($_FILES['imgurl']['name']))
            {
                $explode = explode('.',$_FILES['imgurl']['name']);
                $picName = pfModel::randstring().'.'.$explode[count($explode)-1];
                move_uploaded_file($_FILES['imgurl']['tmp_name'],'Public/activityPic/'.$picName);
                $_POST['imgurl'] = 'http://117.74.137.59/HY/Public/activityPic/'.$picName;
            }
            
            $loupanData = M('loupan_main')->where(array('id'=>$_POST['mainid']))->find();
            $_POST['loupan'] = $loupanData['itemname'];
            $_POST['area'] = $loupanData['Area'];
            $adSQL = M('activity')->add($_POST);
            $this->success('保存成功');
        }
        else 
        {
            $this->error('标题不能为空');
        }
    }
    public function activityDel()
    {
    	if(!empty($_GET['id']))
    	{
    		$a = M('activity');
    		$a->where(array('id'=>$_GET['id']))->delete();
    		$this->success('删除成功');
    	}
    }
    public function activityDisplay()
    {
        $type = $_GET['type'];
        $explode = explode(',', $_GET['id']);
        $where = array();
        for ($i=0;$i<count($explode);$i++)
        {
        if(!empty($explode[$i]))
            $where['id'][] = array('eq',$explode[$i]);
        }
        $where['id'][] = 'or';
        $activity = M('activity');
        if($type==1)
        {
            $activity->where($where)->save(array('display'=>1));
        }
        elseif($type==2)
        {
            $activity->where($where)->save(array('display'=>0));
        }
        $this->success('修改成功');
    }
    public function activityToExamine()
    {
        $type = $_GET['type'];
        $explode = explode(',', $_GET['id']);
        $where = array();
        for ($i=0;$i<count($explode);$i++)
        {
            if(!empty($explode[$i]))
            $where['id'][] = array('eq',$explode[$i]);
        }
        $where['id'][] = 'or';
        $activity = M('activity');
        if($type==1)
        {
            $activity->where($where)->save(array('toExamine'=>1));
        }
        elseif($type==2)
        {
            $activity->where($where)->save(array('toExamine'=>0));
        }
        $this->success('修改成功');
    }
    public function activityType()
    {
        $aT = M('activitytype');
        $aTSQL = $aT->select();
        $this->data = $aTSQL;
        $this->display();
    }
    public function activityTypeSave()
    {
        $aT = M('activitytype');
        $aTSQL = $aT->add($_POST);
        $this->success('保存成功');
    }
    public function privacyTypeDel()
    {
        $aT = M('activitytype');
        $aT->where(array(
            'id'=>$_GET['id']
        ))->delete();
        $this->success('删除成功');
    }
    public function operationLog()//操作日志
    {
        if (!empty($_GET['pageno']))
        {
            $pageno = $_GET['pageno'];
            $startRow = ($_GET['pageno']-1)*10;
        }
        else 
        {
            $startRow = 0;
        }
        
       	$log = M('admin_login');
       	$this->data = $log->order('id desc')->limit($startRow,10)->select();
       	$count = $log->count();
       	$this->count = $count;
        $this->thisPageno = $pageno;
        $this->pageno = pfModel::pagenoArray($pageno,$count);
        $this->ksPageno = array(
            $pageno-9>0?$pageno-9:1,
            $pageno+9<ceil($count/10)?$pageno+9:ceil($count/10)
        );
       	$this->display();
    }
    public function logDel()
    {
        $log = M('admin_login');
        $log->where(array('id'=>$_GET['id']))->delete();
        $this->success('删除成功');
    }
    public function authorityManagement()
    {
        redirect('index.php?s=Web/operationLog');
    }
    public function roleGroup()
    {
        $g = M('admin_group');
        $u = M('admin_user');
        $gSQL = $g->select();
        foreach ($gSQL as $key => $value)
        {
            $name = $value['roleName'];
            $gSQL[$key]['num'] = count($u->where(array('role'=>$name))->select());
        }
        $this->data = $gSQL;
        $this->display();
    }
    public function groupDel()
    {
    	$g = M('admin_group');
    	$g->where(array('id'=>$_GET['id']))->delete();
    	$this->success('删除成功');
    }
    public function addGroupSave()
    {
        if(!empty($_POST))
        {
            $g = M('admin_group');
            $gSQL = $g->add($_POST);
            $this->success('添加成功！',__APP__.'/Web/roleGroup');
        }
        else 
        {
            $this->error('请输入');
        }
    }
    public function roleNode()
    {
        $g = M('admin_group');
        $gSQL = $g->select();
        if(!empty($gSQL))
        {
            
            $data = array();
            $for1 = array('member','realEstate','message','activity','news');
            $for2 = array('add'=>'增','del'=>'删','update'=>'改','select'=>'查');
            foreach ($gSQL as $key => $value)
            {
                $listArray = array();
                $listArray['id'] = $value['id'];
                $listArray['roleName'] = $value['roleName'];
                foreach ($for1 as $for1key => $for1value)
                {
                    foreach ($for2 as $for2key => $for2value)
                    {
                        if($value[$for1value.'_'.$for2key]==1)
                        {
                            $listArray[$for1value] .= $for2value." ";
                        }
                    }
                }
                $data[] = $listArray;
            }
            $this->data = $data;
        }
        
        $this->display();
    }
    public function nodeEdit()
    {
        $this->for1 = array('member'=>'会员管理','realEstate'=>'楼盘管理','message'=>'消息管理','activity'=>'活动管理','news'=>'资讯管理');
        $this->for2 = array('add','del','update','select');

        if(!empty($_GET['id']))
        {
            $g = M('admin_group');
            $gSQL = $g->where(array('id'=>$_GET['id']))->find();
            $this->data = $gSQL;
        }
        $this->display();
    }
    public function roleNodeSave()
    {
        $for1 = array('member','realEstate','message','activity','news');
        $for2 = array('add','del','update','select');
        $id = $_POST['id'];
        unset($_POST['id']);
        $data = array();
        foreach ($for1 as $value)
        {
            foreach ($for2 as $value2)
            {
                $data[$value.'_'.$value2] = 0;
                if(isset($_POST[$value.'_'.$value2]))
                {
                    $data[$value.'_'.$value2] = 1;
                }
            }
        }
        
        $g = M('admin_group');
        $gSQL = $g->where(array('id'=>$id))->save($data);
        $this->success('保存成功！',__APP__.'/Web/roleNode');
    }
    public function roleUser()
    {
        $u = M('admin_user');
        $uSQL = $u->select();
        foreach ($uSQL as $key => $value)
        {
            if ($value['status']==1)
                $uSQL[$key]['status'] = '正常';
            else
                $uSQL[$key]['status'] = '禁用';          
        }
        $this->data = $uSQL;
        $this->display();
    }
    public function userEdit()
    {
        if (!empty($_GET['id']))
        {
            $u = M('admin_user');
            $data = $u->where(array('id'=>$_GET['id']))->find();
            $explode = explode(' ', $data['city']);
            $data['city1'] = $explode[0];
            $data['city2'] = $explode[1];
            unset($data['city']);
            $this->data = $data;
            
            $g = M('admin_group');
            $gSQL = $g->select();
            if(empty($gSQL))
            {
                $this->error('请先创建角色');
                exit();
            }
            $this->role = $gSQL;
            
            $this->display();
        }
    }
    public function userEditSave()
    {
        $data = array();
        if( !empty($_POST['account']) &&
            !empty($_POST['nickname']) &&
            !empty($_POST['role'])
        )
        {
            $u = M('admin_user');
            $data['account'] = $_POST['account'];
            $data['nickname'] = $_POST['nickname'];
            $data['tel'] = $_POST['tel'];
            $data['role'] = $_POST['role'];
            $data['status'] = $_POST['status'];
            $data['city'] = $_POST['city1'].' '.$_POST['city2'];
    
            $u->where(array('account'=>$_POST['account']))->save($data);
            $this->success('保存成功！~',__APP__.'/Web/roleUser');
        }
        else
        {
            $this->error('请输入完整');
        }
    }
    public function addUser()
    {
       $g = M('admin_group');
       $gSQL = $g->select();
       if(empty($gSQL))
       {
           $this->error('请先创建角色');
           exit();
       }
       $this->role = $gSQL;
       $this->display();
    }
    public function userDel()
    {
    	$u = M('admin_user');
    	$u->where(array('id'=>$_GET['id']))->delete();
    	$this->success('删除成功');
    }
    public function addUserSave()
    {
        $data = array();
        if(!empty($_POST['account']) &&
           !empty($_POST['password']) &&
           !empty($_POST['passwordto']) &&
           !empty($_POST['nickname']) &&
           !empty($_POST['role'])    
        )
        {
            $u = M('admin_user');
            if(!$u->where(array('account'=>$_POST['account']))->select())
            {
                if($_POST['password'] != $_POST['passwordto'])
                {
                    $this->error('密码不一致');
                    exit();
                }
                $data['password'] = md5($_POST['password']);
                $data['account'] = $_POST['account'];
                $data['nickname'] = $_POST['nickname'];
                $data['tel'] = $_POST['tel'];
                $data['role'] = $_POST['role'];
                $data['status'] = $_POST['status'];
                $data['city'] = $_POST['city1'].' '.$_POST['city2']; 
                $data['createtime'] = date('Y-m-d');
                
                $u->add($data);
                $this->success('保存成功！~',__APP__.'/Web/roleUser');
            }
            else 
            {
                $this->error('用户名已存在');
            }
        }
        else
        {
            $this->error('请输入完整');
        } 
    }
    public function feedBack()//反馈意见
    {
        $f = M('feedback');
        if(empty($_POST['userid']) && empty($_POST['startDate']))
        {
        	$fSQL =  $f->select();
        }
        else 
        {
        	//$_POST['time'] = array(array('egt',$a_time),array('elt',$b_time),'and');
        	if (!empty($_POST['startDate']) && !empty($_POST['endDate']) && !empty($_POST['userid']))
        	{
        		echo 1;
        		$where = array(
        			'userid'=>$_POST['userid'],
        			'createtime'=>array(
        				array(
        					'egt',$_POST['startDate']
        				),
        				array(
        					'elt',$_POST['endDate']
        				),
        				'and'
        			)
        		);
        		$fSQL = $f->where($where)->select();
        	}
        	elseif (!empty($_POST['startDate']) && !empty($_POST['endDate']))
        	{
        		echo 2;
        		$where = array(
        			'createtime'=>array(
        				array(
        					'egt',$_POST['startDate']
        				),
        				array(
        					'elt',$_POST['endDate']
        				),
        				'and'
        			)
        		);
        		$fSQL = $f->where($where)->select();
        	}
        	elseif(!empty($_POST['userid']))
        	{
        		echo 3;
        		$where = array(
        			'userid'=>$_POST['userid'],
        		);
        		$fSQL = $f->where($where)->select();
        	}
        }
        foreach ($fSQL as $key => $value)
        {
            if($value['status'] == 1)
            {
                $fSQL[$key]['status'] = '已回复';
            }
            else 
            {
                $fSQL[$key]['status'] = '未回复';
            }
        }
        $this->data = $fSQL;
        $this->display();
    }
    public function feedBackDel()
    {
        if(!empty($_GET['id']))
        {
            $f = M('feedback');
            $f->where(array('id'=>$_GET['id']))->delete();
            $this->success('删除成功！');
        }
    }
    public function reply()
    {
        $f = M('feedback');
        $fSQL = $f->where(array('id'=>$_GET['id']))->find();
        $this->data = $fSQL;
        $this->display();
    }
    public function replySave()
    {
        $data = array();
        $data['id'] = $_POST['id'];
        $data['reply'] = $_POST['reply'];
        $data['status'] = 1;
        $f = M('feedback');
        $fSQL = $f->where(array('id'=>$_POST['id']))->save($data);
        $this->success('保存成功',__APP__.'/Web/feedBack');
    }
    public function privacy()//隐私条款
    {
        $p = M('privacy');
        $this->data = $p->find();
        $pl = M('privacy_log');
        $this->time = $pl->select();
        $this->display();
    }
    public function privacySave()
    {
        if(!empty($_POST))
        {
            $p = M('privacy');
            $p->where(array('id'=>1))->save($_POST);
            $pl = M('privacy_log');
            $pl->add(array('time'=>date('Y-m-d')));
            $this->success('保存成功！');
        }
    }
    public function tjbb()
    {
        $this->display();
    }
    public function personalData()
    {
        $u = M('admin_user');
        $uSQL = $u->where(array('id'=>$_SESSION['account']))->find();
        if($uSQL['status']==1)
        {
            $uSQL['status'] = '正常';
        }
        else 
        {
            $uSQL['status'] = '禁用';
        }
        $this->data = $uSQL;
        $this->display();   
    }

    public function passwordSave()
    {
        empty($_POST['ypwd'])?$this->error('请填写完整'):$ypwd = $_POST['ypwd'];
        empty($_POST['pwd1'])?$this->error('请填写完整'):$pwd1 = $_POST['pwd1'];
        empty($_POST['pwd2'])?$this->error('请填写完整'):$pwd2 = $_POST['pwd2'];
        if($pwd1==$pwd2)
        {
            $id = $_SESSION['account'];
            $u = M('admin_user');
            $uSQL = $u->where(array('id'=>$id))->find();
            $md5pwd = md5($ypwd);
            if($uSQL['password'] == $md5pwd)
            {
                $u->where(array('id'=>$id))->save(array('password'=>md5($pwd1)));
                $this->success('修改成功！',__APP__.'/Web/personalData');
            }
            else 
            {
                $this->error('原密码不正确');
            }
        }
        else 
        {
            $this->error('两次密码不一致');
        }
    }
    public function clear()
    {
        if (!empty($_POST['message']))
        {
            unset($_SESSION['where']['message']);
        }
        if(!empty($_POST['loupan']))
        {
            unset($_SESSION['where']['loupan']);
        }
        if(!empty($_POST['activity']))
        {
            unset($_SESSION['where']['activity']);
        }
        if(!empty($_POST['news']))
        {
            unset($_SESSION['where']['news']);
        }
    }
    
}