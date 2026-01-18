<?php
require_once('../member/auth.php');

$db_conn=db_connect("host=$HOST dbname=$DBNAME user=$WRITER password=$WRITER_PW");
if (!$db_conn){
	$outmsg=array('status'=> -1,'msg'=>"DataBase open fail!");
	die(json_encode($outmsg));
}

$action = $_GET['action'];
switch ($action) {
    case 'login':
        if (isset($_REQUEST['uid']) && isset($_REQUEST['pwd'])){       
        	
        
        	$login = new Auth();
        	$login->uid = str_replace("'","''",$_REQUEST['uid']);
        	$login->pwd = str_replace("'","''",$_REQUEST['pwd']);
        	$login->db_conn = $db_conn;
        	$result = $login->AuthLogin();
        	if ($result)
        		$outmsg=array('status'=> 1,'msg'=>"登入成功");
            else
        		$outmsg=array('status'=> -1,'msg'=>"登入失敗");
        	die(json_encode($outmsg));
        }

        break;
    case 'device':
        $result = AuthCheck();
        if (!$result){
        	$outmsg=array('status'=> -1,'msg'=>"permission denied");
        	die(json_encode($outmsg));
        }

        $dop=@(int)$_REQUEST['dop'];
        if ($dop==0){
        	$top=(int)$_REQUEST['top'];
        	$result=deviceSelect($db_conn,$top);
        	for ($i=0;$i<$result[1];$i++){
        		$msg=json_encode(db_fetch_assoc($result[0],$i));
        		echo $msg."\n";
        	}
        	echo $i;
        } else if ($dop==1){
        	//insert into...
        } else if ($dop==2){
        	$id=(int)$_REQUEST['id'];
        	$devname=str_replace("'","''",$_REQUEST['devname']);
        	$result=deviceUpdate($db_conn,$id,$devname);
        	if ($result)
        		$outmsg=array('status'=> 1,'msg'=>"success");
        	else
        		$outmsg=array('status'=> -1,'msg'=>"error");
        	die(json_encode($outmsg));
        }

        function deviceSelect($db_conn,$top) {
        	session_start();
        	if ($top==1){
        		$topSelect='and top=1';
        	}
        	$QUERY="select id,devname,state,access,price,ip,top from device where host='${_SESSION['id']}' ${topSelect} order by id asc";
        	$result=db_exec($db_conn,$QUERY);
        	$numrow=db_NumRows($result);
        	if (!$result){
        		return false;
        	}else {	
        		return array($result, $numrow);
        	}
        }
        function deviceUpdate($db_conn,$id,$devname) {
        	session_start();
        	$QUERY="update device set devname='${devname}' where id=${id}";
        	$result=db_exec($db_conn,$QUERY);
        	$numrow=db_NumRows($result);
        	if (!$result){
        		return false;
        	}else {	
        		return true;
        	}
        }
        break;
    case 'atime':
        session_start();

        $QUERY = "select atime from host where id='${_SESSION['id']}'";
        $result = db_exec($db_conn, $QUERY);
        if (!$result || $numrow != 1){
        	$outmsg = array('status'=> -1,'msg'=>"DataBase open fail!");
        }
        $ARAW = db_fetch_row($result, 0);
        $outmsg = array('status'=> 1,'msg'=>"success",'atime'=>$ARAW[0]);
        die(json_encode($outmsg));
    default:
        break;
}
?>