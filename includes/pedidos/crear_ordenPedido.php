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
        $cliente = $valor->cliente;
        $fecha = $valor->fecha;
        $horaEntrega = $valor->horaEntrega;
        $formaPago = $valor->formaPago;
        $direccionCliente = $valor->direccionCliente;
        $observacionCliente = $valor->observacionCliente;
        //Para quitar (.) ($) ( ) y dejar el numero como un entero
        $tranformar = array('.' => '', '$' => '', ' ' => '');

        $sql = "INSERT INTO orders (customer_id, created_at, observation, payment_method, paid_plan, delivery_time) VALUES ('$cliente', '$fecha', '$observacionCliente', '$formaPago', true, '$horaEntrega')";
        $resultado = pg_query($sql);

        if($resultado){
            $sqli = "SELECT * FROM orders ORDER BY id DESC LIMIT 1";
            $result = pg_query($sqli);
            $result_s = pg_fetch_array($result, null, PGSQL_ASSOC);
            $id_orden = $result_s['id'];

            $sql = "INSERT INTO order_addresses (order_id, address_id, created_at) VALUES ('$id_orden', '$direccionCliente', '$fecha')";
            $resultadoOA = pg_query($sql);
            
            if($resultadoOA){
                //Capturo el array de los productos
                $productos = $valor->productos;
                //Recorro el array de los productos
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
                        $sql = "INSERT INTO order_products (product_id, order_id, created_at, quantity, size_id, additions_id) VALUES ('$idProducto', '$id_orden', '$fecha', '$cantidadProducto', '$sizeProducto', '$idAdicion')";
                        $query = pg_query($sql);
                        
                    }else if(is_numeric($objetivoProducto)){
                        $sql = "INSERT INTO order_products (product_id, order_id, created_at, quantity, target_id, additions_id) VALUES ('$idProducto', '$id_orden', '$fecha', '$cantidadProducto', '$objetivoProducto', '$idAdicion')";
                        $query = pg_query($sql);
                    }else{
                        $sql = "INSERT INTO order_products (product_id, order_id, created_at, quantity, additions_id) VALUES ('$idProducto', '$id_orden', '$fecha', '$cantidadProducto', '$idAdicion')";
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