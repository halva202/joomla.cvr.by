<?php
function select($table, $conditions){
	header( 'Content-Type: text/html; charset=utf-8' );
	$server = "localhost";
	
	$user = 'iconmast_calend';
	$pass = 'Nsm%)+z2IV@5';
	$database = "iconmast_calendar";
	
	$db = mysql_connect($server, $user, $pass);
	if(!mysql_select_db($database)){
		echo 'bd is absent<br>';
		die(mysql_error());
	};
	mysql_set_charset( 'utf8' );

	$select = mysql_query('SELECT * FROM '.$table.' where '.$conditions);
	return $select;
}
?>