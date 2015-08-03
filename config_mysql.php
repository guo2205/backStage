<?php
  //static 
    $SQLINFO_HOST='localhost';
    $SQLINFO_USER='root';
    $SQLINFO_CODE='';
    $SQLINFO_DATA='hy';
    $SQL_Trace=false;
    function _sqllog($str)
    {
    	global $SQL_Trace;
	if($SQL_Trace)
	{
		echo $str.'<br>';
	}
    }

?>