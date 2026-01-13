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

$swop=@(int)$_REQUEST['swop'];
$id=@(int)$_REQUEST['id'];
$status=@(int)$_REQUEST['status'];

if ($swop==1){
	$result=powerSwitch($id,$status,$db_conn);
	if (!$result)
		$outmsg=array('status'=> -1,'msg'=>"error");
	else
		$outmsg=array('status'=> 1,'msg'=>"success");
	die(json_encode($outmsg));
} else if ($swop==2){
	$result=topSetting($id,$db_conn);
	if ($result==0 || $result==1)
		$outmsg=array('status'=> 1,'msg'=>"success",'top'=>$result[0]);
	else
		$outmsg=array('status'=> -1,'msg'=>"設定超過上限四個或設備消失");
	die(json_encode($outmsg));
}

function powerSwitch($id,$status,$db_conn) {
	$QUERY="select host.clientip,device.ip from host,device where host.id=device.host and device.id=${id}";
	$result=db_exec($db_conn,$QUERY);
	$ARAW=db_fetch_row($result,0);
	if ($status==0)
		$cnt="${ARAW[1]}:-200";
	else if ($status==1)
		$cnt="${ARAW[1]}:-100";
	
	$nfn='http://'.trim($ARAW[0]).'/proc.php?pwd=Leaeasd12sad&cmd='.urlencode($cnt);
	ini_set('default_socket_timeout', 9);
	$retmsg=@join('',file($nfn));
        echo "$nfn ret=$retmsg";
//	$retmsg=(int)$retmsg;
	if ($retmsg==1)	{
		$QUERY="update device set state=${status} where id=${id}";
		$result=db_exec($db_conn,$QUERY);
		if (!$result)
			return false;
		else
			return true;
	} else {
		return false;
	}
}
function topSetting($id,$db_conn) {
	session_start();
	$QUERY="select count(top) from device where host='${_SESSION['id']}' and top=1";
	$result=db_exec($db_conn,$QUERY);
	$ARAW=db_fetch_row($result,0);
	$count=$ARAW[0];
	
	$QUERY="select top from device where id=${id}";
	$result=db_exec($db_conn,$QUERY);
	$ARAW=db_fetch_row($result,0);
	$set=$ARAW[0];
	
	if ($count>=4 && $set==0) {
		return false;
	} else {

		if ($set==1)
			$QUERY="update device set top=0 where id=${id}";
		else
			$QUERY="update device set top=1 where id=${id}";
		$result=db_exec($db_conn,$QUERY);
		if (!$result)
			return false;
		else
			return $set;
	}
}
?>
