<?php 
	//Recibo el array
	$datos = json_decode($_POST['data']);
	//Requiero la conexion
    require '../conexion.php';
    //SI EXISTE EL ARRAY
	if($datos){
		//ALMACENO LOS DATOS
		$idZona = $datos->{'id'};
		$checked = $datos->{'checked'};

		if($checked == 't'){
			$checked = "true";
		}else{
			$checked = "false";
		}

		$sql = "UPDATE delivery_zones SET active = '$checked' WHERE id = '$idZona'";
        $resultado = pg_query($sql);
	}

?>