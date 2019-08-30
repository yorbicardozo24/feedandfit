<?php 
	//NUMERO DE FILAS
	$rows = $_POST['data'];
	//CONEXION
	require '../conexion.php';

	$categorias = array();

	if($rows){

		$query = "SELECT * FROM categories ORDER BY id ASC";
		$result = pg_query($query);

		while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
			$name_categoria = $row['name'];
			$id_categoria = $row['id'];

			$sql = "SELECT * FROM products WHERE category_id = '$id_categoria' ORDER BY id DESC";
			$resultado = pg_query($sql);

			$productos = array();

			while ($line = pg_fetch_array($resultado, null, PGSQL_ASSOC)) {
				$id_producto = $line['id'];
				$nombre_producto = $line['name'];
				$descripcion = $line['description'];
				$habilitado = $line['active'];
				$almuerzo = $line['menu_of_the_day_lunch'];
				$cena = $line['menu_of_the_day_dinner'];	
				
				array_push($productos, ["id" => $id_producto, "nombre" => $nombre_producto, "descripcion" => $descripcion, "habilitado" => $habilitado, "almuerzo" => $almuerzo, "cena" => $cena, "habilitadoText" => "habilitado", "almuerzoText" => "almuerzo", "cenaText" => "cena"]);
			}
			array_push($categorias, ["id" => $id_categoria, "categoria" => $name_categoria, "productos" => $productos]);
		}
			
		echo json_encode($categorias);
	}
?>