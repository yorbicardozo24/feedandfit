<?php 
	session_start();
	if(isset($_SESSION["usuario"])){
		require '../conexion.php';

		$id = $_GET['id'];

		$sql = "SELECT * FROM products WHERE category_id = '$id'";
		$query = pg_query($sql);

		while ($line = pg_fetch_array($query, null, PGSQL_ASSOC)) {
			$id_product = $line['id'];

			$sql = "DELETE FROM sizes WHERE product_id = '$id_product'";
			$query = pg_query($sql);

			$sql = "DELETE FROM targets WHERE product_id = '$id_product'";
			$query = pg_query($sql);

		}

		$sql = "DELETE FROM products WHERE category_id = '$id'";
		$query = pg_query($sql);

		$sql = "DELETE FROM categories WHERE id = '$id'";
		$query = pg_query($sql);
		

      	if($query){ 
		  	header("Location: ../../categorias");
    	}else{ 
      		header("Location: ../../categorias");
    	}

	} else { header("Location: ../../index"); }

?>