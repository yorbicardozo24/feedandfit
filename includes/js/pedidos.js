function domicilio(id, text){
	var url = "includes/pedidos/domicilioSolicitado.php";
    // var ventana = window.open("https://www.w3schools.com");
	$.ajax({
    	type: "POST",
       	url: url,
       	data: {data : id},
       	success: function(data){
        	var recargar = data;
        	if(recargar > 0){
        		location.reload();
        	}
    	}
    });
    myWindow = window.open("https://web.whatsapp.com/send?phone=573006640264&text="+text, "" , "width=650,height=400");
}
window.onfocus=function(){ if(myWindow){myWindow.close();}}
//Ver pedido
$('#ver-pedido').on('show.bs.modal', function(e) {
	$(this).find('.btn-ok').attr('onClick', $(e.relatedTarget).data('href'));
    
	$('.debug-url').html('Delete URL: <strong>' + $(this).find(
		 '.btn-ok').attr('href') + '</strong>');

    var see = e.relatedTarget.id;
    var url = "includes/pedidos/ver_pedido.php";

    $.ajax({
       type: "POST",
       url: url,
       data: {data : see, from: "ver", historial: "no"},
       success: function(data){
        var datos = JSON.parse(data);
        
        $('#idOrdenPedido').html('# ' + datos.id);
        $('#nombreOrdenPedido').html(datos.nombre + " " + datos.apellido);
        $('#fecha').html(datos.fecha);
        $('#direccionCliente').html(datos.direccion);
        $('#observacionCliente').html(datos.observacion);
        //VALIDO SI LOS CAMPOS NO SON NULOS
        //EMAIL
        var email = datos.email;
        if(!!email){
            var emailCliente = email;
        }else{
            var emailCliente = "";
        }
        //TELEFONO
        var telefono = datos.telefono;
        if(!!telefono){
            var telefonoCliente = telefono;
        }else{
            var telefonoCliente = "";
        }

        $('#correoCliente').html('<strong>Correo:</strong>' + ' ' + emailCliente);
        $('#telefonoCliente').html('<strong>Teléfono:</strong>' + ' ' + telefonoCliente);
        $('#contentProduct').html('');
        var nProducto = datos.productos.length;
        var valorTotal = 0;

        //PARA LOS PLANES
        var paidPlan = datos.paid_plan;
        if(paidPlan == 't'){
            //SI ES PLAN ENTONCES OCULTA LOS PRECIOS
            var valorUnitario = document.getElementById("valorUnitario").classList.add("hide");
            var valorTotal = document.getElementById("valorTotal").classList.add("hide");
            var totales = document.getElementById("totales").classList.add("hide");
            
            for(var i = 0; i < nProducto; i++){
                valorTotal = datos.productos[i].precio.replace(/\./g,'') * datos.productos[i].cantidad;

                valorTotal = valorTotal.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
                valorTotal = valorTotal.split('').reverse().join('').replace(/^[\.]/,'');
                $('#contentProduct').append('<tr><td>'+ datos.productos[i].cantidad + '</td><td>'+ datos.productos[i].name +'</td></tr>');

                var nAdiciones = datos.productos[i].adiciones.length;
                if(nAdiciones > 0){
                    var contenido = "";
                    for(var x = 0; x<nAdiciones; x++){
                        contenido = contenido + `
                            <tr>
                                <td style="font-size: 12px;">Adicional</td>
                                <td style="font-size: 12px;">`+ datos.productos[i].adiciones[x].nombre +`</td>
                            </tr>
                        `;
                    }
                    
                    $('#contentProduct').append(contenido);
                }
            }
            
        }else{
            var valorUnitario = document.getElementById("valorUnitario").classList.remove("hide");
            var valorTotal = document.getElementById("valorTotal").classList.remove("hide");
            var totales = document.getElementById("totales").classList.remove("hide");
            
            //SI NO ES PLAN ENTONCES MUESTRA LOS PRECIOS
            for(var i = 0; i < nProducto; i++){
                valorTotal = datos.productos[i].precio.replace(/\./g,'') * datos.productos[i].cantidad;

                valorTotal = valorTotal.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
                valorTotal = valorTotal.split('').reverse().join('').replace(/^[\.]/,'');
                $('#contentProduct').append('<tr><td>'+ datos.productos[i].cantidad + '</td><td>'+ datos.productos[i].name +'</td><td> $ '+ datos.productos[i].precio +'</td><td> $ '+ valorTotal +'</td></tr>');

                var nAdiciones = datos.productos[i].adiciones.length;
                if(nAdiciones > 0){
                    var contenido = "";
                    var precio = 0;
                    for(var x = 0; x<nAdiciones; x++){
                        precio = datos.productos[i].adiciones[x].precio;
                        precio = precio.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
                        precio = precio.split('').reverse().join('').replace(/^[\.]/,'');
                        if(precio == 0){
                            precio = "";
                        }else{
                            precio = " $ " + precio;
                        }
                        contenido = contenido + `
                            <tr>
                                <td style="font-size: 12px;">Adicional</td>
                                <td style="font-size: 12px;">`+ datos.productos[i].adiciones[x].nombre +`</td>
                                <td style="font-size: 12px;">`+ precio +`</td>
                                <td style="font-size: 12px;"></td>
                            </tr>
                        `;
                    }
                    
                    $('#contentProduct').append(contenido);
                }
            }

            $('#domicilio').html('$ ' + datos.domicilio);
            $('#descuento').html('- $ ' + datos.descuento);
            $('#subtotal').html('$ ' + datos.subtotal);
            $('#total').html('$ ' + datos.total);
        }
       }
     });
 });
//Imprimir factura
//FACTURA NORMAL
function impFactura(id){
		var url = "includes/pedidos/ver_pedido.php";
		$.ajax({
        type: "POST",
        url: url,
        data: {data : id, from: "factura", historial: "no"},
        success: function(data){
            //Recibo los datos en un array
            var datos = JSON.parse(data);
            var fecha = datos.fecha;
            var year = fecha.substring(2, 4);
            var dia = fecha.substring(8, 10);
            var mes = fecha.substring(5, 7);
            var time = datos.time;
            var hora = fecha.substring(10, 16);
            var hour = parseInt(hora.substring(0, 3));
            var minutos = hora.substring(4, 6);
            
            var horaentrega = datos.hourentrega;
            
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
            var observacion = datos.observacionPedido;
            var descuento = datos.descuento;
            var x = 0;
            //ABRO UNA NUEVA VENTANA Y DENTRO DE ELLA ESCRIBO EL HTML
			var ventimp = window.open('', 'popimpr');
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
                            <td style="text-align: right; white-space: nowrap; padding-right: 24px;" colspan="3">`+ dia + "/" + mes + "/" + year + " " + time +`</td>
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
                            <td style="text-align: right; white-space: nowrap; padding-right: 24px;" colspan="3">`+ horaentrega +`</td>
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
                precio = datos.productos[x].precio.replace(/\./g,'');
                precioTotal = datos.productos[x].cantidad * precio;
                //para ponerle los puntos
                precioTotal = precioTotal.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
                precioTotal = precioTotal.split('').reverse().join('').replace(/^[\.]/,'');
                var size = datos.productos[x].size;
                if(size == 'simple'){
                    size = "";
                }
                producto = producto + "<tr><td colspan=\"2\">"+ datos.productos[x].name +" <br><strong style=\"margin-left: 10px;\">"+ size +"</strong></td><td>"+ datos.productos[x].cantidad +"</td><td style=\"text-align: center;\">$ "+ precioTotal +"</td></tr>";
                var nAdiciones = datos.productos[x].adiciones.length;
                if(nAdiciones > 0){
                    var contenido = "";
                    var precio = 0;
                    for(var a = 0; a<nAdiciones; a++){
                        precio = datos.productos[x].adiciones[a].precio;
                        precio = precio.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
                        precio = precio.split('').reverse().join('').replace(/^[\.]/,'');
                        if(precio == 0){
                            precio = "";
                        }else{
                            precio = "$ " + precio;
                        }
                        contenido = contenido + `
                            <tr>
                                <td colspan="2"><strong style="margin-left: 20px;">`+ datos.productos[x].adiciones[a].nombre +`</strong></td>
                                <td></td>
                                <td style="text-align: center;">`+ precio +`</td>
                            </tr>
                        `;
                    }
                    
                    producto = producto + contenido;
                }
            }
            //UNIFICO LOS DATOS DEL CLIENTE CON LOS PRODUCTOS
            encabezado = encabezado + producto;
            //CREO LAS VARIABLES DE DOMICILIO, DESCUENTO Y OBSERVACION
            var domicilio_text = "<tr><td colspan=\"2\">Domicilio</td><td></td><td style=\"text-align: center;\">$ " + domicilio + "</td></tr>";
            var descuento_text = "<tr><td colspan=\"2\">Descuento</td><td></td><td style=\"text-align: center;\">- $ " + descuento + "</td></tr>";
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

            //PARA AÑADIR EL SELLO DE PAGADO O FACTURA ANULADA
            //SI EL METODO DE PAGO NO ES EN EFECTIVO ENTONCES EN LA FACTURA VA EL MENSAJE DE PAGADO
            var pagado = "<tr height=\"20px;\"><td colspan=\"4\" style=\"text-align: center; font-size: 50px; border: 2px solid black;\">PAGADO</td></tr>";

            var formapago = datos.formapago;
            //2 ES EL ID DE EFECTIVO
            if(formapago != 2){
                //CONCATENO EL MENSAJE AL FINAL DEL MEDIO DE LA FACTURA
                medio = medio + pagado;
            }

            //PARA LA INFORMACION DEL PIE DE LA FACTURA
            //PARA CALCULAR VALOR BRUTO, NETO E IMPOCONSUMO AL 8%
            //LE QUITO LOS PUNTOS AL SUBTOTAL
            var valorNeto = datos.subtotal.replace(/\./g,'');
            //LE QUITO LOS PUNTOS AL DESCUENTO
            var descuento = datos.descuento.replace(/\./g,'');
            var valorTotalNeto = valorNeto - descuento;
            //REDONDEO EL VALOR BRUTO
            var bruto = Math.round(valorTotalNeto / 1.08);
            //IMPOCONSUMO
            var impoconsumo = valorTotalNeto - bruto;
            //FORMATEO EL VALOR TOTAL NETO
            valorTotalNeto = valorTotalNeto.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
            valorTotalNeto = valorTotalNeto.split('').reverse().join('').replace(/^[\.]/,'');
            //FORMATEO EL IMPOCONSUMO
            impoconsumo = impoconsumo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
            impoconsumo = impoconsumo.split('').reverse().join('').replace(/^[\.]/,'');
            //FORMATEO EL VALOR BRUTO
            bruto = bruto.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
            bruto = bruto.split('').reverse().join('').replace(/^[\.]/,'');
        
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
            var recargar = datos.recargar;
            if(recargar > 0){
                location.reload();
            }
					}
				 });

}

//FACTURA ORDEN DE PEDIDO
    function impFacturaOrdenPedido(id){
        var url = "includes/pedidos/ver_pedido.php";
        $.ajax({
        type: "POST",
        url: url,
        data: {data : id, from: "ordenPedido", historial: "no"},
        success: function(data){
            //Recibo los datos en un array
            var datos = JSON.parse(data);
            var fecha = datos.fecha;
            var year = fecha.substring(2, 4);
            var dia = fecha.substring(8, 10);
            var mes = fecha.substring(5, 7);
            var time = datos.time;
            var hora = fecha.substring(10, 16);
            var hour = parseInt(hora.substring(0, 3));
            var minutos = hora.substring(4, 6);
            
            var horaentrega = datos.hourentrega;
            
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
            var observacion = datos.observacionPedido;
            var descuento = datos.descuento;
            var x = 0;
            //ABRO UNA NUEVA VENTANA Y DENTRO DE ELLA ESCRIBO EL HTML
            var ventimp = window.open('', 'popimpr');
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
                            <td style="text-align: right; white-space: nowrap; padding-right: 24px;" colspan="3">`+ dia + "/" + mes + "/" + year + " " + time +`</td>
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
                            <td style="text-align: right; white-space: nowrap; padding-right: 24px;" colspan="3">`+ horaentrega +`</td>
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
                var size = datos.productos[x].size;
                if(size == 'simple'){
                    size = "";
                }
                producto = producto + "<tr><td>"+ datos.productos[x].name +" <br><strong style=\"margin-left: 10px;\">"+ size +"</strong></td><td colspan=\"3\" style=\"text-align: center;\">"+ datos.productos[x].cantidad +"</td></tr>";
                var nAdiciones = datos.productos[x].adiciones.length;
                if(nAdiciones > 0){
                    var contenido = "";
                    var precio = 0;
                    for(var a = 0; a<nAdiciones; a++){
                        contenido = contenido + `
                            <tr>
                                <td colspan="2"><strong style="margin-left: 20px;">`+ datos.productos[x].adiciones[a].nombre +`</strong></td>
                                <td></td>
                                <td style="text-align: center;"></td>
                            </tr>
                        `;
                    }
                    
                    producto = producto + contenido;
                }
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
            var recargar = datos.recargar;
            if(recargar > 0){
                location.reload();
            }
                    }
                 });

}
//Eliminar
 $('#confirm-delete').on('show.bs.modal', function(e) {
	 $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));

	 $('.debug-url').html('Delete URL: <strong>' + $(this).find(
		 '.btn-ok').attr('href') + '</strong>');
 });


$(document).ready(function() {
    $('#example').dataTable({
    "paging": false,
    "aaSorting":[[7,"asc"]]
});
//     function changeColor() {
//     //Para saber cuantas ordenes hay
//     var nOrdenes = document.getElementById("ordenes").childElementCount;
//     //Para capturar la hora de entrega de la orden
//     var hora = document.getElementById("ordenes");
//     var horaEntrega = "";
//     //Para el tiempo actual
//     var timeActual = new Date();
//     var horaActual = timeActual.getHours();
//     var minutoActual = timeActual.getMinutes();

//     switch(minutoActual){
//         case 0:
//             minutoActual = '00';
//         case 1:
//             minutoActual = '01';
//             break;
//         case 2:
//             minutoActual = "02";
//             break;
//         case 3:
//             minutoActual = "03";
//             break;
//         case 4:
//             minutoActual = "04";
//             break;
//         case 5:
//             minutoActual = "05";
//             break;
//         case 6:
//             minutoActual = "06";
//             break;
//         case 7:
//             minutoActual = "07";
//             break;
//         case 8:
//             minutoActual = "08";
//             break;
//         case 9:
//             minutoActual = "09";
//             break;
//     }

//     var horaEntregaActual = horaActual + ':' + minutoActual;

//     var horaEntregaUser = "";
//     var minutosEntregaUser = "";

//     var horaActualSistema = horaActual;
//     var minutoActualSistema = minutoActual;

//     for(var i = 0; i < nOrdenes; i++){
//         horaEntrega = hora.children[i].children[6].innerText;
//         //Para validar los minutos
//         minutosEntregaUser = parseInt(horaEntrega.substring(3));
//         //Para validar la misma hora
//         horaEntregaUser = parseInt(horaEntrega.substring(0, 2));

//         //Validaciones
//         if(horaEntregaUser == horaActualSistema){
//         }

//     }
// }
// setInterval(changeColor, 3000);
});