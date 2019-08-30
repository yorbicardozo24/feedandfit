// Para obtener direccion, hora de entrega y observacion del cliente
function selectId() {
    //Por si el mensaje de alerta existe entonces se elimina
    if ( document.getElementById('alerta') ){
        $('#alerta').remove();
    }
	var idSelect = document.getElementById("select").value;
	var url = "includes/pedidos/new_pedido.php";
	$.ajax({
        async: false,
		type: "POST",
		url: url,
		data: {data: idSelect},
		success: function(data){
			var dato = JSON.parse(data);
			if(dato.length == 1){
				$('#direccion_select').html('<option class="direccion_select" selected="selected" value='+ dato[0].id + '>' + dato[0].direccion + '</option>');
                selectDirection();
			}else{
				var element_direccion = document.getElementById("direccion_select").childElementCount;
				if(element_direccion >= 0){
					$('.direccion_select').remove();
						for(var i=0; i<dato.length; i++){
						  $('#direccion_select').append('<option class="direccion_select" value='+ dato[i].id + '>' + dato[i].direccion + '</option>');
					    }
				}
                var direccion_select = document.getElementsByClassName("direccion_select")[0].selected = "selected";
                selectDirection();
			}
            $('#horaEntrega').val(dato[0].time);
            $('#observacion').val(dato[0].observacion);
		}
	});
}
//Para obtener valor de domicilio del cliente
function selectDirection(){
    var url_domicilio = "includes/pedidos/domicilio.php";
    var id_direccion = document.getElementById("direccion_select").value;
    $.ajax({
        type: "POST",
        url: url_domicilio,
        data: {data: id_direccion},
        success: function(data){
            $('#domicilio').html('$ ' + data);
        }
    });
}
//Para obtener tamaño, objetivo y precio de un producto
function producto() {
    //Por si el mensaje de alerta existe entonces se elimina
    if ( document.getElementById('alerta') ){
        $('#alerta').remove();
    }
    var id_producto = document.getElementById("product").value;
    var url_producto = "includes/pedidos/producto.php";
    $.ajax({
        async: false,
        type: "POST",
        url: url_producto,
        data: {data: id_producto},
        success: function(data){
            var dato = JSON.parse(data);
            //Si es size (tamaño)
            if(dato[0].size){
                // Obtengo tamaños y precios
                var nombre1 = {nombre: dato[0].size,price: dato[0].price, id: dato[0].id};
                var nombre2 = {nombre: dato[1].size,price: dato[1].price, id: dato[1].id};
                var nombre3 = {nombre: dato[2].size,price: dato[2].price, id: dato[2].id};
                //Remueve todas las opciones de el select de tamaño
                $('.select_sizes').remove();
                //Muestra el select de tamaños
                var sizes = document.getElementById("sizes").classList.remove("hide");
                //Remueve todas las opciones de el select de objetivos
                $('.select_targets').remove();
                //Oculta el select de objetivos
                var target = document.getElementById("objetivo").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple').html('');
                //si todos los tamaños son mayores a 0 entonces se acumulan en el select de tamaño
                if(nombre1.price != 0 && nombre2.price != 0 && nombre3.price != 0){
                    //Numeros sin puntos
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    //Inserto las opciones en el select de objetivos
                    $('#sizes_select').append('<option id='+ precio1 +' class="select_sizes" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select').append('<option id='+ precio2 +' class="select_sizes" selected="selected" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#sizes_select').append('<option id='+ precio3 +' class="select_sizes" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    //Coloca el valor de el campo dos ya que esta por defecto
                    $('#product-price').html('$ ' + nombre2.price);
                }
                //Si algun tamaño es mayor a 0 pero los demas no entonces se acumula una sola opcion en el select de tamaño
                if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price == 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    $('#sizes_select').html('<option id='+ precio1 +' class="select_sizes" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#product-price').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price == 0){
                    var precio2 = nombre2.price.replace(/\./g,'');
                    $('#sizes_select').html('<option id='+ precio2 +' class="select_sizes" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#product-price').html('$ ' + nombre2.price);
                }
                if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price > 0){
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select').html('<option id='+ precio3 +' class="select_sizes" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price').html('$ ' + nombre3.price);
                }
                if(nombre1.price > 0 && nombre2.price > 0 && nombre3.price == 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    $('#sizes_select').html('<option id='+ precio1 +' class="select_sizes" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select').append('<option id='+ precio2 +' class="select_sizes" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#product-price').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price > 0){
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select').html('<option id='+ precio2 +' class="select_sizes" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#sizes_select').append('<option id='+ precio3 +' class="select_sizes" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price').html('$ ' + nombre2.price);
                }
                if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price > 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select').html('<option id='+ precio1 +' class="select_sizes" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select').append('<option id='+ precio3 +' class="select_sizes" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0){
                    sizes = document.getElementById("sizes").classList.add("hide");
                    target = document.getElementById("objetivo").classList.add("hide");
                    $('#product-price').html('$ 0');
                }
                var label_precio = document.getElementById("product-price").classList.remove("hide");
                var label_precio_nuevo = document.getElementById("product-price_second").classList.add("hide");
                //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
                cambiarPrecioSelectChange();
                calSubtotal();
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
                //Si es objetivo
            } else if(dato[0].target){
                //Obtengo tamaños y precios
                var nombre1 = {nombre: dato[0].target,price: dato[0].price, id: dato[0].id};
                var nombre2 = {nombre: dato[1].target,price: dato[1].price, id: dato[1].id};
                var nombre3 = {nombre: dato[2].target,price: dato[2].price, id: dato[2].id};
                var nombre4 = {nombre: dato[3].target,price: dato[3].price, id: dato[3].id};
                var nombre5 = {nombre: dato[4].target,price: dato[4].price, id: dato[4].id};
                var nombre6 = {nombre: dato[5].target,price: dato[5].price, id: dato[5].id};
                //Remueve todas las opciones de el select de tamaño
                $('.select_sizes').remove();
                //Oculta el select de tamaño
                var sizes = document.getElementById("sizes").classList.add("hide");
                //Remueve todas las opciones de el select de objetivos
                $('.select_targets').remove();
                //Muestra el select de objetivos
                var target = document.getElementById("objetivo").classList.remove("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple').html('');

                //si todos los objetivos son mayores a 0 entonces se acumulan en el select de tamaño
                if(nombre1.price != '' && nombre2.price != '' && nombre3.price != '' && nombre4.price != '' && nombre5.price != '' && nombre6.price != ''){
                    //Numeros sin puntos
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    var precio4 = nombre4.price.replace(/\./g,'');
                    var precio5 = nombre5.price.replace(/\./g,'');
                    var precio6 = nombre6.price.replace(/\./g,'');
                    $('#targets_select').html('');
                    //Inserto las opciones en el select de objetivos
                    $('#targets_select').append('<option id='+ precio1 +' class="select_targets" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#targets_select').append('<option id='+ precio2 +' class="select_targets" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#targets_select').append('<option id='+ precio3 +' class="select_targets" selected="selected" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#targets_select').append('<option id='+ precio4 +' class="select_targets" value='+ nombre4.id + '>' + nombre4.nombre + '</option>');
                    $('#targets_select').append('<option id='+ precio5 +' class="select_targets" value='+ nombre5.id + '>' + nombre5.nombre + '</option>');
                    $('#targets_select').append('<option id='+ precio6 +' class="select_targets" value='+ nombre6.id + '>' + nombre6.nombre + '</option>');
                    //Coloca el valor de el campo dos ya que esta por defecto
                    $('#product-price').html('$ ' + nombre3.price);
                }
                // //Si algun tamaño es mayor a 0 pero los demas no entonces se acumula una sola opcion en el select de tamaño
                // if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio1 = nombre1.price.replace(/\./g,'');
                //     $('#targets_select').html('<option id='+ precio1 +' class="select_targets" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                //     $('#product-price').html('$ ' + nombre1.price);
                // }
                // if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio2 = nombre2.price.replace(/\./g,'');
                //     $('#targets_select').html('<option id='+ precio2 +' class="select_targets" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                //     $('#product-price').html('$ ' + nombre2.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price > 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio3 = nombre3.price.replace(/\./g,'');
                //     $('#targets_select').html('<option id='+ precio3 +' class="select_targets" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                //     $('#product-price').html('$ ' + nombre3.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price > 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio4 = nombre4.price.replace(/\./g,'');
                //     $('#targets_select').html('<option id='+ precio4 +' class="select_targets" value='+ nombre4.id + '>' + nombre4.nombre + '</option>');
                //     $('#product-price').html('$ ' + nombre4.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price > 0 && nombre6.price == 0){
                //     var precio5 = nombre5.price.replace(/\./g,'');
                //     $('#targets_select').html('<option id='+ precio5 +' class="select_targets" value='+ nombre5.id + '>' + nombre5.nombre + '</option>');
                //     $('#product-price').html('$ ' + nombre5.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price > 0){
                //     var precio6 = nombre6.price.replace(/\./g,'');
                //     $('#targets_select').html('<option id='+ precio6 +' class="select_targets" value='+ nombre6.id + '>' + nombre6.nombre + '</option>');
                //     $('#product-price').html('$ ' + nombre6.price);
                // }
                var label_precio = document.getElementById("product-price").classList.remove("hide");
                var label_precio_nuevo = document.getElementById("product-price_second").classList.add("hide");
                calSubtotal();
                //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
                cambiarPrecioSelectChange();
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
            }else if(dato[0].simple){
                $('.select_sizes').remove();
                var sizes = document.getElementById("sizes").classList.add("hide");
                $('.select_targets').remove();
                var target = document.getElementById("objetivo").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple').html('');
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
                $('#product-price').html('$ ' + dato[0].precio);
                calSubtotal();
            }else{
                //Si no hay tamaño, ni hay objetivo ni hay precio entonces
                //remueve las opciones del select de tamaño y remueve las opciones del select de objetivos
                //tambien deja el precio en 0
                $('.select_sizes').remove();
                var sizes = document.getElementById("sizes").classList.add("hide");
                $('.select_targets').remove();
                var target = document.getElementById("objetivo").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple').html('');
                $('#product-price').html('$ 0');
                calSubtotal();
                alert(dato[0].datos);
            }
        }
    });
}
function precioSize(){
    //Obtengo el id del option seleccionado
    var id_select_size = document.getElementById("sizes_select").selectedOptions[0].id;
    //Formateo el numero con separador de miles
    id_select_size = id_select_size.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    id_select_size = id_select_size.split('').reverse().join('').replace(/^[\.]/,'');
    //Cambio el precio segun el id seleccionado
    $('#product-price').html('$ ' + id_select_size);

    //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
    var label_precio = document.getElementById("product-price").classList.remove("hide");
    var label_precio_nuevo = document.getElementById("product-price_second").classList.add("hide");
    calSubtotal();
    //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
    cambiarPrecioSelectChange();
    adicional();
}
function precioObjetivo(){
    //Obtengo el id del option seleccionado
    var id_select_objetivo = document.getElementById("targets_select").selectedOptions[0].id;
    //Formateo el numero con separador de miles
    id_select_objetivo = id_select_objetivo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    id_select_objetivo = id_select_objetivo.split('').reverse().join('').replace(/^[\.]/,'');
    //Cambio el precio segun el id seleccionado
    $('#product-price').html('$ ' + id_select_objetivo);

    //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
    var label_precio = document.getElementById("product-price").classList.remove("hide");
    var label_precio_nuevo = document.getElementById("product-price_second").classList.add("hide");
    calSubtotal();
    //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
    cambiarPrecioSelectChange();
    adicional();
}
function adicional(){
    var values = $('#multiple').val();
    var nValues = values.length;
    var total = 0;
    for(var i = 0; i<nValues; i++){
        total = total + parseInt(values[i]);
    }
    var sizes = $('#sizes').hasClass('hide');
    var objetivo = $('#objetivo').hasClass('hide');
    var totalNuevo = 0;
    if(nValues > 0){
        $('#cantidadProducto').val(1);
        $('#cantidadProducto').attr('disabled', 'disabled');
    }else{
        $('#cantidadProducto').removeAttr('disabled');
    }
    var cantidadProducto = document.getElementById("cantidadProducto").value;
    if(!sizes){
        var id_select_size = document.getElementById("sizes_select").selectedOptions[0].id;
        id_select_size = parseInt(id_select_size) * cantidadProducto;
        totalNuevo = parseInt(id_select_size) + total;
    }else if(!objetivo){
        var id_select_objetivo = document.getElementById("targets_select").selectedOptions[0].id;
        id_select_objetivo = parseInt(id_select_objetivo) * cantidadProducto;
        totalNuevo = parseInt(id_select_objetivo) + total;
    }else{
        var productPrice = document.getElementById("product-price").innerText;
        productPrice = productPrice.replace(/\./g,'');
        productPrice = productPrice.replace(/\ /g,'');
        productPrice = parseInt(productPrice.replace(/\$/g,''));
        var price = document.getElementById("product-price").classList.add("hide");
        var price_second = document.getElementById("product-price_second").classList.remove("hide");
        var pSimple = productPrice * cantidadProducto;
        totalNuevo = pSimple + total;
    }
    
    totalNuevo = totalNuevo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    totalNuevo = totalNuevo.split('').reverse().join('').replace(/^[\.]/,'');
    var product_price = $('#product-price').hasClass('hide');
    if(product_price){
        $('#product-price_second').html('$ ' + totalNuevo);
    }else{
        $('#product-price').html('$ ' + totalNuevo);
    }
    calSubtotal();
}
function addProduct(from){
    var filas = parseInt(document.getElementById("addNewProduct").lastElementChild.id);
    var fila = parseInt(document.getElementById("addNewProduct").childElementCount);
    if(fila == 1){
        var textoHtml = `
            <div class="col-md-1">
                <a class="borrarProducto" id="borrarProducto`+ filas +`" onClick="eliminarProducto(`+ filas +`)"><i class="fas fa-trash-alt"></i></a>
            </div>
        `;
        $('#' + filas).append(textoHtml);
    }
    $.ajax({
        async: false,
        type: "POST",
        url: "includes/pedidos/listarProductos.php",
        data: {data: fila},
        success: function(data){
            var datos = JSON.parse(data);
            var opcionesProductos = datos.length;
            var arregloFinal = [];
            for(i = 0; i < opcionesProductos; i++){
                arregloFinal.push({
                    id: datos[i].id,
                    nombre: datos[i].nombre
                });
            }
            filas = filas + 1;
    if(fila < 10){
        //NUEVO PEDIDO
        var textoHtml = `
        <div class="row mt20" id="`+filas+`">
            <div class="col-md-3">
                <label>Producto</label>
                <!-- Select para precio del producto -->
                <select class="form-control" id="product`+filas+`" onChange="producto`+filas+`()">
                </select>
            </div>
            <div id="sizes`+filas+`" class="col-md-2 hide">
                <label>Tamaño</label>
                <select class="form-control" id="sizes_select`+filas+`" onChange="precioSize`+filas+`()">
                    <option>Escoge tamaño</option>
                </select>
            </div>
            <div id="objetivo`+filas+`" class="col-md-2 hide">
                <label>Objetivo</label>
                <select class="form-control" id="targets_select`+filas+`" onChange="precioObjetivo`+filas+`()">
                    <option>Elige objetivo</option>
                </select>
            </div>
            <div id="adicional`+filas+`" class="col-md-2 hide">
                <label>Adicional</label>
                <select class="form-control" onChange="adicional`+filas+`()" multiple="multiple" id="multiple`+filas+`" style="width:120px">
                </select>
            </div>
            <div class="col-md-2">
                <label>Cantidad</label>
                <input id="cantidadProducto`+filas+`" class="form-control only-number" type="number" onChange="adicional`+filas+`()" value="1" min="1"/>
            </div>
            <div class="col-md-2 precios">
                <label>Precio</label>
                <div id="product-price`+filas+`" style="padding-top: 5px;">$0</div>
                <div id="product-price_second`+filas+`" style="padding-top: 5px;" class="hide"></div>
            </div>
            <div class="col-md-1">
                <a class="borrarProducto" id="borrarProducto`+filas+`" onClick="eliminarProducto(`+filas+`)"><i class="fas fa-trash-alt"></i></a>
            </div>
        </div>`;
        //NUEVA ORDEN DE PEDIDO
        var textoHtmlNewOrder = `
        <div class="row mt20" id="`+filas+`">
            <div class="col-md-3">
                <label>Producto</label>
                <!-- Select para precio del producto -->
                <select class="form-control" id="product`+filas+`" onChange="producto`+filas+`()">
                </select>
            </div>
            <div id="sizes`+filas+`" class="col-md-2 hide">
                <label>Tamaño</label>
                <select class="form-control" id="sizes_select`+filas+`" onChange="precioSize`+filas+`()">
                    <option>Escoge tamaño</option>
                </select>
            </div>
            <div id="objetivo`+filas+`" class="col-md-2 hide">
                <label>Objetivo</label>
                <select class="form-control" id="targets_select`+filas+`" onChange="precioObjetivo`+filas+`()">
                    <option>Elige objetivo</option>
                </select>
            </div>
            <div id="adicional`+filas+`" class="col-md-2 hide">
                <label>Adicional</label>
                <select class="form-control" onChange="adicional`+filas+`()" multiple="multiple" id="multiple`+filas+`" style="width:120px">
                </select>
            </div>
            <div class="col-md-2">
                <label>Cantidad</label>
                <input id="cantidadProducto`+filas+`" class="form-control only-number" type="number" onChange="adicional`+filas+`()" value="1" min="1"/>
            </div>
            <div class="col-md-2 precios hide">
                <label>Precio</label>
                <div id="product-price`+filas+`" style="padding-top: 5px;">$0</div>
                <div id="product-price_second`+filas+`" style="padding-top: 5px;" class="hide"></div>
            </div>
            <div class="col-md-1">
                <a class="borrarProducto" id="borrarProducto`+filas+`" onClick="eliminarProducto(`+filas+`)"><i class="fas fa-trash-alt"></i></a>
            </div>
        </div>`;
        
        if(from == 'nuevoPedido'){
            $('#addNewProduct').append(textoHtml);
        }else{
            $('#addNewProduct').append(textoHtmlNewOrder);
        }

        var selectProduct = "#product" + filas;
        var multiple = "#multiple" + filas;
        
        for(var x = 0; x < opcionesProductos; x++){
            $(selectProduct).append('<option value="'+arregloFinal[x].id+'">'+arregloFinal[x].nombre+'</option>');
        }
        $(selectProduct).select2();
        $(selectProduct).select2('open');
        $(multiple).select2();
    }else{
        alert('Solo se pueden registrar 10 productos!')
    }
        }
    });
    
    
}

//Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
function cambiarPrecioSelectChange(){
    var cantidad = document.getElementById("cantidadProducto").value;

    if(cantidad > 1){
        var precio = document.getElementById("product-price").textContent;
        precio = precio.replace(/\./g,'');
        precio = precio.replace(/\$/g,'');
        var precionuevo = precio * cantidad;
        //Formateo el numero con separador de miles
        precionuevo = precionuevo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        precionuevo = precionuevo.split('').reverse().join('').replace(/^[\.]/,'');
        //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
        $('#product-price_second').html('$ ' + precionuevo);
        var label_precio = document.getElementById("product-price").classList.add("hide");
        var label_precio_nuevo = document.getElementById("product-price_second").classList.remove("hide");
        calSubtotal();
    }
}


//Para calcular Subtotal
function calSubtotal(){
    var nProduct = parseInt(document.getElementById("addNewProduct").childElementCount);
    var precios = "";
    var arregloPrecios = [];

    for(var i = 0; i < nProduct; i++){
        var precio1 = document.getElementsByClassName("precios")[i].children[1].classList.length;
        var precio2 = document.getElementsByClassName("precios")[i].children[2].classList.length;

        if(precio1 === 0){//no tiene la clase hide
            precios = document.getElementsByClassName("precios")[i].children[1].textContent; 

            precios = precios.replace(/\./g,'');
            precios = precios.replace(/\$/g,'');
            arregloPrecios.push({
                valor: precios
            }); 
        }

        if(precio2 === 0){//no tiene la clase hide
            precios2 = document.getElementsByClassName("precios")[i].children[2].textContent; 

            precios2 = precios2.replace(/\./g,'');
            precios2 = precios2.replace(/\$/g,'');
            arregloPrecios.push({
                valor: precios2
            }); 
        }
    }
    var cantidadValores = arregloPrecios.length;
    var subtotalFinal = 0;
    for(var x = 0; x < cantidadValores; x++){
        subtotalFinal = subtotalFinal + parseInt(arregloPrecios[x].valor);
    }
    $('#subtotalHide').val(subtotalFinal);
    var metodoDescuento = $('input:radio[name=radios]:checked').val();

    if(metodoDescuento == 'porcentaje'){
        var descuentoPorcentaje = $('#descuento').val();
        if(descuentoPorcentaje != ''){
            descuentoPorcentaje = descuentoPorcentaje / 100;
            var descuentoRestar = descuentoPorcentaje * subtotalFinal;
            subtotalFinal = subtotalFinal - descuentoRestar;
        }
    }else{
        var descuentoNormal = $('#descuento').val();
        if(descuentoNormal != ''){
            var descuentoTotal = descuentoNormal.replace(/\./g,'');
            subtotalFinal = subtotalFinal - descuentoTotal;
        }
    }
    
    //Formateo el numero con separador de miles
    subtotalFinal = subtotalFinal.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    subtotalFinal = subtotalFinal.split('').reverse().join('').replace(/^[\.]/,'');
    $('#subtotal').html('$ ' + subtotalFinal);
}