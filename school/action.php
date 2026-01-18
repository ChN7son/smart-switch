<?php
require_once('../member/auth.php');
require_once('../lib/db_func.php');
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
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
        	$devname = str_replace("'","''",$_REQUEST['devname']);
			$result = deviceInsert($db_conn,$devname);
			if ($result)
        		$outmsg=array('status'=> 1,'msg'=>"success");
        	else
        		$outmsg=array('status'=> -1,'msg'=>"error");
        	die(json_encode($outmsg));
        } else if ($dop==2){
        	$id=(int)$_REQUEST['id'];
        	$devname=str_replace("'","''",$_REQUEST['devname']);
        	$result=deviceUpdate($db_conn,$id,$devname);
        	if ($result)
        		$outmsg=array('status'=> 1,'msg'=>"success");
        	else
        		$outmsg=array('status'=> -1,'msg'=>"error");
        	die(json_encode($outmsg));
        } else if ($dop==3){
			$id = (int)$_REQUEST['id'];
			$result = deviceDelete($db_conn,$id);
        	if ($result)
        		$outmsg=array('status'=> 1,'msg'=>"success");
        	else
        		$outmsg=array('status'=> -1,'msg'=>"error");
        	die(json_encode($outmsg));
		}
        break;
	case 'switch':
		$result = AuthCheck();
		if (!$result){
			$outmsg=array('status'=> -1,'msg'=>"permission denied");
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