<?php
if (isset($_REQUEST['uid']) && isset($_REQUEST['pwd'])){
	require_once('../member/auth.php');
	
	$db_conn=db_connect("host=localhost dbname=$DBNAME user=$WRITER password=$WRITER_PW");
	if (!$db_conn){
		$outmsg=array('status'=> -1,'msg'=>"DataBase open fail!");
		die(json_encode($outmsg));
	}
	
	$login = new Auth();
	$login->uid = str_replace("'","''",$_REQUEST['uid']);
	$login->pwd = str_replace("'","''",$_REQUEST['pwd']);
	$login->db_conn = $db_conn;
	$result = $login->bAuthLogin();
	if ($result)
		$outmsg=array('status'=> 1,'msg'=>"登入成功");
    else
		$outmsg=array('status'=> -1,'msg'=>"登入失敗");
	die(json_encode($outmsg));
}
?>
