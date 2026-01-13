<?php
require_once('../member/auth.php');

$result = bAuthCheck();
if (!$result){
	$outmsg=array('status'=> -1,'msg'=>"permission denied");
	die(json_encode($outmsg));
}
$db_conn=db_connect("host=localhost dbname=$DBNAME user=$WRITER password=$WRITER_PW");
if (!$db_conn){
	$outmsg=array('status'=> -1,'msg'=>"DataBase open fail!");
	die(json_encode($outmsg));
}

	session_start();
	$id=$_SESSION['id'];
	//echo $_SESSION['id'];
	if ($top==1){
		$topSelect='and top=1';
	}
	$QUERY="select clientip from host where id='${id}'";
        $result=db_exec($db_conn,$QUERY);
	if (!$result){
		return false;
	}else {	
        	$ARAW=db_fetch_row($result,0);
		//echo '=== '.$ARAW[0];
		//echo 'http://'.$ARAW[0].'/cron.php';
		$nfn='http://'.trim($ARAW[0]).'/cron.php?pwd=Leaeasd12sad';
        	ini_set('default_socket_timeout', 9);
		$retgo=file($nfn);
		if (is_bool($retgo)) 
			$retmsg='';
		else
			$retmsg=@join('',$retgo);
		echo $retmsg;
	}
?>
