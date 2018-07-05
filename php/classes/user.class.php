<?php

class User {
	
	private $db;
	private $user_data = array();
	private $expiry_time = 86400;	// Login expiry time (1 day)
	
	public function __construct(mysqli $db) {
        $this->db = &$db;
		//$this->userLoad();
    }
	
	private function userLoad($user_id = false) {
		
		if (!$user_id) {
			$internal_use = 0;
			$user_id = @$_SESSION['user_id'];
		}
		else {
			$internal_use = 1;
		}

		$user_id = (int)@$this->db->real_escape_string($user_id);
		
		if (!$user_id) {
			return false;
		}
		
		$user_query = 'SELECT * FROM data_users WHERE id = \''.$user_id.'\'';
		$user_result = $this->db->query($user_query);
		
		if (!$user_result) {
			throw new Exception('Error mysql query: ' . $this->db->error);
		}
		
		if ($user_data = $user_result->fetch_assoc()) {
			$user_result->free();
			
			$this->user_data = $user_data;
			
			if ($internal_use) {
				return true;
			}
			
			$login_hash = $user_data['id'].$_SERVER['HTTP_USER_AGENT'].$user_data['password'];
			$login_hash = hash('sha256', $login_hash.LOGIN_SALT);

			$logged_in_time = time()-$_SESSION['login_timestamp'];

			if ($login_hash === $_SESSION['login_hash'] AND
				$logged_in_time < $this->expiry_time) {
				return true;
			}
			else {
				$this->loginDestroy();
			}
		}
		return false;
    }
	
	public function loginCreate($username, $password) {
		
		/*
        if ($this->login_isValid()) {
			throw new Exception('Already logged in');
		}
		*/
		
		$username = $this->db->real_escape_string(substr($username, 0, 32));
						
		$user_query = 'SELECT id, password, username ';
		$user_query .= 'FROM users WHERE username = \'' . $username . '\'';		
		$user_result = $this->db->query($user_query);
		
		if (!$user_result) {
			throw new Exception('Error mysql query: ' . $this->db->error);
		}

		// If username is correct
		if ($user_data = $user_result->fetch_assoc()) {
			$user_id = (int)$user_data['id'];
			$username = $user_data['username'];
			// If password is correct
			$hashed_password = hash('sha1', $password);

			if ($hashed_password === $user_data['password']) {
										
				$login_hash = $user_id.$_SERVER['HTTP_USER_AGENT'].$user_data['password'];
				$login_hash = hash('sha1', $login_hash);
				
				$this->user_data['id'] 			= $user_id;
				$_SESSION['user_id'] 			= $user_id;
				$_SESSION['username']			= $username;
				$_SESSION['login_hash'] 		= $login_hash;
				$_SESSION['login_timestamp'] 	= time();
					
				// Login OK
				$status = 1;
				
			} else {
				// Wrong password
				$status = 3;
			}
			$user_result->free();
		} else {
			// Unknown username
			$status = 2;
			$user_id = null;
		}

		# 1 -> OK
		# 2 -> UNKNOWN USERNAME
		# 3 -> WRONG PASSWORD

		/*
		if ($status === 1) {
			
			$ip_user = $this->user_getIP();
			$qdata = array(
				'last_datetime' 	=> date('Y-m-d H:i:s'),
				'last_ip' 			=> $ip_user['ip'],
				'last_ccode' 		=> $ip_user['cc']
			);
			
			// Update last data
			$this->userUpdate($qdata);

			// LOG login attempt
			$this->save_loginLog($user_id, $status, $pusername);
		}
		*/
		return $status;
    }
	
	public function login_isValid() {
        if (!empty($_SESSION['login_hash']) AND !empty($_SESSION['user_id'])) {
            return true;
        }
        return false;
    }
	
	public function loginDestroy() {
		$this->user_data = array();
		session_destroy();
		return true;
    }
	
	public function does_username_exist($username) {
		$username = $this->db->real_escape_string($username);
		$username_query = 'SELECT id FROM users WHERE username = \''.$username.'\'';
		$username_result = $this->db->query($username_query);
		$user_data = $username_result->fetch_assoc();
		if ($user_data['id']) {
			return array('status' => 'error', 'message' => 'Korisničko ime već postoji.');
		}
		return false;
	}

	public function does_email_exists($email) {
		$email = $this->db->real_escape_string($email);
		$email_query = 'SELECT id FROM users WHERE email = \''.$email.'\'';
		$email_result = $this->db->query($email_query);
		$user_data = $email_result->fetch_assoc();
		if ($user_data['id']) {
			return array('status' => 'error', 'message' => 'E-Mail već postoji.');
		}
		return false;
	}
	
}

?>