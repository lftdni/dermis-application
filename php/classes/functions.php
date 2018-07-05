<?php

function get_data_by($field, $field_select = false, $value) {
    global $db;
 
	if(!$field_select) {
		$current_field = '';
	} else {
		$current_field = ', '.$field;
	}
 
	$query = 'SELECT id '.$current_field.' FROM `kuharica`.`korisnici` WHERE '.$field.' = \''.$value.'\'';
	$data = $db->query($query);
	$data = $data->fetch_assoc();
	
	return $data;
}

function username_exists($username, $get_username = false) {
    if ($user = get_data_by('korisnicko_ime', $get_username, $username)) {
        return $user;
    } else {
        return false;
    }
}

function email_exists($email, $get_email = false) {
	if ($email = get_data_by('email', $get_email, $email)) {
        return $email;
    } else {
        return false;
    }
}

?>