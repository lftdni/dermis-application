<?php

if(!$is_logged_in) {
	header('Location: /login'); exit;
}

// Stats (customers, users, services)
// Query
$stats_data_query = '
	SELECT 
	(SELECT COUNT(id) FROM services) AS total_services,
	(SELECT COUNT(id) FROM customers) AS total_customers,
	(SELECT COUNT(id) FROM users) AS total_users
	';
	
// Connection to DB with Query
$stats_data = $db->query($stats_data_query);
// IF Connection passed pick up data in array $stats_data[0]['total_recepti']; - to dump data var_dump($stats_data);
$stats_data = $stats_data->fetch_all(MYSQLI_ASSOC);


// Birthdays
$birthdays_query = 'SELECT * FROM customers ORDER BY birthdate ASC LIMIT 5';
//$birthdays_query = 'SELECT *, EXTRACT(MONTH FROM birthdate) AS OrderMonth, EXTRACT(DAY FROM birthdate) AS OrderDay FROM customers ORDER BY OrderDay ASC, OrderMonth DESC LIMIT 5';
$birthdays_data = $db->query($birthdays_query);
$birthdays_data = $birthdays_data->fetch_all(MYSQLI_ASSOC);

foreach($birthdays_data as $k => $v) {
	$birthdays_data[$k]['days_left'] = remaning_days($v['birthdate']);
}

// Sort array by remaning days
function orderDaysLeft($a, $b) {
	return $a['days_left'] - $b['days_left'];
}
usort($birthdays_data, 'orderDaysLeft');

function remaning_days($date) {
	
	// get date of birthday this calendar year
	$parts 			= explode('-', $date, 2);
	$birth_date	 	= new DateTime(date('Y') . '-' . $parts[1] .' 00:00:00');
	$today 			= new DateTime('midnight today');

	if($birth_date < $today) {
		// next birthday is in one year
		$birth_date->modify('+1 Year'); 
	}

	// get number of days days remaining
	$diff = $birth_date->diff($today);

	if($diff->days > 0) {
		return (int)$diff->days;
	} else {
		return 0;
	}

}

// HTML Info Podaci
$html_data = array(
	'title' 		=> 'Administracija - Dermis',
	'description'	=> '',
	'author' 		=> 'Nives Miletić',
	'page_title'	=> 'Administracija'
);

?>
<!DOCTYPE html>
<html lang="hr">
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
                    <h1 class="page-header"><?=$html_data['page_title'];?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-book fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?=@$stats_data[0]['total_recepti'];?></div>
                                    <div>Ukupno Termina</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">Pregled termina</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-gears fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?=@$stats_data[0]['total_services'];?></div>
                                    <div>Ukupno Usluga</div>
                                </div>
                            </div>
                        </div>
                        <a href="/services">
                            <div class="panel-footer">
                                <span class="pull-left">Popis usluga</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-female fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?=$stats_data[0]['total_customers'];?></div>
                                    <div>Ukupno Klijenata</div>
                                </div>
                            </div>
                        </div>
                        <a href="/customers">
                            <div class="panel-footer">
                                <span class="pull-left">Popis klijenata</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-user fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?=$stats_data[0]['total_users'];?></div>
                                    <div>Ukupno Korisnika</div>
                                </div>
                            </div>
                        </div>
                        <a href="/users">
                            <div class="panel-footer">
                                <span class="pull-left">Popis korisnika</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.row -->
			<div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Nadolazeći rođendani
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Ime</th>
                                            <th class="text-center">Prezime</th>
                                            <th class="text-center">Godina</th>
                                            <th>Rođendan</th>
											<th class="text-center">Čestitka</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php $cb=1;?>
									<?php foreach($birthdays_data as $birthday) : ?>
                                        <tr>
                                            <td class="text-center"><?=$cb++;?></td>
                                            <td class="text-center"><?=$birthday['first_name'];?></td>
                                            <td class="text-center"><?=$birthday['last_name'];?></td>
                                            <td class="text-center">(<?=date_diff(date_create($birthday['birthdate']), date_create('today'))->y;?>)</td>
                                            <td>Preostalo dana <strong><?=$birthday['days_left'];?></strong> do rođendana.</td>
											<?php if($birthday['days_left'] > 0) : ?>
											<td class="text-center"><fieldset disabled=""><button type="submit" class="btn btn-success btn-sm">Pošalji čestitku</button></fieldset></td>
											<?php else: ?>
											<td class="text-center"><fieldset><button type="submit" class="btn btn-success btn-sm">Pošalji čestitku</button></fieldset></td>
											<?php endif; ?>
                                        </tr>
									<?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
				<div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Nadolazeći rođendani
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Ime</th>
                                            <th class="text-center">Prezime</th>
                                            <th class="text-center">Godina</th>
                                            <th>Rođendan</th>
											<th class="text-center">Čestitka</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php $cb=1;?>
									<?php foreach($birthdays_data as $birthday) : ?>
                                        <tr>
                                            <td class="text-center"><?=$cb++;?></td>
                                            <td class="text-center"><?=$birthday['first_name'];?></td>
                                            <td class="text-center"><?=$birthday['last_name'];?></td>
                                            <td class="text-center">(<?=date_diff(date_create($birthday['birthdate']), date_create('today'))->y;?>)</td>
                                            <td>Preostalo dana <strong><?=$birthday['days_left'];?></strong> do rođendana.</td>
											<?php if($birthday['days_left'] > 0) : ?>
											<td class="text-center"><fieldset disabled=""><button type="submit" class="btn btn-success btn-sm">Pošalji čestitku</button></fieldset></td>
											<?php else: ?>
											<td class="text-center"><fieldset><button type="submit" class="btn btn-success btn-sm">Pošalji čestitku</button></fieldset></td>
											<?php endif; ?>
                                        </tr>
									<?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
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

</body>
</html>