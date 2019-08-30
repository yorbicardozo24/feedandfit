<?php
session_start();
	if(isset($_SESSION["usuario"])){
		require '../conexion.php';

		$query = "SELECT * FROM delivery_zones ORDER BY name ASC";
    	$resultado = pg_query($query);

		$resultados = array();

		while ($line = pg_fetch_array($resultado, null, PGSQL_ASSOC)) {
			$id = $line['id'];
			$nombre = ucfirst(strtolower($line['name']));

			array_push($resultados, ["id" => $id, "nombre" => $nombre]);
		}

		echo json_encode($resultados);
	} else { header("Location: ../../index"); }
?>