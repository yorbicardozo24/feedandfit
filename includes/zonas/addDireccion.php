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
		$idCliente = $datos->{'idCliente'};
		$direccion = $datos->{'direccion'};
		$idZona = $datos->{'idZona'};

		$fecha = getdate();
		
		$year = $fecha['year'];
        $dia = $fecha['mday'];
        $mes = $fecha['mon'];

        $hora = $fecha['hours'];
        $minutos = $fecha['minutes'];
        $segundos = $fecha['seconds'];

        $fecha = $year . "-" . $mes . "-" .  $dia . " " . $hora . ":" . $minutos . ":" . $segundos;

		$sql = "INSERT INTO addresses (customer_id, delivery_zone_id, name, created_at) values ('$idCliente', '$idZona', '$direccion', '$fecha')";
        $resultado = pg_query($sql);

        if($resultado){
        	echo "1";
        }
	}

	} else { header("Location: ../../index"); }
?>