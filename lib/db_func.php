<?php
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
?>