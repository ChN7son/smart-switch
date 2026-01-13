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

$nname=str_replace("'","''",$_REQUEST['nname']);
$name=$_SESSION['id'];
$db_conn=db_connect("host=localhost dbname=$DBNAME user=$WRITER password=$WRITER_PW");
db_set_encoding($db_conn,'utf-8');
if (!$db_conn)
	die("DataBase $DBNAME open fail!");
$Q="update host set dname='$nname' where id='$name'";
$result=db_exec($db_conn,$Q);
if (!$result)
{
	die('<b style="color:red;">設備名稱修改失敗！</b>');
}
echo '<b style="color:green;">'.$name.' 設備名稱修改成功！</b>';
$_SESSION['dname']=$nname;
?>
