<?php
require_once('../config.php');
require_once('../dbconfig.php');

class Auth {
	public $uid;
	public $pwd;
    //public $level;
	public $db_conn;
	
	public function AuthLogin() {
		$QUERY ="select id,dname from host where id='$this->uid' and manpwd='$this->pwd'";
		$result = db_exec($this->db_conn,$QUERY);
		$numrow = db_NumRows($result);
		if (!$result || $numrow!=1){
			return false;
		} else {
			$ARAW = db_fetch_row($result,0);
			session_start();
			$_SESSION['id'] = $ARAW[0];
			$_SESSION['dname'] = $ARAW[1];
			return true;
		}
    }
}

function AuthCheck() {
	session_start();
	if (isset($_SESSION['id']) && isset($_SESSION['dname']))
		return true;
	else
		return false;
}
?>
