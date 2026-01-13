<?php
require_once('../config.php');
require_once('../dbconfig.php');

$db_conn=db_connect("host=localhost dbname=$DBNAME user=$WRITER password=$WRITER_PW");
if (!$db_conn){
	$outmsg=array('status'=> -1,'msg'=>"DataBase open fail!");
	die(json_encode($outmsg));
}

session_start();

$QUERY="select atime from host where id='${_SESSION['id']}'";
$result=db_exec($db_conn,$QUERY);
if (!$result || $numrow!=1){
	$outmsg=array('status'=> -1,'msg'=>"DataBase open fail!");
}
$ARAW=db_fetch_row($result,0);
$outmsg=array('status'=> 1,'msg'=>"success",'atime'=>$ARAW[0]);
die(json_encode($outmsg));
?>