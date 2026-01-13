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
die(json_encode($outmsg));
?>
