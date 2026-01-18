<?php
require_once('../member/auth.php');
require_once('../template/template.php');
require_once('../lib/db_func.php');

$db_conn=db_connect("host=$HOST dbname=$DBNAME user=$WRITER password=$WRITER_PW");
if (!$db_conn){
	die("伺服器異常");
}

$result = AuthCheck();
$template = new Template();
if (!$result){
    include 'login.php';
} else {
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    
	// nav 選單
	$navBar = new Template();
	$navBarHtml = $navBar->render('nav_bar.html');
    $template->set('nav_bar', $navBarHtml);
	
	$deviceResults = deviceSelect($db_conn);
	$totalDevice = isset($deviceResults[1]) ? $deviceResults[1] : 0;
	$template->set('total_device', $totalDevice);

    switch ($action) {
        case 'device':
            include 'device.php';
            break;
        default:
			$template->set('id', $_SESSION['id']);			// 能進到這層基本上都是有login SESSION
			$template->set('dname', $_SESSION['dname']);	// 所以不用防跳脫
            $html = $template->render('index.html');
            break;
    }
}

echo $html;
?>