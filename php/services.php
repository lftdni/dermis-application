<?php

if(!$is_logged_in) {
	header('Location: /login'); exit;
}

// Get services data
$services_data_query = '
	SELECT
	services.id,
	services.title,
	services.duration,
	services.description,
	users.id AS user_id,
	users.username
	FROM services, users
	WHERE services.user_id = users.id
	ORDER BY services.id ASC';

$services_data = $db->query($services_data_query);
$services_data = $services_data->fetch_all(MYSQLI_ASSOC);

// HTML Info Podaci
$html_data = array(
	'title' 		=> 'Pregled usluga - '.BRANDNAME,
	'description'	=> '',
	'author' 		=> 'Nives Miletić',
	'page_title'	=> 'Pregled usluga'
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
                    <h1 class="page-header"><i class="fa fa-gears fa-fw"></i> <?=$html_data['page_title'];?></h1>
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
											<th class="text-center">Br</th>
                                            <th class="text-center">Usluga</th>
                                            <th class="text-center">Trajanje</th>
                                            <th>Opis</th>
                                            <th class="text-center">Dodao</th>
                                            <th class="text-center">Uredi uslugu</th>
                                            <th class="text-center">Obriši uslugu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php $sc=1;?>
									<?php foreach ($services_data as $key => $value) : ?>
                                        <tr class="<?=($i%2) ? 'even':'odd';?> gradeX">
											<td class="text-center"><?=$sc++;?></td>
											<td class="text-center"><?=$value['title'];?></td>
                                            <td class="text-center"><?=$value['duration'];?></td>
                                            <td><?=$value['description'];?></td>
                                            <td class="text-center"><a href="/users/edit/<?=$value['user_id'];?>"><i class="fa fa-user fa-fw"></i> <?=$value['username'];?></td>
											<td class="text-center"><a href="/services-add/edit/<?=$value['id'];?>"><i class="fa fa-edit fa-fw"></i>Uredi</a></td>
                                            <td class="text-center"><a href="/services-add/delete/<?=$value['id'];?>"><i class="fa fa-ban fa-fw"></i>Obriši</a></td>
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