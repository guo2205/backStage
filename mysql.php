<?php
 require 'config_mysql.php';
 function mysql_conn()
 {
	global $SQLINFO_HOST,$SQLINFO_USER,$SQLINFO_CODE,$SQLINFO_DATA;

	$conn=mysql_connect($SQLINFO_HOST,$SQLINFO_USER,$SQLINFO_CODE);
	mysql_select_db($SQLINFO_DATA);
	return $conn;
}

?>