<?php

if(!$is_logged_in) {
	header('Location: /login'); exit;
}

// Get USERS Data
$user_data_query = '
		SELECT
		id,
		first_name,
		last_name,
		username,
		email,
		permissions,
		creation_datetime
		FROM users
		ORDER BY last_name ASC';
$user_data = $db->query($user_data_query);
$user_data = $user_data->fetch_all(MYSQLI_ASSOC);

// HTML Info Podaci
$html_data = array(
	'title' 		=> 'Pregled Korisnika - '.BRANDNAME,
	'description'	=> '',
	'author' 		=> 'Nives Miletić',
	'page_title'	=> 'Pregled Korisnika'
);
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

	<!-- DataTables CSS -->
    <link href="<?=BASE_URL;?>css/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="<?=BASE_URL;?>css/dataTables.responsive.css" rel="stylesheet">

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
                    <h1 class="page-header"><i class="fa fa-user fa-fw"></i> <?=$html_data['page_title'];?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?=$html_data['page_title'];?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="pregled_korisnika">
                                    <thead>
                                        <tr>
											<th>Ime</th>
                                            <th>Prezime</th>
                                            <th>Korisničko Ime</th>
                                            <th>E-Mail</th>
                                            <th>Tip Računa</th>
                                            <th>Datum Registracije</th>
                                            <th>Uredi korisnika</th>
                                            <th>Obriši korisnika</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php foreach ($user_data as $key => $value) : ?>
									<?php
										@$i++;
										if($value['permissions'] === '0') {
											$acc_type = 'Korisnik';
										} else {
											$acc_type = 'Administrator';
										}
									?>
                                        <tr class="<?=($i%2) ? 'even':'odd';?> gradeX">
											<td><?=$value['first_name'];?></td>
                                            <td><?=$value['last_name'];?></td>
                                            <td><?=$value['username'];?></td>
                                            <td><?=$value['email'];?></td>
                                            <td><?=$acc_type;?></td>
                                            <td><?=$value['creation_datetime'];?></td>
                                            <td><a href="/users-add/edit/<?=$value['id'];?>">Uredi</a></td>
                                            <td><a href="/users-add/delete/<?=$value['id'];?>">Obriši</a></td>
                                        </tr>
									<?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                            <div class="well">
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
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
	
	<!-- DataTables JavaScript -->
    <script src="<?=BASE_URL;?>js/jquery.dataTables.js"></script>
    <script src="<?=BASE_URL;?>js/dataTables.bootstrap.js"></script>
	
    <!-- Custom Theme JavaScript -->
    <script src="<?=BASE_URL;?>js/sb-admin-2.js"></script>
	
	<script>
	$(document).ready(function(){
		$('#pregled_korisnika').DataTable();
	});
	</script>
</body>
</html>