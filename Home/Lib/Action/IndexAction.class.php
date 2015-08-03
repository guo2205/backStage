<?php
class IndexAction extends Action {
    public function index()
    {
        $this->display();
    }
    public function check()
    {
        if(!empty($_POST))
        {
            $account = $_POST['account'];
            $password = $_POST['password'];
            $au = M('admin_user');
            $where = array();
            $where['account'] = $account;
            $where['password'] = md5($password);
            $auSQL = $au->where($where)->find();
            if($auSQL)
            {
                $_SESSION['account'] = (int)$auSQL['id'];
                $_SESSION['nickname'] = $auSQL['nickname'];
                
                M('admin_login')->add(array(
                	'account'=>$account,
                	'ip'=>pfModel::getIP(),
                    'os'=>pfModel::getOS(),
                	'time'=>date('Y-m-d G:i:s')
                ));
                //记录权限
                if($_SESSION['account'] == 1)
                {
                    $_SESSION['group'] = array('all'=>1);
                }
                else 
                {
                    $role = $auSQL['role'];
                    $group  = M('admin_group');
                    $_SESSION['group'] = $group->where(array('roleName'=>$role))->find();
                }
                
                $this->success('欢迎你，'.$auSQL['nickname'],__URL__.'/body');
                //dump($_SESSION['group']);
                
            }
            else
            {
                $this->error('登录失败');
            }
        }
    }
    public function body()
    {
        if(!isset($_SESSION['account']))
        {
            $this->error('请登录',__URL__.'/index');   
        }
        else 
        {
            $this->display();
        }
    }
    
    public function menu()
    {
        //'会员管理','楼盘管理','活动管理','资讯管理','消息推送'
        $this->data = array(
            array(
                array('会员管理','memberManage')
            ),
            array(
                array('楼盘管理','realEstateManagement')
            ),
            array(
                array('活动管理','activity'),
                array('活动分类','activityType')
            ),
            array(
                array('资讯管理','news'),
                array('资讯分类','newsType')
            ),
            array(
                array('消息推送','message')
            ),
            array(
                array('系统管理','authorityManagement'),
                array('操作日志','operationLog'),
                array('权限管理','roleGroup'),
                array('反馈意见','feedBack'),
                array('隐私条款','privacy')
            ),
            array(
                array('个人资料','personalData'),
                array('修改密码','changePassword')
            ),
            /*
            array(
                array('统计报表',''),
                array('用户分析',''),
                array('存留分析',''),
                array('渠道分析',''),
                array('用户参与度',''),
                array('功能使用',''),
                array('终端分析',''),
                array('用户行为',''),
                array('分享分析','')
                
            )
            */
            array(
                array('统计报表','tjbb')
            )
        );
        $this->display();
    }
    public function top()
    {
        $this->data1 = date('Y-m-d');
        $this->data2 = $_SESSION['nickname'];
        $this->display();
    }
    public function logOff()
    {
    	unset($_SESSION['account']);
    	$this->success('登出成功！',__APP__);
    }
    
}