<?php
require_once('../member/auth.php');
ini_set('display_errors', '0');
$result = bAuthCheck();
if (!$result){
	$outmsg=array('status'=> -1,'msg'=>"permission denied");
	die(json_encode($outmsg));
}
$db_conn=new PDO('pgsql:dbname='.$DBNAME.';host=localhost;', $WRITER,$WRITER_PW);
if (!$db_conn){
	$outmsg=array('status'=> -1,'msg'=>"DataBase open fail!");
	die(json_encode($outmsg));
}
session_start();
	$top=(int)$_REQUEST['top'];
	if ($top==1){
		$topSelect='and top=1';
	}

	$QUERY="select id,devname,state,access,price,ip,top from device where host='${_SESSION['id']}' ${topSelect} order by id asc";
	$result=$db_conn->query($QUERY);
	if (!$result){
		 $outmsg=array('status'=> -1,'msg'=>"query error");
        	die(json_encode($outmsg));

	}else {	
		echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
	}
?>
