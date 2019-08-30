<?php
session_start();
if(isset($_SESSION["usuario"])){
    require '../conexion.php';

    $id = $_GET['id'];
    
    $sql = "SELECT * FROM orders WHERE id = '$id'";
    $resultado = pg_query($sql);
    $fila = pg_fetch_array($resultado, null, PGSQL_ASSOC);
    
    $id_factura = $fila['legal_invoice_id'];
    $paidPlan = $fila['paid_plan'];
    $estado = $fila['status'];

    if($id_factura === NULL && $paidPlan == 'f'){
      $psql = "DELETE FROM order_addresses WHERE order_id = '$id'";
      $presultado = pg_query($psql);
      
      $psq = "DELETE FROM order_products WHERE order_id = '$id'";
      $presultad = pg_query($psq);

      $sql = "DELETE FROM orders WHERE id = '$id'";
      $resultado = pg_query($sql);

      if($resultado){ 
        header("Location: ../../pedidos");
      }else{
        header("Location: ../../pedidos");
      }
    }else if($id_factura == NULL && $estado == 0){
      $psql = "DELETE FROM order_addresses WHERE order_id = '$id'";
      $presultado = pg_query($psql);
      
      $psq = "DELETE FROM order_products WHERE order_id = '$id'";
      $presultad = pg_query($psq);

      $sql = "DELETE FROM orders WHERE id = '$id'";
      $resultado = pg_query($sql);

      if($resultado){ 
        header("Location: ../../pedidos");
      }else{
        header("Location: ../../pedidos");
      }
    }else if($id_factura == NULL && $paidPlan == 't' && $estado != 0){
      $fecha = getdate();

      $year = $fecha['year'];
      $dia = $fecha['mday'];
      $mes = $fecha['mon'];

      $hora = $fecha['hours'];
      $minutos = $fecha['minutes'];
      $segundos = $fecha['seconds'];

      $fecha_anulada = $year . "-" . $mes . "-" .  $dia . " " . $hora . ":" . $minutos . ":" . $segundos;

      $set = "UPDATE orders SET factura_estado = 'Panulado', moved_to_sale_history_at = '$fecha_anulada' WHERE id = '$id'";
      $set_query = pg_query($set);

      echo '<script src="../js/jquery.min.js"></script>';
      echo'<script type="text/javascript">
            var url = "ver_pedido.php";
            $.ajax({
            type: "POST",
            url: url,
            data: {data : '.$id.', from: "ordenPedido"},
            success: function(data){
                //Recibo los datos en un array
                var datos = JSON.parse(data);
                var fecha = datos.fecha;
                var year = fecha.substring(2, 4);
                var mes = fecha.substring(8, 10);
                var dia = fecha.substring(5, 7);

                var hora = fecha.substring(10, 16);
                var hour = parseInt(hora.substring(0, 3));
                var minutos = hora.substring(4, 6);
            
                var tiempo = "";
                if(hour > 12){
                    if(minutos > 30){
                        tiempo = "PM";
                    }else{
                        tiempo = "AM";
                    }
                }else{
                    tiempo = "AM";
                }
                var horaentrega = datos.horaentrega;
                var hourEntrega = parseInt(datos.horaentrega.substring(0, 3));
                var minutosEntrega = parseInt(datos.horaentrega.substring(4, 6));
            
                var tiempoEntrega = "";
                if(hourEntrega > 12){
                    if(minutosEntrega > 30){
                        tiempoEntrega = "PM";
                    }else{
                        tiempoEntrega = "AM";
                    }
                }else{
                    tiempoEntrega = "AM";
                }
                //VALIDAR LOS DATOS PARA QUE NO SALGAN NULOS
                //CEDULA
                var cedula = datos.cedula;
                if(!!cedula){
                    var ncedula = cedula;
                }else{
                    var ncedula = "";
                }
                //DIRECCION
                var direccion = datos.direccion;
                if(!!direccion){
                    var direccion_text = direccion;
                }else{
                    var direccion_text = "";
                }
                //TELEFONO
                var telefono = datos.telefono;
                if(!!telefono){
                    var ntelefono = telefono;
                }else{
                    var ntelefono = "";
                }
                var domicilio = datos.domicilio;
                var observacion = datos.observacion;
                var descuento = datos.descuento;
                var x = 0;
                //ABRO UNA NUEVA VENTANA Y DENTRO DE ELLA ESCRIBO EL HTML
                var ventimp = window.open(\'\', \'popimpr\');
                //DATOS DEL CLIENTE EN LA FACTURA
                var encabezado = `
                    <head>
                        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
                    </head>
                    <body style="font-family: Arial;">
                       <table style="font-size: 14px; border-collapse: collapse;">
                        <tbody>
                            <tr><th style="font-weight: normal;" colspan="4">PEDIDO PLAN</th></tr>
                            <tr height="10px;"></tr>
                            <tr>
                                <td>Fecha:</td>
                                <td style="text-align: right; white-space: nowrap; padding-right: 24px;" colspan="3">`+ dia + "/" + mes + "/" + year + " " + hora + " " + tiempo +`</td>
                            </tr>
                            <tr>
                                <td>Cliente:</td>
                                <td style="text-align: right; white-space: nowrap; padding-right: 24px;" colspan="3">`+ datos.nombre + " " + datos.apellido +`</td>
                            </tr>
                            <tr>
                                <td>NIT/CC:</td>
                                <td style="text-align: right; white-space: nowrap; padding-right: 24px;" colspan="3">`+ ncedula +`</td>
                            </tr>
                            <tr>
                                <td>Dirección:</td>
                                <td style="text-align: right; white-space: nowrap; padding-right: 24px;" colspan="4">`+ direccion_text +`</td>
                            </tr>
                            <tr><td colspan="4"></td></tr>
                            <tr>
                                <td>Teléfono:</td>
                                <td style="text-align: right; white-space: nowrap; padding-right: 24px;" colspan="3">`+ ntelefono +`</td>
                            </tr>
                            <tr>
                                <td>Hora de entrega:</td>
                                <td style="text-align: right; white-space: nowrap; padding-right: 24px;" colspan="3">`+ horaentrega + " " + tiempoEntrega +`</td>
                            </tr>

                            <tr style="border-top: 1px solid #000; border-bottom: 1px solid #000;">
                                <td style="padding: 8px 0;">Descripción</td>
                                <td colspan="3" style="text-align: center;">Cant.</td>
                            </tr>
                        `;
                //PARA CREAR UNA LINEA POR CADA PRODUCTO
                var nProductos = datos.productos.length;
                var producto = "";
                for(x = 0; x < nProductos; x++){
                    producto = producto + "<tr><td>"+ datos.productos[x].name +" <br><strong style=\"margin-left: 10px;\">"+ datos.productos[x].size +"</strong></td><td colspan=\"3\" style=\"text-align: center;\">"+ datos.productos[x].cantidad +"</td></tr>";
                }
                //UNIFICO LOS DATOS DEL CLIENTE CON LOS PRODUCTOS
                encabezado = encabezado + producto;
                //CREO LA VARIABLE DE OBSERVACION
                var observacion_text = "<tr height=\"14px;\"></tr><tr style=\"background-color: #000; color: #fff;\"><td style=\"padding: 6px 0 6px 4px;\" colspan=\"4\">"+ observacion +"</td></tr>";
                //SI LOS DATOS SON MAYORES A 0 ENTONCES SE AÑADE A LA FACTURA
                var medio = "";
                if(!!observacion){
                    medio = medio + observacion_text;
                }
                var anulado = "<tr height=\"20px;\"><td colspan=\"4\" style=\"text-align: center; font-size: 50px; border: 2px solid black;\">PEDIDO ANULADO</td></tr>";

                var factura_estado = datos.factura_estado;
                if(factura_estado == \'Panulado\'){
                    //CONCATENO EL MENSAJE AL FINAL DEL MEDIO DE LA FACTURA
                    medio = medio + anulado;
                }
                var footer = `
                         <tfoot style="font-size: 12px;">
                            <tr height="10px;"></tr>
                            <tr>
                                <td colspan="2" style="text-align: center;">www.feedandfit.co</td>
                                <td colspan="2" style="text-align: center;"><i class="fab fa-whatsapp"></i> 3012553433</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;">Cra 47#84-27 Local 18</td>
                                <td colspan="2" style="text-align: center;"><i class="fab fa-facebook-square"></i> <i class="fab fa-instagram"></i> feedandfit.co</td>
                            </tr>

                        </tfoot>
                    </table>`;

                ventimp.document.write(encabezado + medio + footer);
                ventimp.document.close();
                ventimp.print();
                ventimp.close();
                        }
                     });
      </script>';
    }else if($id_factura > 0){
      
      $fecha = getdate();

      $year = $fecha['year'];
      $dia = $fecha['mday'];
      $mes = $fecha['mon'];

      $hora = $fecha['hours'];
      $minutos = $fecha['minutes'];
      $segundos = $fecha['seconds'];

      $fecha_anulada = $year . "-" . $mes . "-" .  $dia . " " . $hora . ":" . $minutos . ":" . $segundos;

      $set = "UPDATE orders SET factura_estado = 'fanulada', moved_to_sale_history_at = '$fecha_anulada' WHERE id = '$id'";
      $set_query = pg_query($set);

      echo '<script src="../js/jquery.min.js"></script>';
      echo'<script type="text/javascript">
            var url = "ver_pedido.php";
            $.ajax({
              type: "POST",
              url: url,
              data: {data : '.$id.', from: "factura"},
              success: function(data){
                //Recibo los datos en un array
                var datos = JSON.parse(data);
                var fecha = datos.fecha;
                var year = fecha.substring(2, 4);
                var mes = fecha.substring(8, 10);
                var dia = fecha.substring(5, 7);
                var hora = fecha.substring(10, 16);
                var hour = parseInt(hora.substring(0, 3));
                var minutos = hora.substring(4, 6);

                var tiempo = "";
                if(hour > 12){
                    if(minutos > 30){
                        tiempo = "PM";
                    }else{
                        tiempo = "AM";
                    }
                }else{
                    tiempo = "AM";
                }

                var horaentrega = datos.horaentrega;
                var hourEntrega = parseInt(datos.horaentrega.substring(0, 3));
                var minutosEntrega = parseInt(datos.horaentrega.substring(4, 6));
                
                var tiempoEntrega = "";
                if(hourEntrega > 12){
                    if(minutosEntrega > 30){
                        tiempoEntrega = "PM";
                    }else{
                        tiempoEntrega = "AM";
                    }
                }else{
                    tiempoEntrega = "AM";
                }

                //VALIDAR LOS DATOS PARA QUE NO SALGAN NULOS
                //CEDULA
                var cedula = datos.cedula;
                if(!!cedula){
                    var ncedula = cedula;
                }else{
                    var ncedula = "";
                }
                //DIRECCION
                var direccion = datos.direccion;
                if(!!direccion){
                    var direccion_text = direccion;
                }else{
                    var direccion_text = "";
                }
                //TELEFONO
                var telefono = datos.telefono;
                if(!!telefono){
                    var ntelefono = telefono;
                }else{
                    var ntelefono = "";
                }
                var domicilio = datos.domicilio;
                var observacion = datos.observacion;
                var descuento = datos.descuento;
                var x = 0;
      
                //ABRO UNA NUEVA VENTANA Y DENTRO DE ELLA ESCRIBO EL HTML
                var ventimp = window.open(\'\', \'popimpr\');
                //DATOS DEL CLIENTE EN LA FACTURA
                var encabezado = `
                <head>
                    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
                </head>
                <body style="font-family: Arial;">
                   <table style="font-size: 14px; border-collapse: collapse;">
                    <tbody>
                        <tr><th style="font-weight: normal;" colspan="4">FEED AND FIT S.A.S</th></tr>
                        <tr><th style="font-weight: normal;" colspan="4">NIT: 901.202.020-3</th></tr>
                        <tr><th style="font-weight: normal;" colspan="4">REGIMEN COMÚN</th></tr>
                        <tr><th style="font-weight: normal;" colspan="4">Res. DIAN No. 18762013091225 del 2019-02-25</th></tr>
                        <tr><th style="font-weight: normal;" colspan="4">Numeración del 1 al 100000</th></tr>
                        <tr>
                            <td>Fecha:</td>
                            <td style="text-align: right; white-space: nowrap; padding-right: 24px;" colspan="3">`+ dia + "/" + mes + "/" + year + " " + hora + " " + tiempo +`</td>
                        </tr>
                        <tr>
                            <td>Factura de venta No.:</td>
                            <td style="text-align: right; white-space: nowrap; padding-right: 24px;" colspan="3">`+ datos.id_factura +`</td>
                        </tr>
                        <tr>
                            <td>Cliente:</td>
                            <td style="text-align: right; white-space: nowrap; padding-right: 24px;" colspan="3">`+ datos.nombre + " " + datos.apellido +`</td>
                        </tr>
                        <tr>
                            <td>NIT/CC:</td>
                            <td style="text-align: right; white-space: nowrap; padding-right: 24px;" colspan="3">`+ ncedula +`</td>
                        </tr>
                        <tr>
                            <td>Dirección:</td>
                            <td style="text-align: right; white-space: nowrap; padding-right: 24px;" colspan="4">`+ direccion_text +`</td>
                        </tr>
                        <tr><td colspan="4"></td></tr>
                        <tr>
                            <td>Teléfono:</td>
                            <td style="text-align: right; white-space: nowrap; padding-right: 24px;" colspan="3">`+ ntelefono +`</td>
                        </tr>
                        <tr>
                            <td>Hora de entrega:</td>
                            <td style="text-align: right; white-space: nowrap; padding-right: 24px;" colspan="3">`+ horaentrega + " " + tiempoEntrega +`</td>
                        </tr>
                        <tr style="border-top: 1px solid #000; border-bottom: 1px solid #000;">
                            <td style="padding: 8px 0;" colspan="2">Descripción</td>
                            <td>Cant.</td>
                            <td style="text-align: center;">Total</td>
                        </tr>
                        `;
                //PARA CREAR UNA LINEA POR CADA PRODUCTO
                var nProductos = datos.productos.length;
                var producto = "";
                var precioTotal = 0;
                var precio = 0;
                for(x = 0; x < nProductos; x++){
                  //para quitarle los puntos
                  precio = datos.productos[x].precio.replace(/\./g,\'\');
                  precioTotal = datos.productos[x].cantidad * precio;
                  //para ponerle los puntos
                  precioTotal = precioTotal.toString().split(\'\').reverse().join(\'\').replace(/(?=\d*\.?)(\d{3})/g,\'$1.\');
                  precioTotal = precioTotal.split(\'\').reverse().join(\'\').replace(/^[\.]/,\'\');
                  producto = producto + "<tr><td colspan=\"2\">"+ datos.productos[x].name +" <br><strong style=\"margin-left: 10px;\">"+ datos.productos[x].size +"</strong></td><td>"+ datos.productos[x].cantidad +"</td><td style=\"text-align: center;\">$ "+ precioTotal +"</td></tr>";
                }
                //UNIFICO LOS DATOS DEL CLIENTE CON LOS PRODUCTOS
                encabezado = encabezado + producto;
                //CREO LAS VARIABLES DE DOMICILIO, DESCUENTO Y OBSERVACION
                var domicilio_text = "<tr><td colspan=\"2\">Domicilio</td><td></td><td style=\"text-align: center;\">$ " + domicilio + "</td></tr>";
                var descuento_text = "<tr><td colspan=\"2\">Descuento</td><td></td><td style=\"text-align: center;\">$ " + descuento + "</td></tr>";
                var observacion_text = "<tr height=\"14px;\"></tr><tr style=\"background-color: #000; color: #fff;\"><td style=\"padding: 6px 0 6px 4px;\" colspan=\"4\">"+ observacion +"</td></tr>";
                //SI LOS DATOS SON MAYORES A 0 ENTONCES SE AÑADE A LA FACTURA
                var medio = "";
                if(domicilio != 0){
                  medio = domicilio_text;
                }
                if(descuento != 0){
                    medio = medio + descuento_text;
                }
                if(!!observacion){
                    medio = medio + observacion_text;
                }
                //LE AÑADIMOS A LA FACTURA EL TOTAL A PAGAR
                var totalPagar = `
                    <tr height="10px;"></tr>
                        <tr style="border-top: 1px solid #000; border-bottom: 1px solid #000;">
                            <td style="padding: 8px 0;" colspan="2">TOTAL A PAGAR</td>
                            <td></td>
                            <td style="text-align: center;">$ `+ datos.total +`</td>
                        </tr>
                    </tbody>
                    <tr height="20px;">`;
                //CONCATENO LA MITAD DE LA FACTURA CON EL TOTAL A PAGAR
                medio = medio + totalPagar;
                //PARA AÑADIR EL SELLO DE FACTURA ANULADA O FACTURA ANULADA
                //SI EL ESTADO DE LA FACTURA ES ANULADA
                var anulado = "<tr height=\"20px;\"><td colspan=\"4\" style=\"text-align: center; font-size: 50px; border: 2px solid black;\">FACTURA ANULADA</td></tr>";
                //SI EL ESTADO DE LA FACTURA ES PAGADA
                var pagado = "<tr height=\"20px;\"><td colspan=\"4\" style=\"text-align: center; font-size: 50px; border: 2px solid black;\">PAGADO</td></tr>";
                var factura_estado = datos.factura_estado;
                //2 ES EL ID DE EFECTIVO
                if(factura_estado == \'fanulada\'){
                    //CONCATENO EL MENSAJE AL FINAL DEL MEDIO DE LA FACTURA
                    medio = medio + anulado;
                }else if(factura_estado == \'fpagada\'){
                    medio = medio + pagado;
                }
                //PARA LA INFORMACION DEL PIE DE LA FACTURA
                //PARA CALCULAR VALOR BRUTO, NETO E IMPOCONSUMO AL 8%
                //LE QUITO LOS PUNTOS AL SUBTOTAL
                var valorNeto = datos.subtotal.replace(/\./g,\'\');
                //LE QUITO LOS PUNTOS AL DESCUENTO
                var descuento = datos.descuento.replace(/\./g,\'\');
                var valorTotalNeto = valorNeto - descuento;
                //REDONDEO EL VALOR BRUTO
                var bruto = Math.round(valorTotalNeto / 1.08);
                //IMPOCONSUMO
                var impoconsumo = valorTotalNeto - bruto;
                //FORMATEO EL VALOR TOTAL NETO
                valorTotalNeto = valorTotalNeto.toString().split(\'\').reverse().join(\'\').replace(/(?=\d*\.?)(\d{3})/g,\'$1.\');
                valorTotalNeto = valorTotalNeto.split(\'\').reverse().join(\'\').replace(/^[\.]/,\'\');
                //FORMATEO EL IMPOCONSUMO
                impoconsumo = impoconsumo.toString().split(\'\').reverse().join(\'\').replace(/(?=\d*\.?)(\d{3})/g,\'$1.\');
                impoconsumo = impoconsumo.split(\'\').reverse().join(\'\').replace(/^[\.]/,\'\');
                //FORMATEO EL VALOR BRUTO
                bruto = bruto.toString().split(\'\').reverse().join(\'\').replace(/(?=\d*\.?)(\d{3})/g,\'$1.\');
                bruto = bruto.split(\'\').reverse().join(\'\').replace(/^[\.]/,\'\');
            
                var footer = `
                     <tfoot style="font-size: 12px;">
                        <tr>
                            <th style="font-weight: normal; padding-top: 15px;" colspan="4">INFORMACIÓN TRIBUTARIA</th>
                        </tr>
                        <tr>
                            <td style="padding-right: 10px; text-align: center;">V. BRUTO</td>
                            <td style="padding-right: 10px; text-align: center;">IMPOCON. 8%</td>
                            <td style="text-align: center;">V. NETO</td>
                            <td style="text-align: center;">INGR. A TERCERO</td>
                        </tr>
                        <tr>
                            <td style="text-align: center; white-space: nowrap;">$ `+ bruto +`</td>
                            <td style="text-align: center; white-space: nowrap;">$ `+ impoconsumo +`</td>
                            <td style="text-align: center; white-space: nowrap;">$ `+ valorTotalNeto +`</td>
                            <td style="text-align: center; white-space: nowrap;">$ `+ domicilio +`</td>
                        </tr>
                        <tr height="10px;"></tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">www.feedandfit.co</td>
                            <td colspan="2" style="text-align: center;"><i class="fab fa-whatsapp"></i> 3012553433</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">Cra 47#84-27 Local 18</td>
                            <td colspan="2" style="text-align: center;"><i class="fab fa-facebook-square"></i> <i class="fab fa-instagram"></i> feedandfit.co</td>
                        </tr>
                        <tr height="20px;"></tr>
                        <tr>
                            <th colspan="4" style="font-weight: normal;">NO SOMOS AUTORRETENEDORES</th>
                        </tr>
                        <tr>
                            <th colspan="4" style="font-weight: normal;">IMPRESO POR ROLIAGROUP S.A.S</th>
                        </tr>
                        <tr>
                        <th colspan="4" style="font-weight: normal;">NIT: 900297698-1</th>
                        </tr>
                    </tfoot>
                </table>`;
                ventimp.document.write(encabezado + medio + footer);
                ventimp.document.close();
                ventimp.print();
                ventimp.close();
                    }
                 });
      </script>';
    }
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body onfocus="href()">
<script type="text/javascript">
  function href(){
    window.location.href="../../pedidos";
  }
</script>
</body>
</html>
<?php   
} else { header("Location: ../../index"); }

?>