<?php
if (isset($_REQUEST['uid']) && isset($_REQUEST['pwd'])){
	require_once('hash.php');
	require_once('auth.php');
	
	$login = new Auth();
	$login->uid = str_replace("'","''",$_REQUEST['uid']);
	$login->pwd = generate_hash(str_replace("'","''",$_REQUEST['pwd']));
	$result = $login->authLogin();
	if ($result)
		$outmsg=array('status'=> 1,'msg'=>"登入成功");
    else
		$outmsg=array('status'=> -1,'msg'=>"登入失敗");
	die(json_encode($outmsg));
}
?>