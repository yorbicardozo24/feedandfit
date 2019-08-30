<?php 
	session_start();
	if(isset($_SESSION["usuario"])){
		require '../conexion.php';

		$id = $_GET['id'];

		$sql = "DELETE FROM addresses WHERE delivery_zone_id = '$id'";
		$query = pg_query($sql);

		$sql = "DELETE FROM delivery_zones WHERE id = '$id'";
		$query = pg_query($sql);

      	if($query){ 
		  	header("Location: ../../zonas");
    	}else{ 
      		header("Location: ../../zonas");
    	}

	} else { header("Location: ../../index"); }

?>