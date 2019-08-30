<?php 
	//Recibo el array
	$datos = json_decode($_POST['data']);
	//Requiero la conexion
    require '../conexion.php';
    //SI EXISTE EL ARRAY
	if($datos){
		//ALMACENO LOS DATOS
		$idCategoria = $datos->{'id'};
		$checked = $datos->{'checked'};

		if($checked == 't'){
			$checked = "true";
		}else{
			$checked = "false";
		}

		$sql = "UPDATE categories SET active = '$checked' WHERE id = '$idCategoria'";
        $resultado = pg_query($sql);
	}

?>