<?php
function buildUrl($url,array $data)
{
    if(!empty($data) || !empty($url))
    {
        $url2 = '?';
        $count = count($data);
        $num = 0;
        foreach ($data as $key => $value) 
        {
            $num++;
            $url2 .= $key."=".$value;
            if($num!=$count)
            {
                $url2 .= '&';
            }
        }
        return $url.$url2;
    }
}