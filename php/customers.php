<?php

if(!$is_logged_in) {
	header('Location: /login'); exit;
}

// Get customers data
$customers_data_query = '
	SELECT
	customers.id,
	customers.first_name,
	customers.last_name,
	customers.email,
	customers.mobile_phone,
	users.username,
	users.id AS user_id
	FROM customers, users
	WHERE customers.user_id = users.id
	ORDER BY last_name ASC';

$customers_data = $db->query($customers_data_query);
$customers_data = $customers_data->fetch_all(MYSQLI_ASSOC);

// HTML Info Podaci
$html_data = array(
	'title' 		=> 'Pregled klijenata - '.BRANDNAME,
	'description'	=> '',
	'author' 		=> 'Nives Miletić',
	'page_title'	=> 'Pregled klijenata'
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
                    <h1 class="page-header"><i class="fa fa-female fa-fw"></i> <?=$html_data['page_title'];?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Pregled klijenata
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="pregled_korisnika">
                                    <thead>
                                        <tr>
											<th>Ime</th>
                                            <th>Prezime</th>
                                            <th>E-Mail</th>
                                            <th class="text-center">Mobitel</th>
                                            <th class="text-center">Uredi korisnika</th>
                                            <th class="text-center">Obriši korisnika</th>
                                            <th class="text-center">Dodao</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php foreach ($customers_data as $key => $value) : ?>
                                        <tr class="<?=($i%2) ? 'even':'odd';?> gradeX">
											<td><?=$value['first_name'];?></td>
                                            <td><?=$value['last_name'];?></td>
                                            <td><?=$value['email'];?></td>
                                            <td class="text-center"><?=$value['mobile_phone'];?></td>
                                            <td class="text-center"><a href="/customers-add/edit/<?=$value['id'];?>"><i class="fa fa-edit fa-fw"></i>Uredi</a></td>
                                            <td class="text-center"><a href="/customers-add/delete/<?=$value['id'];?>"><i class="fa fa-ban fa-fw"></i>Obriši</a></td>
                                            <td class="text-center"><a href="/users/edit/<?=$value['user_id'];?>"><i class="fa fa-user fa-fw"></i> <?=$value['username'];?></td>
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