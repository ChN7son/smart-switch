<?php
require_once('config.php');
require_once('dbconfig.php');
date_default_timezone_set('Asia/Taipei');
$device=str_replace("'","''",$_REQUEST['id']);
$pwd=str_replace("'","''",$_REQUEST['pwd']);
$db_conn=db_connect("host=$HOST dbname=$DBNAME user=$WRITER password=$WRITER_PW");
db_set_encoding($db_conn,'utf-8');
if (!$db_conn)
    die("DataBase $DBNAME open fail!");

$atime=time();
$Q="update host set atime=$atime where id='$device' and ide='$pwd'";
$result=db_exec($db_conn,$Q);
if (!$result)
	die("DataBase $Q exec fail!");

$Q="select id from host where id='$device' and ide='$pwd'";
$result=db_exec($db_conn,$Q);
if (!$result)
	die("DataBase $Q exec fail!");
$numrow=db_NumRows($result);
if ($numrow!=1)
	die('fail');
echo '0';
?>
