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
		$op=(int)$_REQUEST['op'];
		$line=(int)$_REQUEST['line'];
		$min=$_REQUEST['min'];
		if ($min!='*') $min=(int)$min;
		$h=$_REQUEST['h'];
		if ($h!='*') $h=(int)$h;
		$d=$_REQUEST['d'];
		if ($d!='*') $d=(int)$d;
		$m=$_REQUEST['m'];
		if ($m!='*') $m=(int)$m;
		$w=$_REQUEST['w'];
		if ($w!='*') $w=(int)$w;
		$act=(int)$_REQUEST['act'];
		$devid=(int)$_REQUEST['devid'];
		if ($op==-3)//del
			$nfn='http://'.trim($ARAW[0])."/setcron.php?pwd=Leaeasd12sad&op=-3&line=${line}";
		else
			$nfn='http://'.trim($ARAW[0])."/setcron.php?pwd=Leaeasd12sad&min=${min}&h=${h}&d=${d}&m=${m}&w=${w}&act=${act}&devid=${devid}";
		$fp=fopen('tmp/aaa.txt','a');
		fwrite($fp,$nfn."\n");
		fclose($fp);
        	ini_set('default_socket_timeout', 9);
		$retgo=file($nfn);
                if (is_bool($retgo))
                        $retmsg='';
                else
                        $retmsg=@join('',$retgo);
		echo $retmsg;
	}
?>
