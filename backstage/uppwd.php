<?php
require_once('../config.php');
require_once('../dbconfig.php');
$name=str_replace("'","''",$_REQUEST['id']);
$pwd=str_replace("'","''",$_REQUEST['pwd']);
$npwd1=str_replace("'","''",$_REQUEST['npwd1']);
$npwd2=str_replace("'","''",$_REQUEST['npwd2']);
$DAYTIME=date('Y.m.d H:i:s');
$IP=$_SERVER['REMOTE_ADDR'] ;
$db_conn=db_connect("host=localhost dbname=$DBNAME user=$WRITER password=$WRITER_PW");
db_set_encoding($db_conn,'utf-8');
if (!$db_conn)
	die("DataBase $DBNAME open fail!");
$Q="select id from host where id='$name' and manpwd='$pwd'";
$result=db_exec($db_conn,$Q);
if (!$result)
	die("DataBase $Q exec fail!");
$numrow=db_NumRows($result);
if ($numrow!=1)
	die('<b style="color:red;">驗證失敗！設備編號或密碼錯誤</b>');
if ($npwd1!=$npwd2)
	die('<b style="color:red;">兩個新密碼不一致</b>');
if (strlen(trim($npwd1))<4)
	die('<b style="color:red;">新密碼太短，至少要4個字</b>');
$Q="update host set manpwd='$npwd1' where id='$name' and manpwd='$pwd'";
$result=db_exec($db_conn,$Q);
if (!$result)
{
	die('<b style="color:red;">密碼修改失敗！設備編號或密碼錯誤</b>');
}
echo '<b style="color:green;">'.$name.' 密碼修改成功！</b>';
?>
