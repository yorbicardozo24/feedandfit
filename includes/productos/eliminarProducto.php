<?php 
	session_start();
	if(isset($_SESSION["usuario"])){
		require '../conexion.php';

		$id = $_GET['id'];

		$query = "DELETE FROM additions WHERE product_id = '$id'";
		$result = pg_query($query);

		$query = "DELETE FROM sizes WHERE product_id = '$id'";
		$result = pg_query($query);

		$query = "DELETE FROM targets WHERE product_id = '$id'";
		$result = pg_query($query);

		$sql = "DELETE FROM products WHERE id = '$id'";
      	$resultado = pg_query($sql);

      	if($resultado){ 
		  	header("Location: ../../productos");
    	}else{ 
      		header("Location: ../../productos");
    	}

	} else { header("Location: ../../index"); }

?>