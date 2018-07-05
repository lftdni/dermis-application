<?php

class Validations {
	
	public function first_name($first_name) {
		if(strlen($first_name) > 32) {
			return array('status' => 'error', 'message' => 'Ime može sadržavati maksimalno 32 znaka.');
		}
		return false;
	}
	
	public function last_name($last_name) {
		if(strlen($last_name) > 32) {
			return array('status' => 'error', 'message' => 'Prezime može sadržavati maksimalno 32 znaka.');
		}
		return false;
	}
	
	public function birth_day($birth_day) {
		foreach($birth_day as $day) {
			if(empty($day)) {
				return array('status' => 'error', 'message' => 'Datum rođenja mora biti unesen!');
			}
		}
		return false;
	}
	
	public function username($username) {
		if(strlen($username) > 16) {
			return array('status' => 'error', 'message' => 'Korisničko ime može sadržavati maksimalno 16 znakova.');
		}
		return false;
	}
	
	public function email($email) {
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return array('status' => 'error', 'message' => 'Krivi unos e-mail formata.');
		} else {
			return false;
		}
	}
	
	public function password($password, $password2) {
		if(strlen($password) < 8) {
			return array('status' => 'error', 'message' => 'Lozinka mora imat minimalno 8 znakova!');
		} else {
			if($password !== $password2) {
				return array('status' => 'error', 'message' => 'Lozinke se ne poklapaju.');
			}
		}
		return false;
	}
	
	public function phone($phone_number) {
		if(!is_numeric($phone_number)) {
			return array('status' => 'error', 'message' => 'Broj mobitela ili telefona nije u pravilnom formatu.');
		}
		return false;
	}
	
	public function permissions($permission) {
		if($permission === '1' OR $permission === '0') {
			return false;
		} else {
			return array('status' => 'error', 'message' => 'Tip korisničkog računa nije u redu.');
		}
	}
	
	public function check_services($service_data) {
		
		$error = array();

		if(!$service_data['title']) {
			$error[] = array('status' => 'error', 'message' => 'Naslov usluge nesmije biti prazan.');
		}
		if(!$service_data['duration']) {
			$error[] = array('status' => 'error', 'message' => 'Vrijeme usluge mora biti uneseno.');
		} else {
			if(!is_numeric($service_data['duration'])) {
				$error[] = array('status' => 'error', 'message' => 'Vrijeme trajanja usluge mora biti numeričke vrijednosti.');
			}
		}
		if(!$service_data['description']) {
			$error[] = array('status' => 'error', 'message' => 'Opis usluge mora biti unesen.');
		}
		if($error) {
			return $error;
		}
		return false;
		
	}
	
}

?>