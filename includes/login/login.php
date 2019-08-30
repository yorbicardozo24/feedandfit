<?php
	session_start();

	if(isset($_SESSION["usuario"])){
		header("Location: pedidos");
	} else {

	require '../conexion.php';
	require '../fun.php';

	$username = $_POST['usuario'];
	$password = $_POST['password'];

	if ($username == '' or $password == '') {
		echo "<script type=\"text/javascript\">alert('El nombre de usuario o contraseña es incorrecta, por favor intente de nuevo'); window.location.href='../../index';</script>";
	}else{

	$sql = "SELECT * FROM admins WHERE email = '$username'";
	$resultado = pg_query($sql);
	$fila = pg_fetch_array($resultado, null, PGSQL_ASSOC);
	
	if($username == $fila['email'] AND password_verify($password, $fila['encrypted_password'])) {
		$usernamei = $fila['email'];
		$nombrei = $fila['name'];
		$_SESSION["nombre"] = $nombrei;
		$_SESSION["usuario"] = $usernamei;
		
		echo "<script type=\"text/javascript\">window.location.href='../../pedidos';</script>";
	} else {
		echo "<script type=\"text/javascript\">alert('El nombre de usuario o contraseña es incorrecta, por favor intente de nuevo'); window.location.href='../../index';</script>";
	}
	}

	  }
?>