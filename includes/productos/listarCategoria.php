<?php 
session_start();
	if(isset($_SESSION["usuario"])){
		require '../conexion.php';
		$sql = "SELECT * FROM categories ORDER BY id ASC";
		$query = pg_query($sql);

		$categorias = array();
		while ($line = pg_fetch_array($query, null, PGSQL_ASSOC)) {
			$nombre = ucfirst(strtolower($line['name']));;
			$id = $line['id'];

			array_push($categorias, ["id" => $id, "nombre" => $nombre]);
		}
		echo json_encode($categorias);

	} else { header("Location: ../../index"); }
?>