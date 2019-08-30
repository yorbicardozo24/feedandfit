<?php 
    //ID DEL PEDIDO
    $id_pedido = $_POST['data'];
    $from = $_POST['from'];
    $id_factura_nueva = 0;
    $recargar = 1;
    //CONEXION
    require '../conexion.php';

    //PARA FECHA, DESCUENTO, HORA ENTREGA, SUBTOTAL, TOTAL, FORMA DE PAGO, DOMICILIO Y ID DEL CLIENTE QUE ORDENÓ EL PEDIDO
    $sql = "SELECT * FROM orders WHERE id = '$id_pedido'";
    $resultado = pg_query($sql);
    $fila = pg_fetch_array($resultado, null, PGSQL_ASSOC);
    $subtotal = number_format($fila['subtotal'], 0, '', '.');
    $descuento = number_format($fila['discount'], 0, '', '.');
    $observacionPedido = $fila['observation'];
    $id_customer = $fila['customer_id'];
    $fecha = $fila['created_at'];
    $horaentrega = $fila['delivery_time'];
    $subtotal = number_format($fila['subtotal'], 0, '', '.');
    $total = number_format($fila['total'], 0, '', '.');
    $domicilio = number_format($fila['domicilio'], 0, '', '.');
    $formaPago = $fila['payment_method'];
    $id_factura = $fila['legal_invoice_id'];
    $factura_estado = $fila['factura_estado'];
    //SI ES PLAN O NO
    $paid_plan = $fila['paid_plan'];
    //SI LA DATA QUE RECIBO PROVIENE PARA VER LA FACTURA ENTONCES
    if($from == 'factura'){
        //SELECCIONO EL ULTIMO REGISTRO QUE CONTIENE No DE FACTURA Y LE AGREGO 1
        $sql_query = "SELECT * FROM orders WHERE legal_invoice_id IS NOT NULL ORDER BY legal_invoice_id DESC LIMIT 1";
        $sql_query_sql = pg_query($sql_query);
        $fila = pg_fetch_array($sql_query_sql, null, PGSQL_ASSOC);
        //SUMO 1 
        $id_factura_nueva = $fila['legal_invoice_id'] + 1;

        //VALIDO SI LA FACTURA YA TIENE UN ID
        if($id_factura === NULL){
            $set = "UPDATE orders SET legal_invoice_id = '$id_factura_nueva', status = 1 WHERE id = '$id_pedido'";
            $set_query = pg_query($set);
        }else{
            $recargar = 0;
            $id_factura_nueva = $id_factura;
        }
    }
    if($from == 'ordenPedido'){
        $sql = "SELECT * FROM orders WHERE id = '$id_pedido'";
        $query = pg_query($sql);
        $fila = pg_fetch_array($query, null, PGSQL_ASSOC);

        $status = $fila['status'];
        if($status > 0){
            $recargar = 0;
        }else{

            $set = "UPDATE orders SET status = 1 WHERE id = '$id_pedido'";
            $set_query = pg_query($set);
        }
        
    }
    //PARA NOMBRE, APELLIDO, EMAIL, CEDULA Y TELEFONO DEL CLIENTE
    $query = "SELECT * FROM customers WHERE id = '$id_customer'";
    $resul = pg_query($query);
    $result = pg_fetch_array($resul, null, PGSQL_ASSOC);
    $nombre_cliente = $result['name'];
    $apellido_cliente = $result['last_name'];
    $observacionCliente = $result['observations'];
    $email = $result['email'];
    $telefono = $result['phone'];
    $cedula = $result['document_number'];
    $id_cliente = $result['id'];

    //PARA DIRECCION ELEGIDA DEL CLIENTE
    $sql = "SELECT * FROM order_addresses WHERE order_id = '$id_pedido'";
    $query = pg_query($sql);
    $fila = pg_fetch_array($query, null, PGSQL_ASSOC);
    $idDireccion = $fila['address_id'];

    if($from == 'historial' || $_POST['historial'] == 'historial'){
        //PARA DIRECCION DEL CLIENTE Y ZONA DOMICILIARIA
        $query_sql = "SELECT * FROM history_addresses WHERE address_id = '$idDireccion'";
        $resul_sql = pg_query($query_sql);
        $result_sql = pg_fetch_array($resul_sql, null, PGSQL_ASSOC);
        $direccion_cliente = $result_sql['name'];
        $delivery_zone_id = $result_sql['delivery_zone_id'];

    }else{
        //PARA DIRECCION DEL CLIENTE Y ZONA DOMICILIARIA
        $query_sql = "SELECT * FROM addresses WHERE id = '$idDireccion'";
        $resul_sql = pg_query($query_sql);
        $result_sql = pg_fetch_array($resul_sql, null, PGSQL_ASSOC);
        $direccion_cliente = $result_sql['name'];
        $delivery_zone_id = $result_sql['delivery_zone_id'];
    }

    //PARA LOS PRODUCTOS
    $query_sq = "SELECT * FROM order_products WHERE order_id = '$id_pedido' ORDER BY id ASC";
    $resul_sq = pg_query($query_sq);
    $productos = array();
    while ($line = pg_fetch_array($resul_sq, null, PGSQL_ASSOC)) {
        $id_product = $line['product_id'];
        $cantidad = $line['quantity'];
        $size_id = $line['size_id'];
        $objetivo_id = $line['target_id'];
        $adicionesId = $line['additions_id'];

        //PARA PRECIO DEL PRODUCTO
        if($from == 'historial' || $_POST['historial'] == 'historial'){
            $adicionesId = explode(",", $adicionesId);
            $nAdicionesId = count($adicionesId);
            $additions = array();
            for ($i=0; $i <$nAdicionesId ; $i++) {
                $id = $adicionesId[$i];
                if($id){
                    $sql = "SELECT * FROM history_additions WHERE additions_id = '$id'";
                    $query = pg_query($sql);
                    $row = pg_fetch_array($query, null, PGSQL_ASSOC);

                    $nombre = $row['name'];
                    $precio = $row['price'];
                    $id = $row['id'];
                    array_push($additions, ["id" => $id, "nombre" => $nombre, "precio" => $precio]);
                }
            }
            //PARA NOMBRE DEL PRODUCTO
            $query_s = "SELECT * FROM history_product WHERE product_id = '$id_product'";
            $resul_s = pg_query($query_s);
            $row = pg_fetch_array($resul_s, null, PGSQL_ASSOC);
            $nombre_producto = $row['name'];
            $id_producto = $row['id'];
            $precio = 0;
            if($size_id != ''){
                $sqli = "SELECT * FROM history_sizes WHERE id_size = '$size_id'";
                $resul_sqli = pg_query($sqli);
                $row_query = pg_fetch_array($resul_sqli, null, PGSQL_ASSOC);
                $precio = number_format($row_query['price'], 0, '', '.');
                $size = $row_query['name'];
            }else if($objetivo_id != ''){
                $sqli = "SELECT * FROM history_targets WHERE target_id = '$objetivo_id'";
                $resul_sqli = pg_query($sqli);
                $row_query = pg_fetch_array($resul_sqli, null, PGSQL_ASSOC);
                $precio = number_format($row_query['price'], 0, '', '.');
                $size = $row_query['name'];
            }else{
                $precio = number_format($row['price'], 0, '', '.');
                $size = "simple";
            }
        }else{
            $adicionesId = explode(",", $adicionesId);
            $nAdicionesId = count($adicionesId);
            $additions = array();
            for ($i=0; $i <$nAdicionesId ; $i++) {
                $id = $adicionesId[$i];
                if($id){
                    $sql = "SELECT * FROM additions WHERE id = '$id'";
                    $query = pg_query($sql);
                    $row = pg_fetch_array($query, null, PGSQL_ASSOC);

                    $nombre = $row['name'];
                    $precio = $row['price'];
                    $id = $row['id'];
                    array_push($additions, ["id" => $id, "nombre" => $nombre, "precio" => $precio]);
                }
            }
            //PARA NOMBRE DEL PRODUCTO
            $query_s = "SELECT * FROM products WHERE id = '$id_product'";
            $resul_s = pg_query($query_s);
            $row = pg_fetch_array($resul_s, null, PGSQL_ASSOC);
            $nombre_producto = $row['name'];
            $id_producto = $row['id'];
            $precio = 0;
            if($size_id != ''){
                $sqli = "SELECT * FROM sizes WHERE id = '$size_id'";
                $resul_sqli = pg_query($sqli);
                $row_query = pg_fetch_array($resul_sqli, null, PGSQL_ASSOC);
                $precio = number_format($row_query['price'], 0, '', '.');
                $size = $row_query['name'];
            }else if($objetivo_id != ''){
                $sqli = "SELECT * FROM targets WHERE id = '$objetivo_id'";
                $resul_sqli = pg_query($sqli);
                $row_query = pg_fetch_array($resul_sqli, null, PGSQL_ASSOC);
                $precio = number_format($row_query['price'], 0, '', '.');
                $size = $row_query['name'];
            }else{
                $precio = number_format($row['price'], 0, '', '.');
                $size = "simple";
            }
        }

        array_push($productos, ["name" => $nombre_producto, "cantidad" => $cantidad, "precio" => $precio, "size" => $size, "id" => $id_producto, "adiciones" => $additions]);
    }
    $time = substr($fecha, 11);
    $time = date('h:i A', strtotime($time));
    $hourentrega = date('h:i A', strtotime($horaentrega));

    //ARRREGLO QUE ENVIO PARA MOSTRAR EN LA PAGINA DE PEDIDOS HACIENDO UNA VENTANA MODAL
    $arreglo = array("id" => $id_pedido, "id_cliente" => $id_cliente, "nombre" => $nombre_cliente, "apellido" => $apellido_cliente, "cedula" => $cedula, "fecha" => $fecha, "time" => $time, "horaentrega" => $horaentrega, "hourentrega" => $hourentrega, "direccion" => $direccion_cliente, "observacion" => $observacionCliente, "observacionPedido" => $observacionPedido, "email" => $email, "telefono" => $telefono, "domicilio" => $domicilio, "descuento" => $descuento, "subtotal" => $subtotal, "total" => $total, "paid_plan" => $paid_plan, "productos" => $productos, "formapago" => $formaPago, "id_factura" => $id_factura_nueva, "facturaId" => $id_factura, "factura_estado" => $factura_estado, "recargar" => $recargar);
    echo json_encode($arreglo);

?>