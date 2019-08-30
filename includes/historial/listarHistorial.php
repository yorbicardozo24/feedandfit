<?php 
	session_start();
	if(isset($_SESSION["usuario"])){
		require '../conexion.php';

		$from = $_POST['data'];

		if($from == 'inicial'){
            //PARA CREAR LA TABLA
        	$sql = "SELECT 
                        orders.legal_invoice_id,
                        orders.id,
                        history_customers.name,
                        history_customers.last_name,
                        orders.total,
                        orders.payment_method,
                        history_addresses.name AS direccion,
                        orders.delivery_time,
                        orders.paid_plan,
                        orders.created_at,
                        orders.factura_estado
                    FROM orders
                        JOIN history_customers ON orders.customer_id = history_customers.customer_id 
                        JOIN order_addresses ON order_addresses.order_id = orders.id 
                        JOIN history_addresses ON order_addresses.address_id = history_addresses.address_id 
                    WHERE moved_to_sale_history_at IS NOT NULL 
                        AND factura_estado IS NOT NULL
                    GROUP BY orders.id, history_customers.name, history_customers.last_name, history_addresses.name
                    ORDER BY moved_to_sale_history_at DESC";
        	$resultado = pg_query($sql);

            //PARA MEJOR PRODUCTO
            $sqle = "SELECT 
                        name,
                        COUNT(product_id) AS cantidad_total
                    FROM history_product
                    WHERE product_id IN 
                        (SELECT 
                                order_products.product_id
                            FROM order_products
                            INNER JOIN orders ON orders.id = order_products.order_id 
                            WHERE orders.moved_to_sale_history_at IS NOT NULL 
                            AND orders.factura_estado IS NOT NULL
                            GROUP BY order_products.product_id
                            ORDER BY SUM(quantity) DESC 
                        LIMIT 1)
                    GROUP BY name";
            $query = pg_query($sqle);
            $fila = pg_fetch_array($query, null, PGSQL_ASSOC);

            //PARA PRODUCTOS VENDIDOS
            $query = "SELECT SUM(quantity) AS quantity FROM order_products INNER JOIN orders ON orders.id = order_products.order_id WHERE orders.moved_to_sale_history_at IS NOT NULL AND orders.factura_estado IS NOT NULL";
            $result = pg_query($query);
            $result = pg_fetch_array($result, null, PGSQL_ASSOC);

            //PARA TOTAL VENDIDO
            $sq = "SELECT SUM(subtotal) AS subtotal FROM orders WHERE factura_estado = 'fpagada'";
            $qs = pg_query($sq);
            $resulta = pg_fetch_array($qs, null, PGSQL_ASSOC);

            //PARA MEJOR ZONA
            $querySql = "SELECT history_addresses.delivery_zone_id, history_delivery_zones.name, COUNT(history_addresses.address_id) AS total FROM order_addresses INNER JOIN history_addresses ON history_addresses.address_id = order_addresses.address_id INNER JOIN history_delivery_zones ON history_delivery_zones.delivery_zone_id = history_addresses.delivery_zone_id INNER JOIN orders ON orders.id = order_addresses.order_id WHERE moved_to_sale_history_at IS NOT NULL AND factura_estado IS NOT NULL GROUP BY history_addresses.delivery_zone_id, history_delivery_zones.delivery_zone_id, history_delivery_zones.name ORDER BY total DESC LIMIT 1";
            $row = pg_query($querySql);
            $row = pg_fetch_array($row, null, PGSQL_ASSOC);

            $mejorZona = ucfirst(strtolower($row['name']));
            $vendido = $resulta['subtotal'];
            $productosVendidos = $result['quantity'];
            $mejorProducto = ucfirst(strtolower($fila['name']));
            $cantidadMejorProducto = $fila['cantidad_total'];

    		$filas = array();

			while ($line = pg_fetch_array($resultado, null, PGSQL_ASSOC)) {
				$nFactura = $line['legal_invoice_id'];
                $id = $line['id'];
				$total = $line['total'];
				$direccion = $line['direccion'];
				$cliente = $line['name'] . " " . $line['last_name'];
				//Metodo de pago elegido por el cliente
                $payment_method = $line['payment_method'];
                $paid_plan = $line['paid_plan'];

                switch($payment_method){
                    case 1:
                        $payment_method = "Consignación";
                        break;
                    case 2:
                        $payment_method = "Efectivo";
                        break;
                    case 3:
                        $payment_method = "iFood";
                        break;
                    case 4:
                        $payment_method = "UberEats";
                        break;
                    case 5:
                        $payment_method = "Sin Cobro";
                        break;
                }
                $fecha = $line['created_at'];
                
                $estado = $line['factura_estado'];
				if($estado == 'fanulada'){
					$estado = "Factura Anulada";
		 		}else if($estado == 'fpagada'){
		 			$estado = "Factura Pagada";
		 		}else if($estado == 'Panulado'){
                    $estado = "Envio Anulado";
                }else{
                    $estado = "Enviado";
                }
                $time = substr($fecha, 11);
                $time = date('h:i A', strtotime($time));
	
				array_push($filas, ["id" => $id, "nFactura" => $nFactura, "payment_method" => $payment_method ,"cliente" => $cliente, "total" => $total, "direccion" => $direccion, "fecha" => $fecha, "time" => $time, "estado" => $estado, "paid_plan" => $paid_plan, "mejorProducto" => $mejorProducto, "cantidadMejorProducto" => $cantidadMejorProducto, "productosVendidos" => $productosVendidos, "vendido" => $vendido, "mejorZona" => $mejorZona]);
			}
			
			echo json_encode($filas);
		}
        if($from == 'fechas'){
            $fechaInicial = json_decode($_POST['fechaInicial']);

            $fechaInicial = $fechaInicial->{'yearInicial'} . "-" . $fechaInicial->{'mesInicial'} . "-" . $fechaInicial->{'diaInicial'} . " 00:00:00";
            
            $fechaFinal = json_decode($_POST['fechaFinal']);

            $fechaFinal = $fechaFinal->{'yearFinal'} . "-" . $fechaFinal->{'mesFinal'} . "-" . $fechaFinal->{'diaFinal'} . " 23:59:59";

            //PARA CREAR LA TABLA
            $sql = "SELECT 
                        orders.legal_invoice_id,
                        orders.id,
                        history_customers.name,
                        history_customers.last_name,
                        orders.total,
                        orders.payment_method,
                        history_addresses.name AS direccion,
                        orders.delivery_time,
                        orders.paid_plan,
                        orders.created_at,
                        orders.factura_estado
                    FROM orders
                        JOIN history_customers ON orders.customer_id = history_customers.customer_id 
                        JOIN order_addresses ON order_addresses.order_id = orders.id 
                        JOIN history_addresses ON order_addresses.address_id = history_addresses.address_id 
                    WHERE moved_to_sale_history_at IS NOT NULL 
                        AND factura_estado IS NOT NULL
                        AND moved_to_sale_history_at > '$fechaInicial' AND moved_to_sale_history_at < '$fechaFinal'
                    GROUP BY orders.id, history_customers.name, history_customers.last_name, history_addresses.name
                    ORDER BY moved_to_sale_history_at DESC";
            $resultado = pg_query($sql);
            //PARA MEJOR PRODUCTO
            $sqle = "SELECT 
                        name,
                        COUNT(product_id) AS cantidad_total
                    FROM history_product
                    WHERE product_id IN 
                        (SELECT 
                                order_products.product_id
                            FROM order_products
                            INNER JOIN orders ON orders.id = order_products.order_id 
                            WHERE orders.moved_to_sale_history_at IS NOT NULL 
                            AND orders.factura_estado IS NOT NULL
                            AND order_products.created_at > '$fechaInicial' AND order_products.created_at < '$fechaFinal'
                            GROUP BY order_products.product_id
                            ORDER BY SUM(quantity) DESC 
                        LIMIT 1)
                    GROUP BY name";
            $query = pg_query($sqle);
            $fila = pg_fetch_array($query, null, PGSQL_ASSOC);

            $query = "SELECT SUM(quantity) AS quantity FROM order_products INNER JOIN orders ON orders.id = order_products.order_id WHERE orders.moved_to_sale_history_at IS NOT NULL AND orders.factura_estado IS NOT NULL AND orders.created_at > '$fechaInicial' AND orders.created_at < '$fechaFinal'";
            $result = pg_query($query);
            $result = pg_fetch_array($result, null, PGSQL_ASSOC);
            //PARA TOTAL VENDIDO
            $sq = "SELECT SUM(subtotal) AS subtotal FROM orders WHERE factura_estado = 'fpagada' AND moved_to_sale_history_at > '$fechaInicial' AND moved_to_sale_history_at < '$fechaFinal'";
            $qs = pg_query($sq);
            $resulta = pg_fetch_array($qs, null, PGSQL_ASSOC);
            //PARA MEJOR ZONA
            $querySql = "SELECT history_addresses.delivery_zone_id, history_delivery_zones.name, COUNT(history_addresses.address_id) AS total FROM order_addresses INNER JOIN history_addresses ON history_addresses.address_id = order_addresses.address_id INNER JOIN history_delivery_zones ON history_delivery_zones.delivery_zone_id = history_addresses.delivery_zone_id INNER JOIN orders ON orders.id = order_addresses.order_id WHERE moved_to_sale_history_at IS NOT NULL AND factura_estado IS NOT NULL AND order_addresses.created_at > '$fechaInicial' AND order_addresses.created_at < '$fechaFinal' GROUP BY history_addresses.delivery_zone_id, history_delivery_zones.delivery_zone_id, history_delivery_zones.name ORDER BY total DESC LIMIT 1";
            $row = pg_query($querySql);
            $row = pg_fetch_array($row, null, PGSQL_ASSOC);

            $mejorZona = ucfirst(strtolower($row['name']));
            $vendido = $resulta['subtotal'];
            $productosVendidos = $result['quantity'];
            $mejorProducto = ucfirst(strtolower($fila['name']));
            $cantidadMejorProducto = $fila['cantidad_total'];

            $filas = array();

            while ($line = pg_fetch_array($resultado, null, PGSQL_ASSOC)) {
                $nFactura = $line['legal_invoice_id'];
                $id = $line['id'];
                $total = $line['total'];
                $direccion = $line['direccion'];
                $cliente = $line['name'] . " " . $line['last_name'];
                //Metodo de pago elegido por el cliente
                $payment_method = $line['payment_method'];
                $paid_plan = $line['paid_plan'];

                switch($payment_method){
                    case 1:
                        $payment_method = "Consignación";
                        break;
                    case 2:
                        $payment_method = "Efectivo";
                        break;
                    case 3:
                        $payment_method = "iFood";
                        break;
                    case 4:
                        $payment_method = "UberEats";
                        break;
                    case 5:
                        $payment_method = "Sin Cobro";
                        break;
                }
                $fecha = $line['created_at'];
                
                $estado = $line['factura_estado'];
                if($estado == 'fanulada'){
                    $estado = "Factura Anulada";
                }else if($estado == 'fpagada'){
                    $estado = "Factura Pagada";
                }else{
                    $estado = "Plan";
                } 
                $time = substr($fecha, 11);
                $time = date('h:i A', strtotime($time));
    
                array_push($filas, ["id" => $id, "nFactura" => $nFactura, "payment_method" => $payment_method ,"cliente" => $cliente, "total" => $total, "direccion" => $direccion, "fecha" => $fecha, "time" => $time, "estado" => $estado, "paid_plan" => $paid_plan, "mejorProducto" => $mejorProducto, "cantidadMejorProducto" => $cantidadMejorProducto, "productosVendidos" => $productosVendidos, "vendido" => $vendido, "mejorZona" => $mejorZona]);
            }
            
            echo json_encode($filas);
        }
	} else { header("Location: ../../index"); }

?>