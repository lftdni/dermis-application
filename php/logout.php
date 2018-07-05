<?php

if($is_logged_in) {
	$user->loginDestroy();
	header('Location: /login'); exit;
}


?>