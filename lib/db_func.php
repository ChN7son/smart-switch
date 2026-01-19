<?php
function deviceSelect($db_conn, $top = 0) {
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

function deviceInsert($db_conn,$devname) {
	session_start();
	$QUERY="insert into device (id, host, devname, state, access, price, ip, top) values ((select max(id)+1 from device), '${_SESSION['id']}', '$devname', 0, 0, 10, (SELECT COALESCE(MAX(ip), 0) + 1 FROM device), 0)";
	$result=db_exec($db_conn,$QUERY);
	$numrow=db_NumRows($result);
	if (!$result){
		return false;
	}else {	
		return true;
	}
}

function deviceUpdate($db_conn,$id,$devname) {
	$QUERY="update device set devname='${devname}' where id=${id}";
	$result=db_exec($db_conn,$QUERY);
	$numrow=db_NumRows($result);
	if (!$result){
		return false;
	}else {	
		return true;
	}
}

function deviceDelete($db_conn,$id) {
	$QUERY="delete from device where id=${id}";
	$result=db_exec($db_conn,$QUERY);
	$numrow=db_NumRows($result);
	if (!$result){
		return false;
	}else {	
		return true;
	}
}

function powerSwitch($id,$status,$db_conn) {
	$QUERY="update device set state=${status} where id=${id}";
	$result=db_exec($db_conn,$QUERY);
	if (!$result)
		return false;
	else
		return true;
}

function topSetting($id,$db_conn) {
	/* 取消上限
	$QUERY="select count(top) from device where host='${_SESSION['id']}' and top=1";
	$result=db_exec($db_conn,$QUERY);
	$ARAW=db_fetch_row($result,0);
	$count=$ARAW[0];
	if ($count>=4 && $set==0) {
		return false;
	}
	*/
	
	$QUERY="select top from device where id=${id}";
	$result=db_exec($db_conn,$QUERY);
	$ARAW=db_fetch_row($result,0);
	$set=$ARAW[0];
	
	if ($set==1){
		$QUERY="update device set top=0 where id=${id}";
	} else {
		$QUERY="update device set top=1 where id=${id}";
	}
	$result=db_exec($db_conn,$QUERY);
	if (!$result){
		return false;
	} else {
		return $set;
	}
}

function cronSelect($db_conn, $host){
	$query = "SELECT d.devname, c.time, c.dev, c.control, c.daily, c.repeat
			  FROM cron AS c
			  JOIN device AS d ON d.id = c.dev
			  JOIN host AS h ON h.id = d.host AND h.id = '${host}'
	";
	$result = db_exec($db_conn, $query);
	$numrow = db_NumRows($result);
	if (!$result){
		return false;
	}else {	
		return array($result, $numrow);
	}
}

function cronInsert($db_conn, $device, $control, $weekdays, $execute_time, $execute_repeat){
	$query = "INSERT INTO cron (id, time, dev, control, daily, repeat) VALUES ((select max(id)+1 from cron), '${execute_time}', ${device}, ${control}, '${weekdays}', ${execute_repeat})";
	$result = db_exec($db_conn,$query);
	$numrow = db_NumRows($result);
	if (!$result){
		return false;
	}else {	
		return true;
	}
}
?>