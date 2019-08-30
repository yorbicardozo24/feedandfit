<?php 
	session_start();
	if(isset($_SESSION["usuario"])){
	
	require '../conexion.php';
	$datos = json_decode($_POST['data']);
	$from = $_POST['from'];

	if($datos){
		if($from == 'add'){
			$nombres = $datos->nombres;
			$apellidos = $datos->apellidos;
			$tipo = $datos->tipo;
			$documento = $datos->documento;
			$email = $datos->email;
			$phone = $datos->phone;
			$horaCliente = $datos->hora;
			$observaciones = $datos->observaciones;
			$direcciones = $datos->adicionalesCount;

			if(!$horaCliente){
				$selected_time = 'false';
			}else{
				$selected_time = 'true';
			}
			$horaCliente = date('h:i A', strtotime($horaCliente));

			$fecha = getdate();
			
			$year = $fecha['year'];
			$dia = $fecha['mday'];
			$mes = $fecha['mon'];

			$hora = $fecha['hours'];
			$minutos = $fecha['minutes'];
			if($minutos == 0 || $minutos == 1 || $minutos == 2 || $minutos == 3 || $minutos == 4 || $minutos == 5 || $minutos == 6 || $minutos == 7 || $minutos == 8 || $minutos == 9){
				$minutos = "0" . $minutos;
			}
			$segundos = $fecha['seconds'];

	        $fecha = $year . "-" . $mes . "-" .  $dia . " " . $hora . ":" . $minutos . ":" . $segundos;

			$sql = "INSERT INTO customers (name, last_name, email, phone, observations, selected_time, created_at, document_number, delivery_time, document_type) VALUES ('$nombres', '$apellidos', '$email', '$phone', '$observaciones', '$selected_time', '$fecha', '$documento', '$horaCliente', '$tipo')";
			$query = pg_query($sql);

			if($query){
				$sql = "SELECT id FROM customers ORDER BY id DESC LIMIT 1";
				$query = pg_query($sql);
				$row = pg_fetch_array($query, null, PGSQL_ASSOC);
				$id = $row['id'];

				foreach ($direcciones as $clave=>$valor){
					$direccion = $valor->direccionAdicional;
					$zona = $valor->zona;

					$sql = "INSERT INTO addresses (customer_id, delivery_zone_id, name, created_at) VALUES ('$id', '$zona', '$direccion', '$fecha')";
					$query = pg_query($sql);
				}

				echo "1";
			}else{
				echo "2";
			}
		}else if($from == 'edit'){
			$id = $_POST['id'];
			$nombres = $datos->nombres;
			$apellidos = $datos->apellidos;
			$tipo = $datos->tipo;
			$documento = $datos->documento;
			$email = $datos->email;
			$phone = $datos->phone;
			$horaCliente = $datos->hora;
			$observaciones = $datos->observaciones;
			$direcciones = $datos->adicionalesCount;
			if(!$horaCliente){
				$selected_time = 'false';
			}else{
				$selected_time = 'true';
			}
			$horaCliente = date('h:i A', strtotime($horaCliente));
			$sql = "UPDATE customers SET name = '$nombres', last_name = '$apellidos', email = '$email', phone = '$phone', observations = '$observaciones', document_number = '$documento', delivery_time = '$horaCliente', selected_time = '$selected_time', document_type = '$tipo' WHERE id = '$id'";
			$query = pg_query($sql);

			$fecha = getdate();
			
			$year = $fecha['year'];
			$dia = $fecha['mday'];
			$mes = $fecha['mon'];

			$hora = $fecha['hours'];
			$minutos = $fecha['minutes'];
			if($minutos == 0 || $minutos == 1 || $minutos == 2 || $minutos == 3 || $minutos == 4 || $minutos == 5 || $minutos == 6 || $minutos == 7 || $minutos == 8 || $minutos == 9){
				$minutos = "0" . $minutos;
			}
			$segundos = $fecha['seconds'];

	        $fecha = $year . "-" . $mes . "-" .  $dia . " " . $hora . ":" . $minutos . ":" . $segundos;
	

			if($query){
				$sql = "DELETE FROM addresses WHERE customer_id = '$id'";
				$resultado = pg_query($sql);
				if($resultado){
					foreach ($direcciones as $clave=>$valor){
						$direccion = $valor->direccionAdicional;
						$zona = $valor->zona;

						$sql = "INSERT INTO addresses (customer_id, delivery_zone_id, name, created_at) VALUES ('$id', '$zona', '$direccion', '$fecha')";
						$query = pg_query($sql);
					}

					echo "1";
				}
			}else{
				echo "2";
			}
		}
		
	}		
	
	} else { header("Location: ../../index"); }
?>