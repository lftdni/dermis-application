<?php

var_dump($_SESSION);
var_dump($is_logged_in); 

// if($is_logged_in) {
	// header('Location: /home'); exit;
// }
 
// if(@$_POST['login'] === 'true') {
	
	// $username	= @trim($_POST['username']);
	// $password	= @trim($_POST['password']);
	
	// $messages = array();
	
	// if (!$username) {
		// $messages[] = array('status' => 'error', 'msg' => 'Molimo unesite vaše korisničko ime.');
	// }

	// if (!$password) {
		// $messages[] = array('status' => 'error', 'msg' => 'Molimo unesite vaš password.');
	// }
	
	// # 1 -> OK
	// # 2 -> UNKNOWN USERNAME
	// # 3 -> WRONG PASSWORD
	
	// if (!$messages) {
		// $status = $user->loginCreate($username, $password);
		
		// if ($status === 1) {
			// header('Location: /home'); 
			// exit; 
		// } else {
			// $messages[] = array('status' => 'error', 'msg' => 'Korisničko ime ili lozinka nisu točni.');
		// }
	// }
	
	// // var_dump($messages); exit;
// }

// HTML Info Podaci
$html_data = array(
	'title' 		=> 'Prijava - Dermis',
	'description'	=> '',
	'author' 		=> 'Nives Miletić'
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
	
    <div class="container">
	<?php if(@$messages) : ?>
	    <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Upozorenje!</h3>
                    </div>
                    <div class="panel-body">
						<p style="color: red;">
						<?php foreach($messages as $message) : ?>
						<?php echo $message['msg'].'<br />'; ?>
						<?php endforeach; ?>
						</p>
                    </div>
                </div>
            </div>
        </div>
	<?php endif; ?>
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Prijava</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="POST" action="/login">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Korisničko ime" name="username" type="text" autofocus required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Lozinka" name="password" type="password" value="" required>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Zapamti me!
                                    </label>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button class="btn btn-lg btn-success btn-block" type="submit" name="login" value="true">Prijavi se</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
