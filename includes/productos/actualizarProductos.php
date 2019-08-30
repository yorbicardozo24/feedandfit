<?php 
	//Recibo el array
	$datos = json_decode($_POST['data']);
	//Requiero la conexion
    require '../conexion.php';
    //SI EXISTE EL ARRAY
	if($datos){
		//ALMACENO LOS DATOS
		$idProducto = $datos->{'id'};
		$seccionProducto = $datos->{'seccionProducto'};
		$checked = $datos->{'checked'};

		if($checked == 't'){
			$checked = "true";
		}else{
			$checked = "false";
		}

		if($seccionProducto == 'habilitado'){
        	$sql = "UPDATE products SET active = '$checked' WHERE id = '$idProducto'";
        	$resultado = pg_query($sql);
		}else if($seccionProducto == 'almuerzo'){
			$sql = "UPDATE products SET menu_of_the_day_lunch = '$checked' WHERE id = '$idProducto'";
        	$resultado = pg_query($sql);
		}else if($seccionProducto == 'cena'){
			$sql = "UPDATE products SET menu_of_the_day_dinner = '$checked' WHERE id = '$idProducto'";
        	$resultado = pg_query($sql);
		}
	}

?>