<?php
$navBar = new Template();
$navBarHtml = $navBar->render('nav_bar.html');
$template->set('nav_bar', $navBarHtml);
$html = $template->render('device.html');
?>