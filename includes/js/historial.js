function cargarDatos(data){
    var datos = JSON.parse(data);
    var mejorProducto = datos[0].mejorProducto;
    $('#mejorProducto').html(mejorProducto);
    var cantidadMejorProducto = datos[0].cantidadMejorProducto;
    $('#cantidadMejorProducto').html(" ("+ cantidadMejorProducto +")");
    var productosVendidos = datos[0].productosVendidos;
    $('#productosVendidos').html(productosVendidos);
    var vendido = datos[0].vendido;
    vendido = vendido.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    vendido = "$ " + vendido.split('').reverse().join('').replace(/^[\.]/,'');
    $('#vendido').html(vendido);
    var mejorZona = datos[0].mejorZona;
    $('#mejorZona').html(mejorZona);
    var tr = "";
    var encabezado = `
    <div class="box" style="padding-top: 15px; padding-bottom: 15px;">
        <table id="examplee" class="table table-striped table-bordered" style="width:100%; font-size: 12px;">
            <thead>
                <tr>
                    <th class="hide"></th>
                    <th>Factura No.</th>
                    <th>Cliente</th>
                    <th>Precio</th>
                    <th>Dirección</th>
                    <th>Forma de Pago</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>

    `;
    var idFactura = "";
    var total = "";
    var paidPlan = "";
    for(var i = 0; i < datos.length; i++){
        idFactura = datos[i].nFactura;
        total = datos[i].total;
        paidPlan = datos[i].paid_plan;

        if(!idFactura){
            idFactura = "";
        }
        total = total.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        if(total != 0){
            total = "$ " + total.split('').reverse().join('').replace(/^[\.]/,'');
        }else{
            total = "";
        }
        if (paidPlan == 't'){
            var plan = `
                <td class="see" id="see">
                    <a href="#" id="`+ datos[i].id +`" data-href="impFacturaOrdenPedido(`+ datos[i].id +`)" data-toggle="modal" data-target="#ver-pedido" title="Ver Orden de Pedido">
                        <i class="far fa-eye"></i>
                    </a>
                </td>
                <td class="print">
                    <a href="#" id="`+ datos[i].id +`" onClick="impFacturaOrdenPedido(`+ datos[i].id +`)" data-href="imprimir_pedido.php?id=`+ datos[i].id +`" data-toggle="modal" data-target="#imprimir-pedido" title="Imprimir factura">
                        <i class="fa fa-print"></i>
                    </a>
                </td>`;
        }else{
            // SI NO TIENE PLAN
            var plan = `
                <td class="see" id="see">
                    <a href="#" id="`+ datos[i].id +`" data-href="impFactura(`+ datos[i].id +`)" data-toggle="modal" data-target="#ver-pedido" title="Ver Pedido">
                            <i class="far fa-eye"></i>
                    </a>
                </td>
                <td class="print">
                    <a href="#" id="`+ datos[i].id +`" onClick="impFactura(`+ datos[i].id +`)" data-href="imprimir_pedido.php?id=`+ datos[i].id +`" data-toggle="modal" data-target="#imprimir-pedido" title="Imprimir factura">
                        <i class="fa fa-print"></i>
                    </a>
                </td>`;
        }
        var fecha = datos[i].fecha;
        var time = datos[i].time;
        var year = fecha.substring(2, 4);
        var dia = fecha.substring(8, 10);
        var mes = fecha.substring(5, 7);
        fecha = dia + "/" + mes + "/" + year + " " + time;
        tr = tr + `<tr>
            <td class="hide">`+ i +`</td>
            <td>`+ idFactura +`</td>
            <td>`+ datos[i].cliente +`</td>
            <td>`+ total +`</td>
            <td>`+ datos[i].direccion +`</td>
            <td>`+ datos[i].payment_method +`</td>
            <td>`+ fecha +`</td>
            <td>`+ datos[i].estado +`</td>
            `+ plan +`
        </tr>`;
    }
            
    var footer = `</tbody></table></div>`;

    $('#contenidoHistorial').html(encabezado + tr + footer);

    $('#examplee').DataTable();

    var load = document.getElementById("contenedor_carga");
    load.style.visibility = 'hidden';
    load.style.opacity = '0';

    setTimeout("$('#carga').remove()",3000);
}

$(document).ready(function() {
	
	var url = "includes/historial/listarHistorial.php";

	$.ajax({
       	type: "POST",
       	url: url,
       	data: {data: "inicial"},
       	success: function (data){
       		cargarDatos(data);
    	}
    });
});

function consultarXfechas(event){ 
    event.preventDefault();
    $('#contenidoHistorial').html(`<div></div>`);
    var load = document.getElementById("contenedor_carga");
    load.style.visibility = 'visible';
    load.style.opacity = '1';
    $('#contenedor_carga').html(`<div id="carga"></div>`);

    var fechaInicial = document.getElementById("fechaInicial").value;
    var fechaFinal = document.getElementById("fechaFinal").value;
    var fechaIniciall = document.getElementById("fechaInicial").value;
    var fechaFinall = document.getElementById("fechaFinal").value;

    fechaInicial = fechaInicial,
        separador = "/",
        arreglo = fechaInicial.split(separador);

    fechaInicial = {
        diaInicial: arreglo[1],
        mesInicial: arreglo[0],
        yearInicial: arreglo[2]
    }

    fechaFinal = fechaFinal,
        separador = "/",
        arreglo = fechaFinal.split(separador);

    fechaFinal = {
        diaFinal: arreglo[1],
        mesFinal: arreglo[0],
        yearFinal: arreglo[2]
    }

    var url = "includes/historial/listarHistorial.php";
    fechaInicial = JSON.stringify(fechaInicial);
    fechaFinal = JSON.stringify(fechaFinal);
    $.ajax({
        type: "POST",
        url: url,
        data: {data: "fechas", fechaInicial: fechaInicial, fechaFinal: fechaFinal},
        success: function(data){
            cargarDatos(data);
            var datos = JSON.parse(data);
            $('#descargar').html('<label>&nbsp;</label><a href="includes/historial/reporteExcel.php?fechaInicial='+ fechaIniciall +'&fechaFinal='+ fechaFinall+'" target="_blank" class="btn btn-info form-control">Descargar</a>');
        }
    });
}

//PARA LAS FECHAS
$(function() {
    $('input[name="datepicker"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 2018,
        maxYear: parseInt(moment().format('YYYY'),10)
    });
});
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
       data: {data : see, from: "historial", historial: "historial"},
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
            data: {data : id, from: "factura", historial: "historial"},
            success: function(data){
                //Recibo los datos en un array
                var datos = JSON.parse(data);
                var fecha = datos.fecha;
                var year = fecha.substring(2, 4);
                var dia = fecha.substring(8, 10);
                var mes = fecha.substring(5, 7);

                var hora = fecha.substring(10, 16);
                var hour = parseInt(hora.substring(0, 3));
                var minutos = hora.substring(4, 6);
            
                var horaentrega = datos.hourentrega;
                var tiempo = datos.time;
                var hourEntrega = parseInt(datos.horaentrega.substring(0, 3));
                var minutosEntrega = parseInt(datos.horaentrega.substring(4, 6));
            
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
                            <td style="text-align: right; white-space: nowrap; padding-right: 24px;" colspan="3">`+ dia + "/" + mes + "/" + year + " " + tiempo +`</td>
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
            if(factura_estado == 'fanulada'){
                //CONCATENO EL MENSAJE AL FINAL DEL MEDIO DE LA FACTURA
                medio = medio + anulado;
            }else if(factura_estado == 'fpagada'){
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
                    }
                 });
    }

    //FACTURA ORDEN DE PEDIDO
        function impFacturaOrdenPedido(id){
            var url = "includes/pedidos/ver_pedido.php";
            $.ajax({
            type: "POST",
            url: url,
            data: {data : id, from: "ordenPedido", historial: "historial"},
            success: function(data){
                //Recibo los datos en un array
                var datos = JSON.parse(data);
                var fecha = datos.fecha;
                var year = fecha.substring(2, 4);
                var dia = fecha.substring(8, 10);
                var mes = fecha.substring(5, 7);

                var hora = fecha.substring(10, 16);
                var hour = parseInt(hora.substring(0, 3));
                var minutos = hora.substring(4, 6);
            
                var horaentrega = datos.hourentrega;
                var tiempo = datos.time;
                var hourEntrega = parseInt(datos.horaentrega.substring(0, 3));
                var minutosEntrega = parseInt(datos.horaentrega.substring(4, 6));
        
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
                                <td style="text-align: right; white-space: nowrap; padding-right: 24px;" colspan="3">`+ dia + "/" + mes + "/" + year + " " + tiempo +`</td>
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
                var factura_estado = datos.factura_estado;
                // PEDIDO ANULADO
                var Panulado = "<tr height=\"20px;\"><td colspan=\"4\" style=\"text-align: center; font-size: 50px; border: 2px solid black;\">PEDIDO ANULADO</td></tr>";
                if(factura_estado == 'Panulado'){
                    medio = medio + Panulado;
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

    }