<?php
require_once('../member/auth.php');

ini_set('display_errors', '1');
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
	$QUERY="select host.clientip,device.ip from host,device where host.id=device.host and host.id='${id}'";
	$result=db_exec($db_conn,$QUERY);
	$ARAW=db_fetch_row($result,0);
	$nfn='http://'.trim($ARAW[0]).'/chkstatus.php?pwd=Leaeasd12sad&cmd='.urlencode($cnt);
	ini_set('default_socket_timeout', 9);
	$retmsg=@join('',file($nfn));
	$item=explode(',',trim($retmsg));
	if (count($item)<10) die("Error, $retmsg");
	for ($i=1;$i<=count($item);$i++)
	{
		$onoff=(int) $item[$i];
		$Q="update device set state=$onoff where host='${id}' and ip=$i ";
		$r=db_exec($db_conn,$Q);
		//echo "$Q.<br/>";
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
	$retmsg=(int)$retmsg;
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
