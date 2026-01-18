<?php
require_once('../member/auth.php');
require_once('../template/template.php');

$result = AuthCheck();
$template = new Template();
if (!$result){
    include 'login.php';
} else {
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    //$navBar = file_get_contents('nav_bar.html');

    switch ($action) {
        case 'device':
            include 'device.php';
            break;
        default:
			$navBar = new Template();
			$navBarHtml = $navBar->render('nav_bar.html');
            $template->set('nav_bar', $navBarHtml);
            $html = $template->render('index.html');
            break;
    }
}

echo $html;
?>