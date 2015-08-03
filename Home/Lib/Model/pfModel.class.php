<?php
class pfModel extends Model
{
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
    
    function pagenoArray($pageno,$count)
    {
        $pagenoArray = array();
        if($pageno<5)
        {
            if ($count%10==0)
            {
                for ($i = 1;$i<=$count/10;$i++)
                {
                    $pagenoArray[] = $i;
                }        
            }
            else
            {
                for ($i=1;$i<=($count/10)+1;$i++)
                {
                    $pagenoArray[] = $i;
                }
            }
        }
        else
        {
            if ($pageno+4>$count)
            {
                for ($i=$pageno-4;$i<=$count;$i++)
                {
                    $pagenoArray[] = $i;
                }
            }
            else
            {
                for ($i=$pageno-4;$i<=$pageno+4;$i++)
                {
                    $pagenoArray[] = $i;
                }
            }
        }
        return $pagenoArray;
    }
    
    function getIP()
    {
        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if(getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if(getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else
            $ip = "Unknow";
        return $ip;
    } 
    
    function getOS()
    {
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if(strpos($agent, 'windows nt')) {
            $platform = 'windows';
        } elseif(strpos($agent, 'macintosh')) {
            $platform = 'mac';
        } elseif(strpos($agent, 'ipod')) {
            $platform = 'ipod';
        } elseif(strpos($agent, 'ipad')) {
            $platform = 'ipad';
        } elseif(strpos($agent, 'iphone')) {
            $platform = 'iphone';
        } elseif (strpos($agent, 'android')) {
            $platform = 'android';
        } elseif(strpos($agent, 'unix')) {
            $platform = 'unix';
        } elseif(strpos($agent, 'linux')) {
            $platform = 'linux';
        } else {
            $platform = 'other';
        }
        return $platform;
    }
    
    function isNode(array $data,$node)
    {
        if (isset($data['all']))
        {
            return true;
        }
        else 
        {
            $flg = false;
            foreach ($data as $key => $value)
            {
                if ($key==$node)
                {
                    if ($value==1)
                    {
                        $flg = true;
                    }
                }
            }
            return $flg;
        }
    }
}