<?php
	session_start();

  	if(isset($_SESSION["usuario"])){

	require 'conexion.php';
	require 'fun.php';
	// Eliminar todas las sesiones:
	session_destroy();
	header("Location: ../index");

	} else { header("Location: ../index"); }

?>