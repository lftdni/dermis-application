<?php
// Settings
date_default_timezone_set('Europe/Zagreb');
$current_datetime = date('Y-m-d H:i:s');

// Include
include('php/config/db.php');
include('php/config/config.php');

// Session start
session_start();

// Include Classes
include('php/classes/user.class.php');

// Load Class
$user = new User($db);
$is_logged_in = $user->login_isValid();

// All pages
$pages = array(
	'login'					=> 1,
	'home'					=> 1,
	'services'				=> 1,
	'services-add'			=> 1,
	'customers'				=> 1,
	'customers-add'			=> 1,
	'users'	 				=> 1,
	'users-add'	 			=> 1,
	'logout'				=> 1
);

// URL rewrite
$request_arr 	= @explode('/', trim($_GET['r'], '/'), 5); 		# 0 > page, 1,2,3.. > options
$request_page 	= @$request_arr[0]; 							# request page

// Check if page exists
if (!empty($pages[$request_page])) {
	$include_page = $request_page;
} else {
	if ($request_page) {
		// 404
		$include_page = '404';
	} else {
		// Default page
		$include_page = 'login';
	}
}

// Check if page exists on server
$include_page = 'php/'.$include_page.'.php';
if (file_exists($include_page)) {
	include($include_page);
} else {
	die('File does not exist!');
}

?>