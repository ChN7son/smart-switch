<?php
$results = deviceSelect($db_conn, $_SESSION['id']);
$option_str = '';
if ($results){
	for ($i=0;$i<$results[1];$i++){
    	$row = db_fetch_assoc($results[0],$i);
		$optionDevice = new Template();
		$optionDevice->set('device_id', $row['id']);
		$optionDevice->set('device_name', $row['devname']);
		$optionDeviceHtml = $optionDevice->render('option_device.html');
		
		$option_str .= $optionDeviceHtml;
    }
}
$template->set('option_device', $option_str);

$cron_list_str = '';
$results = cronSelect($db_conn, $_SESSION['id']);
if ($results){
	for ($i=0;$i<$results[1];$i++){
    	$row = db_fetch_assoc($results[0],$i);
		$cronList = new Template();
		$cronList->set('cron_id', $row['id']);
		$cronList->set('cron_dname', $row['devname']);
		$cronList->set('cron_status', $row['control'] == 1 ? '開啟' : '關閉');
		$cronList->set('cron_time', substr($row['time'], 0, 5));
		$cronList->set('cron_weekday', $row['daily']);
		$cronList->set('cron_repeat', $row['repeat'] == 1 ? 'V' : '');
		$cronListHtml = $cronList->render('cron_list.html');
		
		$cron_list_str .= $cronListHtml;
    }
}
$template->set('cron_list', $cron_list_str);

$html = $template->render('cron.html');
?>