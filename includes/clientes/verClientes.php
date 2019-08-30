<?php
session_start();
    if(isset($_SESSION["usuario"])){
        //ID DEL CLIENTE
        $id = $_POST['data'];
        //CONEXION
        require '../conexion.php';

        $sql = "SELECT customers.id,
            customers.name,
            customers.last_name,
            customers.email,
            customers.phone,
            TO_CHAR(customers.created_at :: DATE, 'dd/mm/yyyy') AS fecha,
            customers.observations,
            customers.delivery_time,
            customers.document_type,
            customers.document_number,
            customers.selected_time
            FROM customers 
            WHERE id = '$id'";
        $resultado = pg_query($sql);
        $fila = pg_fetch_array($resultado, null, PGSQL_ASSOC);
        
        $idCliente = $fila['id'];
        $nombreCliente = $fila['name'];
        $apellidoCliente = $fila['last_name'];
        $email = $fila['email'];
        $telefono = $fila['phone'];
        if($telefono == NULL){
            $telefono = "";
        }
        $fecha = $fila['fecha'];
        $observacion = $fila['observations'];
        if($observacion == NULL){
            $observacion = "";
        }
        if($fila['selected_time'] == 't'){
            $hora = date('h:i A', strtotime($fila['delivery_time']));
            $hour = $fila['delivery_time'];
        }else{
            $hora = "";
            $hour = "";
        }
        $tipo = $fila['document_type'];
        $numero = $fila['document_number'];
        if($numero == NULL){
            $numero = "";
        }

        //PARA LAS DIRECCIONES
        $query = " SELECT addresses.name as direccion, delivery_zones.name as zona, delivery_zones.id
                FROM addresses
                INNER JOIN delivery_zones ON addresses.delivery_zone_id = delivery_zones.id
                WHERE addresses.customer_id = '$id'";
        $result = pg_query($query);
        $direcciones = array();
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $direccion = $line['direccion'];
            $zona = $line['zona'];
            $idZona = $line['id'];
            
            array_push($direcciones, ["direccion" => $direccion, "zona" => $zona, "idZona" => $idZona]);
        }

        //ARRREGLO QUE ENVIO PARA MOSTRAR EN LA PAGINA DE CLIENTES HACIENDO UNA VENTANA MODAL
        $datos = array("id" => $id, "nombreCliente" => $nombreCliente, "apellidoCliente"=> $apellidoCliente, "email" => $email, "telefono" => $telefono, "fecha" => $fecha, "observacion" => $observacion, "hora" => $hora, "hour" => $hour, "tipo" => $tipo, "numero" => $numero, "direcciones" => $direcciones);
        echo json_encode($datos);
    } else { header("Location: ../../index"); }
?>