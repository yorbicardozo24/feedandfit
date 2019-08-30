<?php 
	session_start();
	if(isset($_SESSION["usuario"])){
		require '../conexion.php';
		require_once '../Classes/PHPExcel.php';

		$objPHPExcel = new PHPExcel();

		$fechaInicial = $_GET['fechaInicial'];
		$fechaFinal = $_GET['fechaFinal'];

		$diaInicial = substr($fechaInicial, 3, 2);
		$mesInicial = substr($fechaInicial, 0, 2);
		$yearInicial = substr($fechaInicial, 6, 4);
		$fechaInicial = $yearInicial . "-" . $mesInicial . "-" .$diaInicial . " 00:00:00";

		$diaFinal = substr($fechaFinal, 3, 2);
		$mesFinal = substr($fechaFinal, 0, 2);
		$yearFinal = substr($fechaFinal, 6, 4);
		$fechaFinal = $yearFinal . "-" . $mesFinal . "-" .$diaFinal . " 23:59:59";
	

		$objPHPExcel->getProperties()
        	->setCreator("FeedAndFit")
        	->setLastModifiedBy("FeedAndFit")
        	->setTitle("Reporte Mensual")
        	->setDescription("Reporte Mensual");

		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle('Hoja 1');

        //PARA CREAR LA TABLA
        $sql = "SELECT orders.legal_invoice_id, customers.name, customers.last_name, orders.subtotal, orders.discount, orders.domicilio, orders.total, ROUND(orders.subtotal / 1.08) AS bruto, ROUND((orders.subtotal / 1.08) * 0.08) AS Impoconsumo, TO_CHAR(orders.created_at :: DATE, 'dd/mm/yyyy') AS Fecha, orders.factura_estado FROM orders INNER JOIN customers ON orders.customer_id = customers.id INNER JOIN order_addresses ON order_addresses.order_id = orders.id INNER JOIN addresses ON order_addresses.address_id = addresses.id WHERE moved_to_sale_history_at IS NOT NULL AND factura_estado IS NOT NULL AND moved_to_sale_history_at > '$fechaInicial' AND moved_to_sale_history_at < '$fechaFinal' ORDER BY moved_to_sale_history_at DESC";
            $resultado = pg_query($sql);

		$n = 2;
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Factura');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Fecha');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Cliente');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'SubTotal');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Domicilio');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Descuento');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Valor Bruto');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Impoconsumo');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Total');
        while ($line = pg_fetch_array($resultado, null, PGSQL_ASSOC)) {
            $nFactura = $line['legal_invoice_id'];
            $fecha = $line['fecha'];
            $cliente = $line['name'] . " " . $line['last_name'];
            $discount = $line['discount'];
            $subtotal = $line['subtotal'];
            $domicilio = $line['domicilio'];
            $bruto = $line['bruto'];
            $impoconsumo = $line['impoconsumo'];
            $total = $line['total'];
            
            $estado = $line['factura_estado'];
            if($estado == 'fanulada'){
                $estado = "Factura Anulada";
            }else if($estado == 'fpagada'){
                $estado = "Factura Pagada";
            }else{
                $estado = "Plan";
            }
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$n, $nFactura);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$n, $fecha);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$n, $cliente);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$n, $subtotal);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$n, $domicilio);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$n, $discount);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$n, $bruto);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$n, $impoconsumo);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$n, $total);

			$n = $n + 1;
            
            }


        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header('Content-Disposition: attachment;filename="Reporte Mensual.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');

		echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";

        // //PARA MEJOR PRODUCTO
        // $sqle = "SELECT order_products.product_id, products.name, SUM(quantity) AS cantidad_total FROM order_products INNER JOIN products ON products.id = order_products.product_id INNER JOIN orders ON orders.id = order_products.order_id WHERE orders.moved_to_sale_history_at IS NOT NULL AND orders.factura_estado IS NOT NULL AND order_products.created_at > '$fechaInicial' AND order_products.created_at < '$fechaFinal' GROUP BY order_products.product_id, products.name ORDER BY SUM(quantity) DESC LIMIT 1";
        // 	$query = pg_query($sqle);
        // 	$fila = pg_fetch_array($query, null, PGSQL_ASSOC);
        // $query = "SELECT SUM(quantity) AS quantity FROM order_products INNER JOIN orders ON orders.id = order_products.order_id WHERE orders.moved_to_sale_history_at IS NOT NULL AND orders.factura_estado IS NOT NULL AND orders.created_at > '$fechaInicial' AND orders.created_at < '$fechaFinal'";
        //     $result = pg_query($query);
        //     $result = pg_fetch_array($result, null, PGSQL_ASSOC);

        //     //PARA TOTAL VENDIDO
        // $sq = "SELECT SUM(subtotal) AS subtotal FROM orders WHERE factura_estado = 'fpagada' AND moved_to_sale_history_at > '$fechaInicial' AND moved_to_sale_history_at < '$fechaFinal'";
        //     $qs = pg_query($sq);
        //     $resulta = pg_fetch_array($qs, null, PGSQL_ASSOC);

        //     //PARA MEJOR ZONA
        // $querySql = "SELECT addresses.delivery_zone_id, delivery_zones.name, COUNT(address_id) AS total FROM order_addresses INNER JOIN addresses ON addresses.id = order_addresses.address_id INNER JOIN delivery_zones ON delivery_zones.id = addresses.delivery_zone_id INNER JOIN orders ON orders.id = order_addresses.order_id WHERE moved_to_sale_history_at IS NOT NULL AND factura_estado IS NOT NULL AND order_addresses.created_at > '$fechaInicial' AND order_addresses.created_at < '$fechaFinal' GROUP BY addresses.delivery_zone_id, delivery_zones.id ORDER BY total DESC LIMIT 1";
        //     $row = pg_query($querySql);
        //     $row = pg_fetch_array($row, null, PGSQL_ASSOC);

        //     $mejorZona = ucfirst(strtolower($row['name']));
        //     $vendido = $resulta['subtotal'];
        //     $productosVendidos = $result['quantity'];
        //     $mejorProducto = ucfirst(strtolower($fila['name']));
        //     $cantidadMejorProducto = $fila['cantidad_total'];
    } else { header("Location: ../../index"); }
?>