<?php
	session_start();
	if(isset($_SESSION["usuario"])){

	//Recibo el array
	$datos = json_decode($_POST['data']);
	//Requiero la conexion
    require '../conexion.php';
    //SI EXISTE EL ARRAY
	if($datos){
		//ALMACENO LOS DATOS
		$nombre = $datos->{'nombre'};
		$checked = $datos->{'checked'};

		if($checked == '1'){
			$checked = "true";
		}else{
			$checked = "false";
		}
		$fecha = getdate();
		
		$year = $fecha['year'];
        $dia = $fecha['mday'];
        $mes = $fecha['mon'];

        $hora = $fecha['hours'];
        $minutos = $fecha['minutes'];
        $segundos = $fecha['seconds'];

        $fecha = $year . "-" . $mes . "-" .  $dia . " " . $hora . ":" . $minutos . ":" . $segundos;

		$sql = "INSERT INTO categories (name, active, created_at) values ('$nombre', '$checked', '$fecha')";
        $resultado = pg_query($sql);

        if($resultado){
        	echo "1";
        }
	}

	} else { header("Location: ../../index"); }
?>