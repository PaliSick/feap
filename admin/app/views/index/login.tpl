<!DOCTYPE HTML>
<html lang="es-ES">
<head>
	<meta charset="UTF-8">
	<title>Login</title>

	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/login.css" type="text/css" charset="utf-8">
	<style type="text/css" media="screen">
		#logo{
			background: url('../img/feap-logo-admin.png') no-repeat scroll top left;
			width: 198px;
			height: 119px;
		}
	</style>
	
	
	<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
	
</head>
<body>
	<div class="content-wrapper">
		<div id="logo"></div>
		<div class="login-holder">
			<form action="index/login-submit" method="post" accept-charset="utf-8">
				<label for="user">Usuario:</label><input type="text" name="user" id="user">
				<label for="user">Contraseña:</label><input type="password" name="pass" id="pass">

				<input type="submit" name="submit" id="submit" value="Enviar">
			</form>
		</div>
	</div>
</body>
</html>