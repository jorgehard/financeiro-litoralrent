<?php 
	session_start();
	include("config.php");
	$con = new DBConnection();
	if((isset($_SESSION['login_usuario']) && isset($_SESSION['senha_usuario'])) || (isset($_COOKIE['user']) && isset($_COOKIE['password']))){
		echo "<script>window.location='restrito/';</script>";
	}
	if(isset($_POST["submit"])) {
		$user = preg_replace('/[^[:alnum:]_\-@.]/', '',$_POST['user']);
		$password = preg_replace('/[^[:alnum:]_]/', '',$_POST['password']);
		$md5password = md5($password);
		$stat = $con->prepare("SELECT * FROM usuarios WHERE login = ? AND senha = ?");
		$stat->execute(array($user,$md5password));
		$count = $stat->rowCount();
		if ($count == "1")
		{
			$row = $stat->fetch(PDO::FETCH_ASSOC);
			$_SESSION['id_usuario'] = $row['id'];
			$_SESSION['login_usuario'] = $user;
			$_SESSION['senha_usuario'] = $md5password;
			
			if(isset($_POST["autologin"]))
			{
				$cookie_time = (10 * 365 * 24 * 60 * 60);
				setcookie ('user', $user, time() + $cookie_time);
				setcookie ('password', $md5password, time() + $cookie_time);
			}
			
			echo "<script>window.location='restrito/';</script>";
		}else{
			echo '	
			<div class="container" style="max-width: 600px; text-align:center; margin-top:20px; margin-bottom:auto; opacity:0.9">
				<div class="alert alert-danger">
					<strong>Login inválido!</strong> Senha ou Login incorretos, tente novamente!.
				</div>
			</div>';
		}
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>LitoralRent - Sistema Administrativo</title>
    <meta name="author" content="jorgehenrique@live.com">
	<link rel="icon" href="style/img/icone-litoralrent.ico" type="image/x-icon"/>
	<link rel="shortcut icon" href="style/img/imagens/icone-litoralrent.ico" type="image/x-icon"/>
	<link rel="stylesheet" href="style/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="style/css/AdminLTE.min.css">
	<link rel="stylesheet" href="plugins/iCheck/square/green.css">
	<link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Ubuntu:500' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="style/css/login.css?v5">
</head>
<body class="wrapper">
	<div class="background-login"></div>
	<div class="container-fluid">
		<div class="form-signin-2">
			<div class="formHeader">
				<h2 class="form-signin-heading-2"><center><img src="style/img/litoralrent-logo.png" alt="Logo Empresa" class="img-responsive" width="40%"/></center></h2>
			</div>
			<fieldset>
				<form method="post">
					<div>
						<div class="form-group">
							<input class="form-control input-sm" type="text" name="user" placeholder="Usuario" required autofocus/>
						</div>
						<div class="form-group">
							<input class="form-control input-sm" type="password" name="password" placeholder="Senha" required/>
						</div>          
						<div class="form-group remember-me">
							<label class="container">
								<input type="checkbox" class="lembrar" value="1" name="autologin"/> 
								<span class="checkmark"></span>
								<span class="titulo">
								Lembrar senha</span>
							</label>
						</div>
					</div>
					<div class="footer">                                                               
						<input class="btn btn-block btn-success" type="submit" name="submit" value="Entrar"/>
					</div>
				</form>
			</fieldset>

		</div>

	</div>
	  <footer class="pull-right" style="position:fixed; bottom:20px; text-align:center; width:100%; opacity:0.5; font-size:11px; color:#f3f3f3; letter-spacing:1.5px">
		<strong>AtlasWare Soluções Tecnologicas | Todos direitos reservados.  Copyright &copy; 2018 </strong>
	  </footer>

    <script src="plugins/jquery/dist/jquery.min.js"></script>
	<script src="plugins/bootstrap/dist/js/bootstrap.min.js"></script>
	
	<script src="plugins/iCheck/icheck.min.js"></script>
	
	<script>
	  $(function () {
		$('.lembrar').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green',
			increaseArea: '20%' // op/tional
		});
	  });
	</script>
</body>
</html>
