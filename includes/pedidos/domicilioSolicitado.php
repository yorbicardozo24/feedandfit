<?php
session_start();
if(isset($_SESSION["usuario"])){
    require '../conexion.php';

	$id = $_POST['data'];
	$recargar = 0;
	if($id){
		$sql = "SELECT * FROM orders WHERE id = '$id'";
		$query = pg_query($sql);
		$fila = pg_fetch_array($query, null, PGSQL_ASSOC);

		$estado = $fila['status'];

		if($estado == '1'){
			$sql = "UPDATE orders SET status = 3 WHERE id = '$id'";
        	$resultado = pg_query($sql);
        	
        	$recargar = 1;
		}

		echo $recargar;
	}

} else { header("Location: ../../index"); }
?>