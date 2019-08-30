<?php
//Recibo el array
if($_POST['data']){
    //Requiero la conexion
    require '../conexion.php';
    //Codifico el json
    $datos = json_decode($_POST['data']);
    //Recorro el arreglo para obtener los datos
    foreach ($datos as $clave=>$valor){
        //Asigno las variables
        $idPedido = $valor->idPedido;
        $cliente = $valor->cliente;
        $fecha = $valor->fecha;
        $direccionCliente = $valor->direccionCliente;
        $observacionCliente = $valor->observacionCliente;
        //Hora de entrega
        $horaEntrega = $valor->horaEntrega;
     
        $sql = "UPDATE orders SET customer_id = '$cliente', observation = '$observacionCliente', delivery_time = '$horaEntrega' WHERE id = '$idPedido'";
        $resultado = pg_query($sql);

        if($resultado){
            $psq = "DELETE FROM order_products WHERE order_id = '$idPedido'";
            $presultad = pg_query($psq);

            if($presultad){
                $sql = "UPDATE order_addresses SET address_id = '$direccionCliente' WHERE order_id = '$idPedido'";
                $query = pg_query($sql);
                //Capturo el array de los productos
                $productos = $valor->productos;
                
                // Recorro el array de los productos
                foreach($productos as $clave=>$producto){
                    $idProducto = $producto->producto;
                    $cantidadProducto = $producto->cantidad;
                    $sizeProducto = $producto->size;
                    $objetivoProducto = $producto->objetivo;
                    $adiciones = $producto->adicional;

                    $idAdicion = "";
                    foreach ($adiciones as $key => $value) {
                        $idAdicion = $idAdicion . "," . $value->id;
                    }

                    if(is_numeric($sizeProducto)){
                        $sql = "INSERT INTO order_products (product_id, order_id, created_at, quantity, size_id, additions_id) VALUES ('$idProducto', '$idPedido', '$fecha', '$cantidadProducto', '$sizeProducto', '$idAdicion')";
                        $query = pg_query($sql);
                        
                    }else if(is_numeric($objetivoProducto)){
                        $sql = "INSERT INTO order_products (product_id, order_id, created_at, quantity, target_id, additions_id) VALUES ('$idProducto', '$idPedido', '$fecha', '$cantidadProducto', '$objetivoProducto', '$idAdicion')";
                        $query = pg_query($sql);
                    }else{
                        $sql = "INSERT INTO order_products (product_id, order_id, created_at, quantity, additions_id) VALUES ('$idProducto', '$idPedido', '$fecha', '$cantidadProducto', '$idAdicion')";
                        $query = pg_query($sql);
                    }
                }
                echo '1';
            }
        }else{
            echo '2'; //False
        }
    }
}

?>