<?php 
	//CONEXION
	require '../conexion.php';

	$query = "SELECT * FROM products WHERE active = 'true' ORDER BY name ASC";
    $resultado = pg_query($query);

	$productos = array();
	array_push($productos, ["id" => 0, "nombre" => "Escoge"]);

	while ($line = pg_fetch_array($resultado, null, PGSQL_ASSOC)) {
		$id_producto = $line['id'];
		$nombre_producto = $line['name'];	
	
		array_push($productos, ["id" => $id_producto, "nombre" => $nombre_producto]);
	}
			
	echo json_encode($productos);
?>