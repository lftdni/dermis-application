<?php

if(!$is_logged_in) {
	header('Location: /login'); exit;
}

// Include Classes
include('php/classes/validations.class.php');

// Load Classes
$validations = new Validations();

// HTML Info Podaci
$html_data = array(
	'title' 		=> 'Unos usluge - '.BRANDNAME,
	'description'	=> '',
	'author' 		=> 'Nives Miletić',
	'page_title'	=> 'Unos usluge',
	'button'		=> array(	'name' 	=> 'add_service',
								'value' => 'true',
								'text' 	=> 'Dodaj uslugu')
);

$service_data 	= array();
$messages		= array();

if(@$_POST['add_service'] === 'true' OR @$_POST['edit_service'] === 'true') {
	
	// Make service_data array and trim all $_POST values	
	foreach($_POST as $value => $key) {
		$service_data[$value] = trim($key);
 	}
	
	// Validate input values for service_data
	$messages = $validations->check_services($service_data);
			
	// IF empty messages(no errors) continue
	if(!$messages) {
		
		if(@$_POST['add_service'] === 'true') { // ADD SERVICE IN DATABASE
			
			// Add datetime and user_id to $service_data array
			$service_data['creation_datetime'] 		= $current_datetime;
			$service_data['user_id']				= $_SESSION['user_id'];
			
			// Remove key(s) from service_data to prepare for implode
			unset($service_data['add_service']);

			$add_service_query = 'INSERT INTO `dermis_app`.`services` (`'.implode('`, `', array_keys($service_data)).'`) VALUES (\''.implode('\', \'', $service_data).'\')';
			if($db->query($add_service_query)) {
				$messages[] = array('status' => 'success', 'message' => 'Usluga '.$service_data['title'].' je uspješno kreirana.');
			} else {
				$messages[] = array('status' => 'error', 'message' => 'Nešto nije u redu sa bazom podataka.');
			}
			
		} elseif(@$_POST['edit_service'] === 'true') { // UPDATE SERVICE IN DATABASE
			
			$service_id = $db->real_escape_string($service_data['service_id']);
			
			unset(
				$service_data['service_id'],
				$service_data['edit_service']
			);
			
			$sql_update_data = '';
			foreach($service_data as $key => $value) {
				if($value == end($service_data)) {
					$sql_update_data .= $key.'=\''.$value.'\' '; // Space at end
				} else {
					$sql_update_data .= $key.'=\''.$value.'\', '; // Space with comma on end
				}
			}
			
			$update_service_query = 'UPDATE `dermis_app`.`services` SET '.$sql_update_data.' WHERE id = '.$service_id.'';
			if($db->query($update_service_query)) {
				$messages[] = array('status' => 'success', 'message' => 'Usluga "'.$service_data['title'].'" je uspješno ažurirana.');
			} else {
				$messages[] = array('status' => 'error', 'message' => $db->error);
			}
			
		}

	}
	
}

// Delete Services
if(@$request_arr[1] === 'delete' AND !empty(@$request_arr[2])) {
	
	$service_id 				= $db->real_escape_string($request_arr[2]);
	$delete_service_query 		= 'DELETE FROM services WHERE id = \''.$service_id.'\'';

	if($db->query($delete_service_query)) {
		if($db->affected_rows === 1) {
			$messages[] = array('status' => 'success', 'message' => 'Usluga je uspješno obrisana.');
		} else {
			$messages[] = array('status' => 'error', 'message' => 'Usluga ne postoji.');
		}
	} else {
		$messages[] = array('status' => 'error', 'message' => 'Usluga nije obrisana. Nešto nije u redu sa bazom podataka.');
	}
	
}

// Edit Services
if(@$request_arr[1] === 'edit' AND !empty(@$request_arr[2])) {
	
	if(is_numeric($request_arr[2])) {
		
		$service_id 			= $db->real_escape_string($request_arr[2]);
		$service_data_query 	= 'SELECT * FROM services WHERE id = \''.$service_id.'\'';
		$service_data 			= $db->query($service_data_query);
		$service_data 			= $service_data->fetch_assoc();
					
	} else {
		header('Location: /services-add'); exit;
	}
	
	// HTML Page Data
	$html_data = array(
		'title' 		=> 'Unos usluge - '.BRANDNAME,
		'description'	=> '',
		'author' 		=> 'Nives Miletić',
		'page_title'	=> 'Uredi uslugu ('.$service_data['title'].')',
		'button'		=> array(	'name' 	=> 'edit_service',
									'value' => 'true',
									'text' 	=> 'Uredi uslugu')
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

    <!-- Morris Charts CSS -->
    <link href="<?=BASE_URL;?>css/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?=BASE_URL;?>css/font-awesome.css" rel="stylesheet" type="text/css">

</head>

<body>

    <div id="wrapper">

	<?php include('php/include/navigation.html'); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><i class="fa fa-gears fa-fw"></i> <?=$html_data['page_title'];?></h1>
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
						Ispunite informacije o usluzi
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-6">
								<form role="form" method="POST" accept-charset="UTF-8">
									<div class="form-group">
										<label for="title">Naziv usluge:</label>
										<input class="form-control" type="text" name="title" maxlength="32" value="<?=@$service_data['title'];?>" required>
										<p class="help-block">(primjer: Neki Kurac)</p>
									</div>
									<div class="form-group">
										<label for="duration">Trajanje usluge:</label>
										<input class="form-control" type="text" name="duration" maxlength="32" value="<?=@$service_data['duration'];?>" required>
										<p class="help-block">(Vremenska jedinica: 30.30 u decimalima(30m 30s))</p>
									</div>
									<div class="form-group">
										<label for="description">Opis usluge:</label>
										<textarea class="form-control" name="description" rows="5"><?=@$service_data['description'];?></textarea>
									</div>
									<hr />
									<?php
									if(@$request_arr[1] === 'edit') {
										echo '<input type="hidden" name="service_id" value="'.$request_arr[2].'">'."\r\n";
									}
									?>
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