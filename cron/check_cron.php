<?php
require_once(__DIR__  . '/../config.php');
require_once(__DIR__  . '/../dbconfig.php');
require_once(__DIR__  . '/../lib/db_func.php');
$db_conn=db_connect("host=$HOST dbname=$DBNAME user=$WRITER password=$WRITER_PW");
if (!$db_conn){
	die("伺服器異常");
}

/* 現在時間*/
date_default_timezone_set('Asia/Taipei');
$dayWeek = date("w") === '0' ? '7' : date("w");
$hourMinute = date('H:i');
/******/

$query = "select id,time,dev,control,daily,repeat from cron where id <> 0";
$results = db_exec($db_conn, $query);
$numrow = db_NumRows($results);
if ($numrow){
	for ($i=0;$i<$numrow;$i++){
    	$row = db_fetch_assoc($results,$i);
		$row['daily'] = trim($row['daily']);
		$exec_time = substr($row['time'], 0, 5);
		$exec_week = $row['daily'] != '' ? explode(',', $row['daily']) : array();
		
		if (count($exec_week) == 0 || in_array($dayWeek, $exec_week)){
			if ($hourMinute == $exec_time){
				$query = "update device set state=${row['control']} where id=${row['dev']}";
				$result = db_exec($db_conn, $query);
				
				if ($row['repeat'] == 0){
					$query = "delete from cron where id = ${row['id']}";
					$result = db_exec($db_conn, $query);
				}
			}
		}
    }
}
?>