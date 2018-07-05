<?php

if(!$is_logged_in) {
	header('Location: /login'); exit;
}

// Includes
include('php/classes/functions.php');
include('php/classes/validations.class.php');

// Loading Classes
$validations = new Validations();

// Main HTML Data
$html_data = array(
	'title' 		=> 'Unos klijenta - '.BRANDNAME,
	'description'	=> '',
	'author' 		=> 'Nives Miletić',
	'page_title'	=> 'Unos klijenta',
	'button'		=> array(
								'name' => 'add_costumer',
								'value' => 'true',
								'text' => 'Dodaj klijenta')
);

if(@$_POST['add_costumer'] === 'true' OR @$_POST['edit_customers'] === 'true') {
	
	// Make costumers_data array with trimed values
	$costumer_data = array();
	foreach($_POST as $value => $key) {
		$costumer_data[$value] = trim($key);
 	}
	
	$messages = array();
	
	// Check if name has lower then 32 chars
	if($error = $validations->first_name($costumer_data['first_name'])) {
		$messages[] = $error;
	}	
			
	// Check if surname has lower then 32 chars
	if($error = $validations->last_name($costumer_data['last_name'])) {
		$messages[] = $error;
	}
	
	// Check if birthday values are entered (day, month, year)
	if($error = $validations->birth_day(array($costumer_data['birth_day'], $costumer_data['birth_month'], $costumer_data['birth_year']))) {
		$messages[] = $error;
	}
		
	// Check if email is not empty and validate email address
	if($error = $validations->email($costumer_data['email'])) {
		$messages[] = $error;
	}	
		
	// Check if telephone number contains only numbers
	if($costumer_data['telephone']) {
		if($error = $validations->phone($costumer_data['telephone'])) {
			$messages[] = $error;
		}
	}
	
	// Check if telephone number contains only numbers
	if($costumer_data['mobile_phone']) {
		if($error = $validations->phone($costumer_data['mobile_phone'])) {
			$messages[] = $error;
		}
	}
	
	// IF empty messages(no errors) continue
	if(!$messages) {
		
		// Convert birth day,month,year into mysql formated date and unset add_costumer html vaildation
		$costumer_data['birthdate'] = $costumer_data['birth_year'].'-'.$costumer_data['birth_month'].'-'.$costumer_data['birth_day'];
		unset(
			$costumer_data['birth_day'],
			$costumer_data['birth_month'],
			$costumer_data['birth_year'],
			$costumer_data['add_costumer']
		);
		
		$costumer_data['user_id'] = $_SESSION['user_id'];
		
		if(@$_POST['add_costumer'] === 'true') { // ADD COSTUMER IN DATABASE
			
			$add_costumer_query = 'INSERT INTO `dermis_app`.`customers` (`'.implode('`, `', array_keys($costumer_data)).'`) VALUES (\''.implode('\', \'', $costumer_data).'\')';
			if($db->query($add_costumer_query)) {
				$array_message = 'Klijent '.$costumer_data['first_name'].' je uspješno unesen u bazu podataka.';
				$messages[] = array('status' => 'success', 'message' => $array_message);
			} else {
				$messages[] = array('status' => 'error', 'message' => $db->error);
			}
			
		} elseif (@$_POST['edit_customers'] === 'true') { // UPDATE COSTUMER IN DATABASE
			
			$customer_id = $costumer_data['customer_id'];
			unset(
				$costumer_data['customer_id'],
				$costumer_data['edit_customers']
				);
			
			$sql_update_data = '';
			$last_key = key(array_slice($costumer_data, -1, 1, TRUE )); // GETTING NAME OF LAST KEY IN costumer_data array
			foreach($costumer_data as $key => $value) {		
				if($key == $last_key) {
					$sql_update_data .= $key.'=\''.$db->real_escape_string($value).'\' '; // Space at end
				} else {
					$sql_update_data .= $key.'=\''.$db->real_escape_string($value).'\', '; // Space with comma on end
				}
			}
			
			$update_costumer_query = 'UPDATE `dermis_app`.`customers` SET '.$sql_update_data.' WHERE id = '.$customer_id.'';
			if($db->query($update_costumer_query)) {
				$array_message = 'Klijent '.$costumer_data['first_name'].' je uspješno ažuriran u bazi podataka.';
				$messages[] = array('status' => 'success', 'message' => $array_message);
			} else {
				$messages[] = array('status' => 'error', 'message' => $db->error);
			}
			
		}

	}
	
}

// Delete Costumers
if(@$request_arr[1] === 'delete' AND !empty(@$request_arr[2])) {
	
	if(is_numeric($request_arr[2])) {
		
		$costumer_id = $request_arr[2];
		$delete_costumer_query = 'DELETE FROM customers WHERE id = \''.$costumer_id.'\'';

		if($db->query($delete_costumer_query)) {
			if($db->affected_rows === 1) {
				$messages[] = array('status' => 'success', 'message' => 'Klijent je uspješno obrisan.');
			} else {
				$messages[] = array('status' => 'error', 'message' => 'Klijent ne postoji.');
			}
		} else {
			$messages[] = array('status' => 'error', 'message' => 'Klijent nije obrisan. Nešto nije u redu sa bazom podataka.');
		}
	} else {
		header('Location: /customers-add'); exit;
	}

}

// Edit Costumers
if(@$request_arr[1] === 'edit' AND !empty(@$request_arr[2])) {
	
	// Fetch costumer data from database by id
	$costumer_id = $db->real_escape_string($request_arr[2]);
	$costumer_data_query = '
			SELECT * ,
			DATE_FORMAT(birthdate,\'%d\') AS birth_day,
			DATE_FORMAT(birthdate,\'%m\') AS birth_month,
			DATE_FORMAT(birthdate,\'%Y\') AS birth_year
			FROM customers WHERE id = \''.$costumer_id.'\'';

	$costumer_data = $db->query($costumer_data_query);
	if(!$costumer_data = $costumer_data->fetch_assoc()) {
		header('Location: /customers-add'); exit;
	}
	
	// HTML Data for EDIT
	$html_data = array(
		'title' 		=> 'Uredi klijenta - '.BRANDNAME,
		'description'	=> '',
		'author' 		=> 'Nives Miletić',
		'page_title'	=> 'Uredi klijenta ('.$costumer_data['first_name'].')',
		'button'		=> array('name' => 'edit_customers', 'value' => 'true', 'text' => 'Izmjeni')
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
                    <h1 class="page-header"><i class="fa fa-female fa-fw"></i> <?=$html_data['page_title'];?></h1>
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
						Osobni podaci klijenta
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-6">
								<form role="form" method="POST" accept-charset="UTF-8">
									<div class="form-group">
										<div class="form-inline">
											<div class="input-group col-xs-3">
												<span class="input-group-addon" style="min-width: 100px;">Ime</span>
												<input class="form-control" type="text" name="first_name" maxlength="32" placeholder="Ime" value="<?=@$costumer_data['first_name'];?>" autofocus required>
											</div>
											&nbsp;&nbsp;
											<div class="input-group col-xs-3">
												<span class="input-group-addon" style="min-width: 100px;">Prezime</span>
												<input class="form-control" type="text" name="last_name" maxlength="32" placeholder="Prezime" value="<?=@$costumer_data['last_name'];?>" required>
											</div>
											&nbsp;&nbsp;
											<div class="input-group">
												<span class="input-group-addon" style="min-width: 100px;">Datum Rođenja</span>
												<div class="form-inline">
													<div class="form-group">
														<select class="form-control" name="birth_day" required>
															<option value="" selected>Dan</option>
															<?php
															foreach (range(1, 31) as $day) {
																if($day == @$costumer_data['birth_day']) {
																	$selected = ' selected';
																} else {
																	$selected = '';
																}
																echo '<option'.$selected.' value='.$day.'>'.$day.'</option>';
															}
															?>
														</select>
													</div>
													<div class="form-group">
														<select class="form-control" name="birth_month" required>
															<option value="" selected>Mjesec</option>
															<?php
															foreach (range(1, 12) as $month) {
																if($month == @$costumer_data['birth_month']) {
																	$selected = ' selected';
																} else {
																	$selected = '';
																}
																echo '<option'.$selected.' value='.$month.'>'.$month.'</option>';
															}
															?>
														</select>
													</div>
													<div class="form-group">
														<select class="form-control" name="birth_year" required>
															<option value="" selected>Godina</option>
															<?php
															foreach (range(date('Y'), 1920) as $year) {
																if($year == @$costumer_data['birth_year']) {
																	$selected = ' selected';
																} else {
																	$selected = '';
																}
																echo '<option'.$selected.' value='.$year.'>'.$year.'</option>';
															}
															?>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
									<hr />
									<div class="form-group">
										<div class="form-inline">
											<div class="input-group col-xs-3">
												<span class="input-group-addon" style="min-width: 100px;">E-Mail</span>
												<input class="form-control" type="email" name="email" maxlength="255" placeholder="E-Mail" value="<?=@$costumer_data['email'];?>">
											</div>
											&nbsp;&nbsp;
											<div class="input-group col-xs-3">
												<span class="input-group-addon" style="min-width: 100px;">Telefon</span>
												<input class="form-control" type="tel" name="telephone" maxlength="255" placeholder="Telefon" value="<?=@$costumer_data['telephone'];?>">
											</div>
											&nbsp;&nbsp;
											<div class="input-group col-xs-3">
												<span class="input-group-addon" style="min-width: 100px;">Mobitel</span>
												<input class="form-control" type="tel" name="mobile_phone" maxlength="255" placeholder="Mobitel" value="<?=@$costumer_data['mobile_phone'];?>" required>
											</div>
										</div>
									</div>
									<hr />
									<div class="form-group">
										<div class="form-inline">
											<div class="input-group col-xs-3">
												<span class="input-group-addon" style="min-width: 100px;">Adresa</span>
												<input class="form-control" type="text" name="address" maxlength="255" placeholder="Adresa" value="<?=@$costumer_data['address'];?>">
											</div>
											&nbsp;&nbsp;
											<div class="input-group col-xs-3">
												<span class="input-group-addon" style="min-width: 100px;">Grad</span>
												<input class="form-control" type="text" name="city" maxlength="255" placeholder="Grad" value="<?=@$costumer_data['city'];?>" required>
											</div>
											&nbsp;&nbsp;
											<div class="input-group col-xs-3">
												<span class="input-group-addon" style="min-width: 100px;">Poštanski broj</span>
												<input class="form-control" type="text" name="postal_code" maxlength="255" placeholder="Poštanski broj" value="<?=@$costumer_data['postal_code'];?>" required>
											</div>
										</div>
									</div>
									<hr />
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon" style="min-width: 150px;">Alergije</span>
											<input class="form-control" type="text" name="allergies" maxlength="32" placeholder="Alergije" value="<?=@$costumer_data['allergies'];?>" required>
										</div>
									</div>
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon" style="min-width: 150px;">Kronične bolesti</span>
											<input class="form-control" type="text" name="chronic_diseases" maxlength="32" placeholder="Kronične bolesti" value="<?=@$costumer_data['chronic_diseases'];?>" required>
										</div>
									</div>
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon" style="min-width: 150px;">Terapija</span>
											<input class="form-control" type="text" name="therapy" maxlength="32" placeholder="Terapija" value="<?=@$costumer_data['therapy'];?>" required>
										</div>
									</div>
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon" style="min-width: 150px;">Obiteljske sklonosti</span>
											<input class="form-control" type="text" name="family_preferences" maxlength="32" placeholder="Obiteljske sklonosti" value="<?=@$costumer_data['family_preferences'];?>" required>
										</div>
									</div>
									 <div class="form-group">
										<div class="input-group">
											<span class="input-group-addon" style="min-width: 150px;">Tip menstruacije</span>
											<div class="form-inline">
												<div class="form-group">
													<select class="form-control" name="menstruation_type">
														<option <?php if (@$costumer_data['menstruation_type'] == 'poorly' ) echo 'selected'; ?> value="poorly">Slabo krvarenje</option>
														<option <?php if (@$costumer_data['menstruation_type'] == 'strong' ) echo 'selected'; ?> value="strong">Jako krvarenje</option>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon" style="min-width: 150px;">Tip prehrane</span>
											<div class="form-inline">
												<div class="form-group">
													<select class="form-control" name="nutrition_type">
														<option <?php if (@$costumer_data['nutrition_type'] == 'mixed' ) echo 'selected'; ?> value="mixed">Mješoviti</option>
														<option <?php if (@$costumer_data['nutrition_type'] == 'veget' ) echo 'selected'; ?> value="veget">Vegetarijanski</option>
														<option <?php if (@$costumer_data['nutrition_type'] == 'carbo' ) echo 'selected'; ?> value="carbo">Ugljikohidratni</option>
														<option <?php if (@$costumer_data['nutrition_type'] == 'prote' ) echo 'selected'; ?> value="prote">Proteinski</option>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon" style="min-width: 150px;">Pušač</span>
											<div class="form-inline">
												<div class="form-group">
													<select class="form-control" name="smoker">
														<option <?php if (@$costumer_data['smoker'] == '0' ) echo 'selected'; ?> value="0" selected>Ne</option>
														<option <?php if (@$costumer_data['smoker'] == '1' ) echo 'selected'; ?> value="1">Da</option>
													</select>
												</div>
											</div>
										</div>
									</div>
									<hr />
									<div class="form-group">
										<label for="remark">Opaska:</label>
										<textarea class="form-control" name="remark" rows="5"><?=@$costumer_data['remark'];?></textarea>
									</div>
									<hr />
									<?php
									if(@$request_arr[1] === 'edit') {
										echo '<input type="hidden" name="customer_id" value="'.$request_arr[2].'">'."\r\n";
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
											<dd><pre>Primjer 1: Ivan <br />Primjer 2: Ivan - Marko</pre></dd>
											<dt>Prezime</dt>
											<dd><pre>Primjer 1: Marković <br />Primjer 2: Marković - Perković</pre></dd>
											<dt>Datum Rođenja</dt>
											<dd><pre>Primjer: 05 12 1970</pre></dd>
											<dt>E-Mail</dt>
											<dd><pre>Primjer: ivan.markovic@domena.com</pre></dd>
											<dt>Telefon</dt>
											<dd><pre>Primjer: 051202202</pre></dd>
											<dt>Mobitel</dt>
											<dd><pre>Primjer: 0915252552</pre></dd>
											<dt>Adresa</dt>
											<dd><pre>Primjer: Šampionska Ulica 12</pre></dd>
											<dt>Grad</dt>
											<dd><pre>Primjer: Rijeka</pre></dd>
											<dt>Poštanski broj</dt>
											<dd><pre>Primjer: 51000</pre></dd>
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
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>

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