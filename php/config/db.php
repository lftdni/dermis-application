<?php
// Database
$db = new mysqli(
	'127.0.0.1',		// Hostname
	'root',				// Database Username
	'',					// Username Password
	'dermis_app'		// Database Name
);
//

if (!empty($db->connect_error)) {
	throw new Exception('MySQL connect error (' . $db->connect_errno . ') ' . $db->connect_error);
}

if (!$db->set_charset('utf8')) {
	throw new Exception('Error loading character set UTF8: '.$db->error);
}
?>
