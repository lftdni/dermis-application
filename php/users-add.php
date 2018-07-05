<?php

if(!$is_logged_in) {
	header('Location: /login'); exit;
}

include('php/classes/functions.php');
include('php/classes/validations.class.php');

$validations = new Validations();

//echo '<pre>',print_r($_POST),'</pre>'; exit;

// HTML Info Podaci
$html_data = array(
	'title' 		=> 'Unos Korisnika - Kuharica',
	'description'	=> '',
	'author' 		=> 'Nives Miletić',
	'page_title'	=> 'Unos Korisnika',
	'button'		=> array('name' => 'add_user', 'value' => 'true', 'text' => 'Dodaj Korisnika')
);

$user_data = array();

if(@$_POST['add_user'] === 'true' OR @$_POST['edit_user'] === 'true') {
	
	// Make user_data array and trim all $_POST values
	$user_data = array();
	
	foreach($_POST as $value => $key) {
		$user_data[$value] = trim($key);
 	}
	
	$messages = array();
	
	// Check if name has lower then 32 chars
	if($error = $validations->first_name($user_data['first_name'])) {
		$messages[] = $error;
	}
	
	// Check if surname has lower then 32 chars
	if($error = $validations->last_name($user_data['last_name'])) {
		$messages[] = $error;
	}
	
	// Check if username has lower then 16 chars
	if($error = $validations->username($user_data['username'])) {
		$messages[] = $error;
	}
	
	// Validate email format
	if($error = $validations->email($user_data['email'])) {
		$messages[] = $error;
	}
	
	// Check if password is less then 8 chars and check if passwords are equal
	if($error = $validations->password($user_data['password'], $user_data['password2'])) {
		$messages[] = $error;
	}

	// Check if account type is 1 or 0
	if($error = $validations->permissions($user_data['permissions'])) {
		$message[] = $error;
	}
	
	// IF empty messages(no errors) continue
	if(!$messages) {
		
		if($error = $user->does_username_exist($user_data['username'])) {
			$messages[] = $error;
		}
		
		if($error = $user->does_email_exist($user_data['email'])) {
			$messages[] = $error;
		}
		
		if(!$messages) {
			
			// Add and change key(s) in user_data array
			$user_data['password'] 				= sha1($user_data['password']);
			$user_data['creation_datetime'] 	= $current_datetime;
			$user_data['lastupdate_datetime'] 	= $current_datetime;
			
			// Remove key(s) from user_data to prepare for implode
			unset(
				$user_data['password2'],
				$user_data['add_user']
			);

			$add_user_query = 'INSERT INTO `dermis_app`.`users` (`'.implode('`, `', array_keys($user_data)).'`) VALUES (\''.implode('\', \'', $user_data).'\')';
			
			//echo '<pre>',print_r($user_data),'</pre>'; exit;
			
			if($user_data['permissions'] === '0') {
				$acc_type_name = 'Korisnički ';
			} else {
				$acc_type_name = 'Administracijski ';
			}
			
			if($db->query($add_user_query)) {
				$messages[] = array('status' => 'success', 'message' => $acc_type_name.'račun je uspješno kreiran.');
			} else {
				$messages[] = array('status' => 'error', 'message' => 'Nešto nije u redu sa bazom podataka.');
			}
		}
	}
}

// Delete Users

if(@$request_arr[1] === 'delete' AND !empty(@$request_arr[2])) {
	
	if(is_numeric($request_arr[2])) {
		
		$user_id = $request_arr[2];
		
		$delete_user_query = 'DELETE FROM users WHERE id = \''.$user_id.'\'';

		if($db->query($delete_user_query)) {
			if($db->affected_rows === 1) {
				$messages[] = array('status' => 'success', 'message' => 'Korisnik je uspješno obrisan.');
			} else {
				$messages[] = array('status' => 'error', 'message' => 'Korisnik ne postoji.');
			}
		} else {
			$messages[] = array('status' => 'error', 'message' => 'Korisnik nije obrisan. Nešto nije u redu sa bazom podataka.');
		}
	} else {
		$messages[] = array('status' => 'error', 'message' => 'Hack protection!');
	}
	
}

// Edit Users
if(@$request_arr[1] === 'edit' AND !empty(@$request_arr[2])) {
	
	if(is_numeric($request_arr[2])) {
		
		$user_id 			= $db->real_escape_string($request_arr[2]);
		$user_data_query 	= 'SELECT * FROM users WHERE id = \''.$user_id.'\'';
		$user_data 			= $db->query($user_data_query);
		$user_data 			= $user_data->fetch_assoc();
					
	} else {
		header('Location: \admin\users-add'); exit;
	}
	
	$html_data = array(
		'title' 		=> 'Uredi Korisnika - Kuharica',
		'description'	=> '',
		'author' 		=> 'Nives Miletić',
		'page_title'	=> 'Uredi Korisnika ('.$user_data['username'].')',
		'button'		=> array('name' => 'edit_user', 'value' => 'true', 'text' => 'Uredi Korisnika')
	);

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?=$html_data['description'];?>">
    <meta name="author" content="<?=$html_data['author'];?>">

    <title><?=$html_data['title'];?></title>

    <!-- Bootstrap Core CSS -->
    <link href="<?=BASE_URL;?>css/bootstrap.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?=BASE_URL;?>css/metisMenu.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="<?=BASE_URL;?>css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?=BASE_URL;?>css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?=BASE_URL;?>css/font-awesome.css" rel="stylesheet" type="text/css">

</head>

<body>

    <div id="wrapper">

	<?php include('php/include/navigation.html'); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?=$html_data['page_title'];?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
			<?php if(@$messages) : ?>
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Poruke sustava
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
						<?php foreach ($messages as $key => $value) : ?>
							<?php 
								if($value['status'] === 'error') {
									$alert_class = 'danger';
								} else {
									$alert_class = 'success';
								}
							?>
                            <div class="alert alert-<?=$alert_class;?>">
                                <strong><?=$value['message'];?></strong>
                            </div>
						<?php endforeach; ?>
                        </div>
                        <!-- .panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<?php endif; ?>
            <!-- /.row -->
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						Ispunite informacije o korisniku
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-6">
								<form role="form" method="POST" accept-charset="UTF-8">
									<div class="form-group">
										<label>Ime</label>
										<input class="form-control" type="text" name="first_name" maxlength="32" value="<?=@$user_data['first_name'];?>" required>
										<p class="help-block">(primjer: Ivan)</p>
									</div>
									<div class="form-group">
										<label>Prezime</label>
										<input class="form-control" type="text" name="last_name" maxlength="32" value="<?=@$user_data['last_name'];?>" required>
										<p class="help-block">(primjer: Marković)</p>
									</div>
									<div class="form-group">
										<label>Korisničko Ime</label>
										<input class="form-control" type="text" name="username" maxlength="16" value="<?=@$user_data['username'];?>" required>
										<p class="help-block">Korisničko ime može sadržavati maksimalno 16 znakova. (primjer: ivan_markovic)</p>
									</div>
									<div class="form-group">
										<label>E-Mail</label>
										<input class="form-control" type="email" name="email" value="<?=@$user_data['email'];?>" required>
										<p class="help-block">(primjer: ivan.markovic@domena.hr)</p>
									</div>
									<div class="form-group">
										<label>Lozinka</label>
										<input class="form-control" type="password" name="password" value="" autofocus required>
										<p class="help-block">Lozinka mora sadržavati minimalno 8 znakova.</p>
									</div>	
									<div class="form-group">
										<label>Lozinka ponovo</label>
										<input class="form-control" type="password" name="password2" value="" required>
										<p class="help-block">Lozinka mora sadržavati minimalno 8 znakova.</p>
									</div>
									<div class="form-group">
										<label>Tip Racuna</label>
										<select class="form-control" name="permissions">
										<?php
											if(@$user_data['dozvole'] === '0') {
												$option0 = ' selected';
											}
											if(@$user_data['dozvole'] === '1') {
												$option1 = ' selected';
											}
										?>
											<option value="0"<?=@$option0;?>>Korisnik</option>
											<option value="1"<?=@$option1;?>>Administrator</option>
										</select>
									</div>
									<hr />
									<button type="submit" class="btn btn-default" name="<?=$html_data['button']['name'];?>" value="<?=$html_data['button']['value'];?>"><?=$html_data['button']['text'];?></button>
									<!-- <button type="reset" class="btn btn-default">Reset Button</button> -->
								</form>
							</div>
							<!-- /.col-lg-6 (nested) -->
							<div class="col-lg-5 pull-right">
								<div class="panel panel-default">
									<div class="panel-heading">
										Pomoć
									</div>
									<div class="panel-body">
										<dl>
											<dt>Ime</dt>
											<dd><pre>This is an example of preformatted text.</pre></dd>
											<dt>Prezime</dt>
											<dd><pre>This is an example of preformatted text.</pre></dd>
											<dt>Korisničko Ime</dt>
											<dd><pre>This is an example of preformatted text.</pre></dd>
										</dl>
									</div>
									<!-- /.panel-body -->
								</div>
							</div>
							<!-- /.col-lg-5 (nested) -->
						</div>
						<!-- /.row (nested) -->
					</div>
					<!-- /.panel-body -->
				</div>
				<!-- /.panel -->
			</div>
			<!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="<?=BASE_URL;?>js/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?=BASE_URL;?>js/bootstrap.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?=BASE_URL;?>js/metisMenu.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?=BASE_URL;?>js/sb-admin-2.js"></script>
	
	<!-- Edit HTML5 Required Message -->
	<script type="text/javascript">
	document.addEventListener("DOMContentLoaded", function() {
		var elements = document.getElementsByTagName("INPUT");
		for (var i = 0; i < elements.length; i++) {
			elements[i].oninvalid = function(e) {
				e.target.setCustomValidity("");
				if (!e.target.validity.valid) {
					e.target.setCustomValidity("Molimo ispunite polje.");
				}
			};
			elements[i].oninput = function(e) {
				e.target.setCustomValidity("");
			};
		}
	})
	</script>
</body>
</html>