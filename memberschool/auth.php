<?php
require_once('../config.php');
require_once('../dbconfig.php');

class Auth {
	public $uid;
	public $pwd;
    //public $level;
	public $db_conn;
	
    public function authLogin() {
		$QUERY="select uid,level from userdb where uid='$this->uid' and pwd='$this->pwd'";
		$result=db_exec($this->db_conn,$QUERY);
		$numrow=db_NumRows($result);
		if (!$result || $numrow!=1){
			return false;
		} else {
			$ARAW=db_fetch_row($result,0);
			session_start();
			$_SESSION['uid']=$ARAW[0];
			$_SESSION['level']=$ARAW[1];
			return true;
		}
    }
	public function bAuthLogin() {
		$QUERY="select id,dname from host where id='$this->uid' and manpwd='$this->pwd'";
		$result=db_exec($this->db_conn,$QUERY);
		$numrow=db_NumRows($result);
		if (!$result || $numrow!=1){
			return false;
		} else {
			$ARAW=db_fetch_row($result,0);
			session_start();
			$_SESSION['id']=$ARAW[0];
			$_SESSION['dname']=$ARAW[1];
			return true;
		}
    }
}

function authCheck() {
	session_start();
	if (isset($_SESSION['level']) && $_SESSION['level']>=1)
		return true;
	else
		return false;
}

function bAuthCheck() {
	session_start();
	if (isset($_SESSION['id']) && isset($_SESSION['dname']))
		return true;
	else
		return false;
}

if (isset($_REQUEST['op']) && $_REQUEST['op'] == 2) {
	$result = bAuthCheck();
	if ($result)
		$outmsg=array('status'=> 1,'msg'=>"access allowed",'id'=>$_SESSION['id'],'dname'=>$_SESSION['dname']);
	else
		$outmsg=array('status'=> -1,'msg'=>"permission denied");
	die(json_encode($outmsg));
}
?>