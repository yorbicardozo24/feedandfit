//Para obtener tamaño, objetivo y precio de un producto
function producto2(){
    var id_producto = document.getElementById("product2").value;
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
                $('.select_sizes2').remove();
                //Muestra el select de tamaños
                var sizes = document.getElementById("sizes2").classList.remove("hide");
                //Remueve todas las opciones de el select de objetivos
                $('.select_targets2').remove();
                //Oculta el select de objetivos
                var target = document.getElementById("objetivo2").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional2").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple2').html('');
                //si todos los tamaños son mayores a 0 entonces se acumulan en el select de tamaño
                if(nombre1.price != 0 && nombre2.price != 0 && nombre3.price != 0){
                    //Numeros sin puntos
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    //Inserto las opciones en el select de objetivos
                    $('#sizes_select2').append('<option id='+ precio1 +' class="select_sizes2" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select2').append('<option id='+ precio2 +' class="select_sizes2" selected="selected" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#sizes_select2').append('<option id='+ precio3 +' class="select_sizes2" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    //Coloca el valor de el campo dos ya que esta por defecto
                    $('#product-price2').html('$ ' + nombre2.price);
                }
                //Si algun tamaño es mayor a 0 pero los demas no entonces se acumula una sola opcion en el select de tamaño
                if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price == 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    $('#sizes_select2').html('<option id='+ precio1 +' class="select_sizes2" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#product-price2').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price == 0){
                    var precio2 = nombre2.price.replace(/\./g,'');
                    $('#sizes_select2').html('<option id='+ precio2 +' class="select_sizes2" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#product-price2').html('$ ' + nombre2.price);
                }
                if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price > 0){
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select2').html('<option id='+ precio3 +' class="select_sizes2" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price2').html('$ ' + nombre3.price);
                }
                if(nombre1.price > 0 && nombre2.price > 0 && nombre3.price == 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    $('#sizes_select2').html('<option id='+ precio1 +' class="select_sizes2" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select2').append('<option id='+ precio2 +' class="select_sizes2" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#product-price2').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price > 0){
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select2').html('<option id='+ precio2 +' class="select_sizes2" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#sizes_select2').append('<option id='+ precio3 +' class="select_sizes2" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price2').html('$ ' + nombre2.price);
                }
                if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price > 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select2').html('<option id='+ precio1 +' class="select_sizes2" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select2').append('<option id='+ precio3 +' class="select_sizes2" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price2').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0){
                    sizes = document.getElementById("sizes2").classList.add("hide");
                    target = document.getElementById("objetivo2").classList.add("hide");
                    $('#product-price2').html('$ 0');
                }
                var label_precio = document.getElementById("product-price2").classList.remove("hide");
                var label_precio_nuevo = document.getElementById("product-price_second2").classList.add("hide");
                //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
                cambiarPrecioSelectChange2();
                calSubtotal();
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional2").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple2').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
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
                $('.select_sizes2').remove();
                //Oculta el select de tamaño
                var sizes = document.getElementById("sizes2").classList.add("hide");
                //Remueve todas las opciones de el select de objetivos
                $('.select_targets2').remove();
                //Muestra el select de objetivos
                var target = document.getElementById("objetivo2").classList.remove("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional2").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple2').html('');
                //si todos los objetivos son mayores a 0 entonces se acumulan en el select de tamaño
                if(nombre1.price != '' && nombre2.price != '' && nombre3.price != '' && nombre4.price != '' && nombre5.price != '' && nombre6.price != ''){
                    //Numeros sin puntos
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    var precio4 = nombre4.price.replace(/\./g,'');
                    var precio5 = nombre5.price.replace(/\./g,'');
                    var precio6 = nombre6.price.replace(/\./g,'');
                    $('#targets_select2').html('');
                    //Inserto las opciones en el select de objetivos
                    $('#targets_select2').append('<option id='+ precio1 +' class="select_targets2" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#targets_select2').append('<option id='+ precio2 +' class="select_targets2" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#targets_select2').append('<option id='+ precio3 +' class="select_targets2" selected="selected" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#targets_select2').append('<option id='+ precio4 +' class="select_targets2" value='+ nombre4.id + '>' + nombre4.nombre + '</option>');
                    $('#targets_select2').append('<option id='+ precio5 +' class="select_targets2" value='+ nombre5.id + '>' + nombre5.nombre + '</option>');
                    $('#targets_select2').append('<option id='+ precio6 +' class="select_targets2" value='+ nombre6.id + '>' + nombre6.nombre + '</option>');
                    //Coloca el valor de el campo dos ya que esta por defecto
                    $('#product-price2').html('$ ' + nombre3.price);
                }
                // //Si algun tamaño es mayor a 0 pero los demas no entonces se acumula una sola opcion en el select de tamaño
                // if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio1 = nombre1.price.replace(/\./g,'');
                //     $('#targets_select2').html('<option id='+ precio1 +' class="select_targets2" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                //     $('#product-price2').html('$ ' + nombre1.price);
                // }
                // if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio2 = nombre2.price.replace(/\./g,'');
                //     $('#targets_select2').html('<option id='+ precio2 +' class="select_targets2" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                //     $('#product-price2').html('$ ' + nombre2.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price > 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio3 = nombre3.price.replace(/\./g,'');
                //     $('#targets_select2').html('<option id='+ precio3 +' class="select_targets2" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                //     $('#product-price2').html('$ ' + nombre3.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price > 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio4 = nombre4.price.replace(/\./g,'');
                //     $('#targets_select2').html('<option id='+ precio4 +' class="select_targets2" value='+ nombre4.id + '>' + nombre4.nombre + '</option>');
                //     $('#product-price2').html('$ ' + nombre4.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price > 0 && nombre6.price == 0){
                //     var precio5 = nombre5.price.replace(/\./g,'');
                //     $('#targets_select2').html('<option id='+ precio5 +' class="select_targets2" value='+ nombre5.id + '>' + nombre5.nombre + '</option>');
                //     $('#product-price2').html('$ ' + nombre5.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price > 0){
                //     var precio6 = nombre6.price.replace(/\./g,'');
                //     $('#targets_select2').html('<option id='+ precio6 +' class="select_targets2" value='+ nombre6.id + '>' + nombre6.nombre + '</option>');
                //     $('#product-price2').html('$ ' + nombre6.price);
                // }
                var label_precio = document.getElementById("product-price2").classList.remove("hide");
                var label_precio_nuevo = document.getElementById("product-price_second2").classList.add("hide");
                calSubtotal();
                //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
                cambiarPrecioSelectChange2();
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional2").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple2').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
            }else if(dato[0].simple){
                $('.select_sizes2').remove();
                var sizes = document.getElementById("sizes2").classList.add("hide");
                $('.select_targets2').remove();
                var target = document.getElementById("objetivo2").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional2").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple2').html('');
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional2").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple2').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
                $('#product-price2').html('$ ' + dato[0].precio);
                calSubtotal();
            }else{
                //Si no hay tamaño, ni hay objetivo ni hay precio entonces
                //remueve las opciones del select de tamaño y remueve las opciones del select de objetivos
                //tambien deja el precio en 0
                $('.select_sizes2').remove();
                var sizes = document.getElementById("sizes2").classList.add("hide");
                $('.select_targets2').remove();
                var target = document.getElementById("objetivo2").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional2").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple2').html('');
                $('#product-price2').html('$ 0');
                calSubtotal();
                alert(dato[0].datos);
            }
        }
    });
}
function precioSize2(){
    //Obtengo el id del option seleccionado
    var id_select_size = document.getElementById("sizes_select2").selectedOptions[0].id;
    //Formateo el numero con separador de miles
    id_select_size = id_select_size.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    id_select_size = id_select_size.split('').reverse().join('').replace(/^[\.]/,'');
    //Cambio el precio segun el id seleccionado
    $('#product-price2').html('$ ' + id_select_size);

    //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
    var label_precio = document.getElementById("product-price2").classList.remove("hide");
    var label_precio_nuevo = document.getElementById("product-price_second2").classList.add("hide");
    calSubtotal();
    //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
    cambiarPrecioSelectChange2();
    adicional2();
}
function precioObjetivo2(){
    //Obtengo el id del option seleccionado
    var id_select_objetivo = document.getElementById("targets_select2").selectedOptions[0].id;
    //Formateo el numero con separador de miles
    id_select_objetivo = id_select_objetivo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    id_select_objetivo = id_select_objetivo.split('').reverse().join('').replace(/^[\.]/,'');
    //Cambio el precio segun el id seleccionado
    $('#product-price2').html('$ ' + id_select_objetivo);

    //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
    var label_precio = document.getElementById("product-price2").classList.remove("hide");
    var label_precio_nuevo = document.getElementById("product-price_second2").classList.add("hide");
    calSubtotal();
    //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
    cambiarPrecioSelectChange2();
    adicional2();
}
function adicional2(){
    var values = $('#multiple2').val();
    var nValues = values.length;
    var total = 0;
    for(var i = 0; i<nValues; i++){
        total = total + parseInt(values[i]);
    }
    var sizes = $('#sizes2').hasClass('hide');
    var objetivo = $('#objetivo2').hasClass('hide');
    var totalNuevo = 0;
    if(nValues > 0){
        $('#cantidadProducto2').val(1);
        $('#cantidadProducto2').attr('disabled', 'disabled');
    }else{
        $('#cantidadProducto2').removeAttr('disabled');
    }
    var cantidadProducto = document.getElementById("cantidadProducto2").value;
    if(!sizes){
        var id_select_size = document.getElementById("sizes_select2").selectedOptions[0].id;
        id_select_size = parseInt(id_select_size) * cantidadProducto;
        totalNuevo = parseInt(id_select_size) + total;
    }else if(!objetivo){
        var id_select_objetivo = document.getElementById("targets_select2").selectedOptions[0].id;
        id_select_objetivo = parseInt(id_select_objetivo) * cantidadProducto;
        totalNuevo = parseInt(id_select_objetivo) + total;
    }else{
        var productPrice = document.getElementById("product-price2").innerText;
        productPrice = productPrice.replace(/\./g,'');
        productPrice = productPrice.replace(/\ /g,'');
        productPrice = parseInt(productPrice.replace(/\$/g,''));
        var price = document.getElementById("product-price2").classList.add("hide");
        var price_second = document.getElementById("product-price_second2").classList.remove("hide");
        var pSimple = productPrice * cantidadProducto;
        totalNuevo = pSimple + total;
    }
    
    totalNuevo = totalNuevo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    totalNuevo = totalNuevo.split('').reverse().join('').replace(/^[\.]/,'');
    var product_price = $('#product-price2').hasClass('hide');
    if(product_price){
        $('#product-price_second2').html('$ ' + totalNuevo);
    }else{
        $('#product-price2').html('$ ' + totalNuevo);
    }
    calSubtotal();
}
//Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
function cambiarPrecioSelectChange2(){
    var cantidad = document.getElementById("cantidadProducto2").value;

    if(cantidad > 1){
        var precio = document.getElementById("product-price2").textContent;
        precio = precio.replace(/\./g,'');
        precio = precio.replace(/\$/g,'');
        var precionuevo = precio * cantidad;
        //Formateo el numero con separador de miles
        precionuevo = precionuevo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        precionuevo = precionuevo.split('').reverse().join('').replace(/^[\.]/,'');
        //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
        $('#product-price_second2').html('$ ' + precionuevo);
        var label_precio = document.getElementById("product-price2").classList.add("hide");
        var label_precio_nuevo = document.getElementById("product-price_second2").classList.remove("hide");
        calSubtotal();
    }
}








////////////////////////3







//Para obtener tamaño, objetivo y precio de un producto
function producto3(){
    var id_producto = document.getElementById("product3").value;
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
                $('.select_sizes3').remove();
                //Muestra el select de tamaños
                var sizes = document.getElementById("sizes3").classList.remove("hide");
                //Remueve todas las opciones de el select de objetivos
                $('.select_targets3').remove();
                //Oculta el select de objetivos
                var target = document.getElementById("objetivo3").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional3").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple3').html('');
                //si todos los tamaños son mayores a 0 entonces se acumulan en el select de tamaño
                if(nombre1.price != 0 && nombre2.price != 0 && nombre3.price != 0){
                    //Numeros sin puntos
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    //Inserto las opciones en el select de objetivos
                    $('#sizes_select3').append('<option id='+ precio1 +' class="select_sizes3" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select3').append('<option id='+ precio2 +' class="select_sizes3" selected="selected" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#sizes_select3').append('<option id='+ precio3 +' class="select_sizes3" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    //Coloca el valor de el campo dos ya que esta por defecto
                    $('#product-price3').html('$ ' + nombre2.price);
                }
                //Si algun tamaño es mayor a 0 pero los demas no entonces se acumula una sola opcion en el select de tamaño
                if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price == 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    $('#sizes_select3').html('<option id='+ precio1 +' class="select_sizes3" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#product-price3').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price == 0){
                    var precio2 = nombre2.price.replace(/\./g,'');
                    $('#sizes_select3').html('<option id='+ precio2 +' class="select_sizes3" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#product-price3').html('$ ' + nombre2.price);
                }
                if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price > 0){
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select3').html('<option id='+ precio3 +' class="select_sizes3" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price3').html('$ ' + nombre3.price);
                }
                if(nombre1.price > 0 && nombre2.price > 0 && nombre3.price == 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    $('#sizes_select3').html('<option id='+ precio1 +' class="select_sizes3" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select3').append('<option id='+ precio2 +' class="select_sizes3" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#product-price3').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price > 0){
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select3').html('<option id='+ precio2 +' class="select_sizes3" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#sizes_select3').append('<option id='+ precio3 +' class="select_sizes3" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price3').html('$ ' + nombre2.price);
                }
                if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price > 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select3').html('<option id='+ precio1 +' class="select_sizes3" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select3').append('<option id='+ precio3 +' class="select_sizes3" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price3').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0){
                    sizes = document.getElementById("sizes3").classList.add("hide");
                    target = document.getElementById("objetivo3").classList.add("hide");
                    $('#product-price3').html('$ 0');
                }
                var label_precio = document.getElementById("product-price3").classList.remove("hide");
                var label_precio_nuevo = document.getElementById("product-price_second3").classList.add("hide");
                //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
                cambiarPrecioSelectChange3();
                calSubtotal();
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional3").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple3').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
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
                $('.select_sizes3').remove();
                //Oculta el select de tamaño
                var sizes = document.getElementById("sizes3").classList.add("hide");
                //Remueve todas las opciones de el select de objetivos
                $('.select_targets3').remove();
                //Muestra el select de objetivos
                var target = document.getElementById("objetivo3").classList.remove("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional3").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple3').html('');
                //si todos los objetivos son mayores a 0 entonces se acumulan en el select de tamaño
                if(nombre1.price != '' && nombre2.price != '' && nombre3.price != '' && nombre4.price != '' && nombre5.price != '' && nombre6.price != ''){
                    //Numeros sin puntos
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    var precio4 = nombre4.price.replace(/\./g,'');
                    var precio5 = nombre5.price.replace(/\./g,'');
                    var precio6 = nombre6.price.replace(/\./g,'');
                    $('#targets_select3').html('');
                    //Inserto las opciones en el select de objetivos
                    $('#targets_select3').append('<option id='+ precio1 +' class="select_targets3" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#targets_select3').append('<option id='+ precio2 +' class="select_targets3" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#targets_select3').append('<option id='+ precio3 +' class="select_targets3" selected="selected" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#targets_select3').append('<option id='+ precio4 +' class="select_targets3" value='+ nombre4.id + '>' + nombre4.nombre + '</option>');
                    $('#targets_select3').append('<option id='+ precio5 +' class="select_targets3" value='+ nombre5.id + '>' + nombre5.nombre + '</option>');
                    $('#targets_select3').append('<option id='+ precio6 +' class="select_targets3" value='+ nombre6.id + '>' + nombre6.nombre + '</option>');
                    //Coloca el valor de el campo dos ya que esta por defecto
                    $('#product-price3').html('$ ' + nombre3.price);
                }
                // //Si algun tamaño es mayor a 0 pero los demas no entonces se acumula una sola opcion en el select de tamaño
                // if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio1 = nombre1.price.replace(/\./g,'');
                //     $('#targets_select3').html('<option id='+ precio1 +' class="select_targets3" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                //     $('#product-price3').html('$ ' + nombre1.price);
                // }
                // if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio2 = nombre2.price.replace(/\./g,'');
                //     $('#targets_select3').html('<option id='+ precio2 +' class="select_targets3" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                //     $('#product-price3').html('$ ' + nombre2.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price > 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio3 = nombre3.price.replace(/\./g,'');
                //     $('#targets_select3').html('<option id='+ precio3 +' class="select_targets3" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                //     $('#product-price3').html('$ ' + nombre3.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price > 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio4 = nombre4.price.replace(/\./g,'');
                //     $('#targets_select3').html('<option id='+ precio4 +' class="select_targets3" value='+ nombre4.id + '>' + nombre4.nombre + '</option>');
                //     $('#product-price3').html('$ ' + nombre4.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price > 0 && nombre6.price == 0){
                //     var precio5 = nombre5.price.replace(/\./g,'');
                //     $('#targets_select3').html('<option id='+ precio5 +' class="select_targets3" value='+ nombre5.id + '>' + nombre5.nombre + '</option>');
                //     $('#product-price3').html('$ ' + nombre5.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price > 0){
                //     var precio6 = nombre6.price.replace(/\./g,'');
                //     $('#targets_select3').html('<option id='+ precio6 +' class="select_targets3" value='+ nombre6.id + '>' + nombre6.nombre + '</option>');
                //     $('#product-price3').html('$ ' + nombre6.price);
                // }
                var label_precio = document.getElementById("product-price3").classList.remove("hide");
                var label_precio_nuevo = document.getElementById("product-price_second3").classList.add("hide");
                calSubtotal();
                //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
                cambiarPrecioSelectChange3();
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional3").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple3').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
            }else if(dato[0].simple){
                $('.select_sizes3').remove();
                var sizes = document.getElementById("sizes3").classList.add("hide");
                $('.select_targets3').remove();
                var target = document.getElementById("objetivo3").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional3").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple3').html('');
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional3").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple3').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
                $('#product-price3').html('$ ' + dato[0].precio);
                calSubtotal();
            }else{
                //Si no hay tamaño, ni hay objetivo ni hay precio entonces
                //remueve las opciones del select de tamaño y remueve las opciones del select de objetivos
                //tambien deja el precio en 0
                $('.select_sizes3').remove();
                var sizes = document.getElementById("sizes3").classList.add("hide");
                $('.select_targets3').remove();
                var target = document.getElementById("objetivo3").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional3").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple3').html('');
                $('#product-price3').html('$ 0');
                calSubtotal();
                alert(dato[0].datos);
            }
        }
    });
}
function precioSize3(){
    //Obtengo el id del option seleccionado
    var id_select_size = document.getElementById("sizes_select3").selectedOptions[0].id;
    //Formateo el numero con separador de miles
    id_select_size = id_select_size.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    id_select_size = id_select_size.split('').reverse().join('').replace(/^[\.]/,'');
    //Cambio el precio segun el id seleccionado
    $('#product-price3').html('$ ' + id_select_size);

    //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
    var label_precio = document.getElementById("product-price3").classList.remove("hide");
    var label_precio_nuevo = document.getElementById("product-price_second3").classList.add("hide");
    calSubtotal();
    //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
    cambiarPrecioSelectChange3();
    adicional3();
}
function precioObjetivo3(){
    //Obtengo el id del option seleccionado
    var id_select_objetivo = document.getElementById("targets_select3").selectedOptions[0].id;
    //Formateo el numero con separador de miles
    id_select_objetivo = id_select_objetivo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    id_select_objetivo = id_select_objetivo.split('').reverse().join('').replace(/^[\.]/,'');
    //Cambio el precio segun el id seleccionado
    $('#product-price3').html('$ ' + id_select_objetivo);

    //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
    var label_precio = document.getElementById("product-price3").classList.remove("hide");
    var label_precio_nuevo = document.getElementById("product-price_second3").classList.add("hide");
    calSubtotal();
    //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
    cambiarPrecioSelectChange3();
    adicional3();
}
function adicional3(){
    var values = $('#multiple3').val();
    var nValues = values.length;
    var total = 0;
    for(var i = 0; i<nValues; i++){
        total = total + parseInt(values[i]);
    }
    var sizes = $('#sizes3').hasClass('hide');
    var objetivo = $('#objetivo3').hasClass('hide');
    var totalNuevo = 0;
    if(nValues > 0){
        $('#cantidadProducto3').val(1);
        $('#cantidadProducto3').attr('disabled', 'disabled');
    }else{
        $('#cantidadProducto3').removeAttr('disabled');
    }
    var cantidadProducto = document.getElementById("cantidadProducto3").value;
    if(!sizes){
        var id_select_size = document.getElementById("sizes_select3").selectedOptions[0].id;
        id_select_size = parseInt(id_select_size) * cantidadProducto;
        totalNuevo = parseInt(id_select_size) + total;
    }else if(!objetivo){
        var id_select_objetivo = document.getElementById("targets_select3").selectedOptions[0].id;
        id_select_objetivo = parseInt(id_select_objetivo) * cantidadProducto;
        totalNuevo = parseInt(id_select_objetivo) + total;
    }else{
        var productPrice = document.getElementById("product-price3").innerText;
        productPrice = productPrice.replace(/\./g,'');
        productPrice = productPrice.replace(/\ /g,'');
        productPrice = parseInt(productPrice.replace(/\$/g,''));
        var price = document.getElementById("product-price3").classList.add("hide");
        var price_second = document.getElementById("product-price_second3").classList.remove("hide");
        var pSimple = productPrice * cantidadProducto;
        totalNuevo = pSimple + total;
    }
    
    totalNuevo = totalNuevo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    totalNuevo = totalNuevo.split('').reverse().join('').replace(/^[\.]/,'');
    var product_price = $('#product-price3').hasClass('hide');
    if(product_price){
        $('#product-price_second3').html('$ ' + totalNuevo);
    }else{
        $('#product-price3').html('$ ' + totalNuevo);
    }
    calSubtotal();
}
//Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
function cambiarPrecioSelectChange3(){
    var cantidad = document.getElementById("cantidadProducto3").value;

    if(cantidad > 1){
        var precio = document.getElementById("product-price3").textContent;
        precio = precio.replace(/\./g,'');
        precio = precio.replace(/\$/g,'');
        var precionuevo = precio * cantidad;
        //Formateo el numero con separador de miles
        precionuevo = precionuevo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        precionuevo = precionuevo.split('').reverse().join('').replace(/^[\.]/,'');
        //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
        $('#product-price_second3').html('$ ' + precionuevo);
        var label_precio = document.getElementById("product-price3").classList.add("hide");
        var label_precio_nuevo = document.getElementById("product-price_second3").classList.remove("hide");
        calSubtotal();
    }
}




//////////////4




//Para obtener tamaño, objetivo y precio de un producto
function producto4(){
    var id_producto = document.getElementById("product4").value;
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
                $('.select_sizes4').remove();
                //Muestra el select de tamaños
                var sizes = document.getElementById("sizes4").classList.remove("hide");
                //Remueve todas las opciones de el select de objetivos
                $('.select_targets4').remove();
                //Oculta el select de objetivos
                var target = document.getElementById("objetivo4").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional4").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple4').html('');
                //si todos los tamaños son mayores a 0 entonces se acumulan en el select de tamaño
                if(nombre1.price != 0 && nombre2.price != 0 && nombre3.price != 0){
                    //Numeros sin puntos
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    //Inserto las opciones en el select de objetivos
                    $('#sizes_select4').append('<option id='+ precio1 +' class="select_sizes4" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select4').append('<option id='+ precio2 +' class="select_sizes4" selected="selected" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#sizes_select4').append('<option id='+ precio3 +' class="select_sizes4" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    //Coloca el valor de el campo dos ya que esta por defecto
                    $('#product-price4').html('$ ' + nombre2.price);
                }
                //Si algun tamaño es mayor a 0 pero los demas no entonces se acumula una sola opcion en el select de tamaño
                if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price == 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    $('#sizes_select4').html('<option id='+ precio1 +' class="select_sizes4" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#product-price4').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price == 0){
                    var precio2 = nombre2.price.replace(/\./g,'');
                    $('#sizes_select4').html('<option id='+ precio2 +' class="select_sizes4" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#product-price4').html('$ ' + nombre2.price);
                }
                if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price > 0){
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select4').html('<option id='+ precio3 +' class="select_sizes4" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price4').html('$ ' + nombre3.price);
                }
                if(nombre1.price > 0 && nombre2.price > 0 && nombre3.price == 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    $('#sizes_select4').html('<option id='+ precio1 +' class="select_sizes4" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select4').append('<option id='+ precio2 +' class="select_sizes4" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#product-price4').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price > 0){
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select4').html('<option id='+ precio2 +' class="select_sizes4" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#sizes_select4').append('<option id='+ precio3 +' class="select_sizes4" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price4').html('$ ' + nombre2.price);
                }
                if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price > 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select4').html('<option id='+ precio1 +' class="select_sizes4" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select4').append('<option id='+ precio3 +' class="select_sizes4" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price4').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0){
                    sizes = document.getElementById("sizes4").classList.add("hide");
                    target = document.getElementById("objetivo4").classList.add("hide");
                    $('#product-price4').html('$ 0');
                }
                var label_precio = document.getElementById("product-price4").classList.remove("hide");
                var label_precio_nuevo = document.getElementById("product-price_second4").classList.add("hide");
                //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
                cambiarPrecioSelectChange4();
                calSubtotal();
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional4").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple4').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
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
                $('.select_sizes4').remove();
                //Oculta el select de tamaño
                var sizes = document.getElementById("sizes4").classList.add("hide");
                //Remueve todas las opciones de el select de objetivos
                $('.select_targets4').remove();
                //Muestra el select de objetivos
                var target = document.getElementById("objetivo4").classList.remove("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional4").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple4').html('');
                //si todos los objetivos son mayores a 0 entonces se acumulan en el select de tamaño
                if(nombre1.price != '' && nombre2.price != '' && nombre3.price != '' && nombre4.price != '' && nombre5.price != '' && nombre6.price != ''){
                    //Numeros sin puntos
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    var precio4 = nombre4.price.replace(/\./g,'');
                    var precio5 = nombre5.price.replace(/\./g,'');
                    var precio6 = nombre6.price.replace(/\./g,'');
                    $('#targets_select4').html('');
                    //Inserto las opciones en el select de objetivos
                    $('#targets_select4').append('<option id='+ precio1 +' class="select_targets4" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#targets_select4').append('<option id='+ precio2 +' class="select_targets4" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#targets_select4').append('<option id='+ precio3 +' class="select_targets4" selected="selected" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#targets_select4').append('<option id='+ precio4 +' class="select_targets4" value='+ nombre4.id + '>' + nombre4.nombre + '</option>');
                    $('#targets_select4').append('<option id='+ precio5 +' class="select_targets4" value='+ nombre5.id + '>' + nombre5.nombre + '</option>');
                    $('#targets_select4').append('<option id='+ precio6 +' class="select_targets4" value='+ nombre6.id + '>' + nombre6.nombre + '</option>');
                    //Coloca el valor de el campo dos ya que esta por defecto
                    $('#product-price4').html('$ ' + nombre3.price);
                }
                // //Si algun tamaño es mayor a 0 pero los demas no entonces se acumula una sola opcion en el select de tamaño
                // if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio1 = nombre1.price.replace(/\./g,'');
                //     $('#targets_select4').html('<option id='+ precio1 +' class="select_targets4" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                //     $('#product-price4').html('$ ' + nombre1.price);
                // }
                // if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio2 = nombre2.price.replace(/\./g,'');
                //     $('#targets_select4').html('<option id='+ precio2 +' class="select_targets4" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                //     $('#product-price4').html('$ ' + nombre2.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price > 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio3 = nombre3.price.replace(/\./g,'');
                //     $('#targets_select4').html('<option id='+ precio3 +' class="select_targets4" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                //     $('#product-price4').html('$ ' + nombre3.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price > 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio4 = nombre4.price.replace(/\./g,'');
                //     $('#targets_select4').html('<option id='+ precio4 +' class="select_targets4" value='+ nombre4.id + '>' + nombre4.nombre + '</option>');
                //     $('#product-price4').html('$ ' + nombre4.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price > 0 && nombre6.price == 0){
                //     var precio5 = nombre5.price.replace(/\./g,'');
                //     $('#targets_select4').html('<option id='+ precio5 +' class="select_targets4" value='+ nombre5.id + '>' + nombre5.nombre + '</option>');
                //     $('#product-price4').html('$ ' + nombre5.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price > 0){
                //     var precio6 = nombre6.price.replace(/\./g,'');
                //     $('#targets_select4').html('<option id='+ precio6 +' class="select_targets4" value='+ nombre6.id + '>' + nombre6.nombre + '</option>');
                //     $('#product-price4').html('$ ' + nombre6.price);
                // }
                var label_precio = document.getElementById("product-price4").classList.remove("hide");
                var label_precio_nuevo = document.getElementById("product-price_second4").classList.add("hide");
                calSubtotal();
                //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
                cambiarPrecioSelectChange4();
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional4").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple4').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
            }else if(dato[0].simple){
                $('.select_sizes4').remove();
                var sizes = document.getElementById("sizes4").classList.add("hide");
                $('.select_targets4').remove();
                var target = document.getElementById("objetivo4").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional4").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple4').html('');
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional4").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple4').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
                $('#product-price4').html('$ ' + dato[0].precio);
                calSubtotal();
            }else{
                //Si no hay tamaño, ni hay objetivo ni hay precio entonces
                //remueve las opciones del select de tamaño y remueve las opciones del select de objetivos
                //tambien deja el precio en 0
                $('.select_sizes4').remove();
                var sizes = document.getElementById("sizes4").classList.add("hide");
                $('.select_targets4').remove();
                var target = document.getElementById("objetivo4").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional4").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple4').html('');
                $('#product-price4').html('$ 0');
                calSubtotal();
                alert(dato[0].datos);
            }
        }
    });
}
function precioSize4(){
    //Obtengo el id del option seleccionado
    var id_select_size = document.getElementById("sizes_select4").selectedOptions[0].id;
    //Formateo el numero con separador de miles
    id_select_size = id_select_size.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    id_select_size = id_select_size.split('').reverse().join('').replace(/^[\.]/,'');
    //Cambio el precio segun el id seleccionado
    $('#product-price4').html('$ ' + id_select_size);

    //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
    var label_precio = document.getElementById("product-price4").classList.remove("hide");
    var label_precio_nuevo = document.getElementById("product-price_second4").classList.add("hide");
    calSubtotal();
    //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
    cambiarPrecioSelectChange4();
    adicional4();
}
function precioObjetivo4(){
    //Obtengo el id del option seleccionado
    var id_select_objetivo = document.getElementById("targets_select4").selectedOptions[0].id;
    //Formateo el numero con separador de miles
    id_select_objetivo = id_select_objetivo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    id_select_objetivo = id_select_objetivo.split('').reverse().join('').replace(/^[\.]/,'');
    //Cambio el precio segun el id seleccionado
    $('#product-price4').html('$ ' + id_select_objetivo);

    //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
    var label_precio = document.getElementById("product-price4").classList.remove("hide");
    var label_precio_nuevo = document.getElementById("product-price_second4").classList.add("hide");
    calSubtotal();
    //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
    cambiarPrecioSelectChange4();
    adicional4();
}
function adicional4(){
    var values = $('#multiple4').val();
    var nValues = values.length;
    var total = 0;
    for(var i = 0; i<nValues; i++){
        total = total + parseInt(values[i]);
    }
    var sizes = $('#sizes4').hasClass('hide');
    var objetivo = $('#objetivo4').hasClass('hide');
    var totalNuevo = 0;
    if(nValues > 0){
        $('#cantidadProducto4').val(1);
        $('#cantidadProducto4').attr('disabled', 'disabled');
    }else{
        $('#cantidadProducto4').removeAttr('disabled');
    }
    var cantidadProducto = document.getElementById("cantidadProducto4").value;
    if(!sizes){
        var id_select_size = document.getElementById("sizes_select4").selectedOptions[0].id;
        id_select_size = parseInt(id_select_size) * cantidadProducto;
        totalNuevo = parseInt(id_select_size) + total;
    }else if(!objetivo){
        var id_select_objetivo = document.getElementById("targets_select4").selectedOptions[0].id;
        id_select_objetivo = parseInt(id_select_objetivo) * cantidadProducto;
        totalNuevo = parseInt(id_select_objetivo) + total;
    }else{
        var productPrice = document.getElementById("product-price4").innerText;
        productPrice = productPrice.replace(/\./g,'');
        productPrice = productPrice.replace(/\ /g,'');
        productPrice = parseInt(productPrice.replace(/\$/g,''));
        var price = document.getElementById("product-price4").classList.add("hide");
        var price_second = document.getElementById("product-price_second4").classList.remove("hide");
        var pSimple = productPrice * cantidadProducto;
        totalNuevo = pSimple + total;
    }
    
    totalNuevo = totalNuevo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    totalNuevo = totalNuevo.split('').reverse().join('').replace(/^[\.]/,'');
    var product_price = $('#product-price4').hasClass('hide');
    if(product_price){
        $('#product-price_second4').html('$ ' + totalNuevo);
    }else{
        $('#product-price4').html('$ ' + totalNuevo);
    }
    calSubtotal();
}
//Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
function cambiarPrecioSelectChange4(){
    var cantidad = document.getElementById("cantidadProducto4").value;

    if(cantidad > 1){
        var precio = document.getElementById("product-price4").textContent;
        precio = precio.replace(/\./g,'');
        precio = precio.replace(/\$/g,'');
        var precionuevo = precio * cantidad;
        //Formateo el numero con separador de miles
        precionuevo = precionuevo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        precionuevo = precionuevo.split('').reverse().join('').replace(/^[\.]/,'');
        //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
        $('#product-price_second4').html('$ ' + precionuevo);
        var label_precio = document.getElementById("product-price4").classList.add("hide");
        var label_precio_nuevo = document.getElementById("product-price_second4").classList.remove("hide");
        calSubtotal();
    }
}


//////////////5





//Para obtener tamaño, objetivo y precio de un producto
function producto5(){
    var id_producto = document.getElementById("product5").value;
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
                $('.select_sizes5').remove();
                //Muestra el select de tamaños
                var sizes = document.getElementById("sizes5").classList.remove("hide");
                //Remueve todas las opciones de el select de objetivos
                $('.select_targets5').remove();
                //Oculta el select de objetivos
                var target = document.getElementById("objetivo5").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional5").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple5').html('');
                //si todos los tamaños son mayores a 0 entonces se acumulan en el select de tamaño
                if(nombre1.price != 0 && nombre2.price != 0 && nombre3.price != 0){
                    //Numeros sin puntos
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    //Inserto las opciones en el select de objetivos
                    $('#sizes_select5').append('<option id='+ precio1 +' class="select_sizes5" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select5').append('<option id='+ precio2 +' class="select_sizes5" selected="selected" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#sizes_select5').append('<option id='+ precio3 +' class="select_sizes5" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    //Coloca el valor de el campo dos ya que esta por defecto
                    $('#product-price5').html('$ ' + nombre2.price);
                }
                //Si algun tamaño es mayor a 0 pero los demas no entonces se acumula una sola opcion en el select de tamaño
                if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price == 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    $('#sizes_select5').html('<option id='+ precio1 +' class="select_sizes5" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#product-price5').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price == 0){
                    var precio2 = nombre2.price.replace(/\./g,'');
                    $('#sizes_select5').html('<option id='+ precio2 +' class="select_sizes5" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#product-price5').html('$ ' + nombre2.price);
                }
                if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price > 0){
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select5').html('<option id='+ precio3 +' class="select_sizes5" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price5').html('$ ' + nombre3.price);
                }
                if(nombre1.price > 0 && nombre2.price > 0 && nombre3.price == 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    $('#sizes_select5').html('<option id='+ precio1 +' class="select_sizes5" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select5').append('<option id='+ precio2 +' class="select_sizes5" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#product-price5').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price > 0){
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select5').html('<option id='+ precio2 +' class="select_sizes5" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#sizes_select5').append('<option id='+ precio3 +' class="select_sizes5" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price5').html('$ ' + nombre2.price);
                }
                if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price > 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select5').html('<option id='+ precio1 +' class="select_sizes5" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select5').append('<option id='+ precio3 +' class="select_sizes5" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price5').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0){
                    sizes = document.getElementById("sizes5").classList.add("hide");
                    target = document.getElementById("objetivo5").classList.add("hide");
                    $('#product-price5').html('$ 0');
                }
                var label_precio = document.getElementById("product-price5").classList.remove("hide");
                var label_precio_nuevo = document.getElementById("product-price_second5").classList.add("hide");
                //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
                cambiarPrecioSelectChange5();
                calSubtotal();
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional5").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple5').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
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
                $('.select_sizes5').remove();
                //Oculta el select de tamaño
                var sizes = document.getElementById("sizes5").classList.add("hide");
                //Remueve todas las opciones de el select de objetivos
                $('.select_targets5').remove();
                //Muestra el select de objetivos
                var target = document.getElementById("objetivo5").classList.remove("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional5").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple5').html('');
                //si todos los objetivos son mayores a 0 entonces se acumulan en el select de tamaño
                if(nombre1.price != '' && nombre2.price != '' && nombre3.price != '' && nombre4.price != '' && nombre5.price != '' && nombre6.price != ''){
                    //Numeros sin puntos
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    var precio4 = nombre4.price.replace(/\./g,'');
                    var precio5 = nombre5.price.replace(/\./g,'');
                    var precio6 = nombre6.price.replace(/\./g,'');
                    $('#targets_select5').html('');
                    //Inserto las opciones en el select de objetivos
                    $('#targets_select5').append('<option id='+ precio1 +' class="select_targets5" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#targets_select5').append('<option id='+ precio2 +' class="select_targets5" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#targets_select5').append('<option id='+ precio3 +' class="select_targets5" selected="selected" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#targets_select5').append('<option id='+ precio4 +' class="select_targets5" value='+ nombre4.id + '>' + nombre4.nombre + '</option>');
                    $('#targets_select5').append('<option id='+ precio5 +' class="select_targets5" value='+ nombre5.id + '>' + nombre5.nombre + '</option>');
                    $('#targets_select5').append('<option id='+ precio6 +' class="select_targets5" value='+ nombre6.id + '>' + nombre6.nombre + '</option>');
                    //Coloca el valor de el campo dos ya que esta por defecto
                    $('#product-price5').html('$ ' + nombre3.price);
                }
                // //Si algun tamaño es mayor a 0 pero los demas no entonces se acumula una sola opcion en el select de tamaño
                // if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio1 = nombre1.price.replace(/\./g,'');
                //     $('#targets_select5').html('<option id='+ precio1 +' class="select_targets5" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                //     $('#product-price5').html('$ ' + nombre1.price);
                // }
                // if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio2 = nombre2.price.replace(/\./g,'');
                //     $('#targets_select5').html('<option id='+ precio2 +' class="select_targets5" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                //     $('#product-price5').html('$ ' + nombre2.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price > 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio3 = nombre3.price.replace(/\./g,'');
                //     $('#targets_select5').html('<option id='+ precio3 +' class="select_targets5" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                //     $('#product-price5').html('$ ' + nombre3.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price > 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio4 = nombre4.price.replace(/\./g,'');
                //     $('#targets_select5').html('<option id='+ precio4 +' class="select_targets5" value='+ nombre4.id + '>' + nombre4.nombre + '</option>');
                //     $('#product-price5').html('$ ' + nombre4.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price > 0 && nombre6.price == 0){
                //     var precio5 = nombre5.price.replace(/\./g,'');
                //     $('#targets_select5').html('<option id='+ precio5 +' class="select_targets5" value='+ nombre5.id + '>' + nombre5.nombre + '</option>');
                //     $('#product-price5').html('$ ' + nombre5.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price > 0){
                //     var precio6 = nombre6.price.replace(/\./g,'');
                //     $('#targets_select5').html('<option id='+ precio6 +' class="select_targets5" value='+ nombre6.id + '>' + nombre6.nombre + '</option>');
                //     $('#product-price5').html('$ ' + nombre6.price);
                // }
                var label_precio = document.getElementById("product-price5").classList.remove("hide");
                var label_precio_nuevo = document.getElementById("product-price_second5").classList.add("hide");
                calSubtotal();
                //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
                cambiarPrecioSelectChange5();
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional5").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple5').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
            }else if(dato[0].simple){
                $('.select_sizes5').remove();
                var sizes = document.getElementById("sizes5").classList.add("hide");
                $('.select_targets5').remove();
                var target = document.getElementById("objetivo5").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional5").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple5').html('');
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional5").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple5').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
                $('#product-price5').html('$ ' + dato[0].precio);
                calSubtotal();
            }else{
                //Si no hay tamaño, ni hay objetivo ni hay precio entonces
                //remueve las opciones del select de tamaño y remueve las opciones del select de objetivos
                //tambien deja el precio en 0
                $('.select_sizes5').remove();
                var sizes = document.getElementById("sizes5").classList.add("hide");
                $('.select_targets5').remove();
                var target = document.getElementById("objetivo5").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional5").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple5').html('');
                $('#product-price5').html('$ 0');
                calSubtotal();
                alert(dato[0].datos);
            }
        }
    });
}
function precioSize5(){
    //Obtengo el id del option seleccionado
    var id_select_size = document.getElementById("sizes_select5").selectedOptions[0].id;
    //Formateo el numero con separador de miles
    id_select_size = id_select_size.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    id_select_size = id_select_size.split('').reverse().join('').replace(/^[\.]/,'');
    //Cambio el precio segun el id seleccionado
    $('#product-price5').html('$ ' + id_select_size);

    //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
    var label_precio = document.getElementById("product-price5").classList.remove("hide");
    var label_precio_nuevo = document.getElementById("product-price_second5").classList.add("hide");
    calSubtotal();
    //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
    cambiarPrecioSelectChange5();
    adicional5();
}
function precioObjetivo5(){
    //Obtengo el id del option seleccionado
    var id_select_objetivo = document.getElementById("targets_select5").selectedOptions[0].id;
    //Formateo el numero con separador de miles
    id_select_objetivo = id_select_objetivo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    id_select_objetivo = id_select_objetivo.split('').reverse().join('').replace(/^[\.]/,'');
    //Cambio el precio segun el id seleccionado
    $('#product-price5').html('$ ' + id_select_objetivo);

    //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
    var label_precio = document.getElementById("product-price5").classList.remove("hide");
    var label_precio_nuevo = document.getElementById("product-price_second5").classList.add("hide");
    calSubtotal();
    //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
    cambiarPrecioSelectChange5();
    adicional5();
}
function adicional5(){
    var values = $('#multiple5').val();
    var nValues = values.length;
    var total = 0;
    for(var i = 0; i<nValues; i++){
        total = total + parseInt(values[i]);
    }
    var sizes = $('#sizes5').hasClass('hide');
    var objetivo = $('#objetivo5').hasClass('hide');
    var totalNuevo = 0;
    if(nValues > 0){
        $('#cantidadProducto5').val(1);
        $('#cantidadProducto5').attr('disabled', 'disabled');
    }else{
        $('#cantidadProducto5').removeAttr('disabled');
    }
    var cantidadProducto = document.getElementById("cantidadProducto5").value;
    if(!sizes){
        var id_select_size = document.getElementById("sizes_select5").selectedOptions[0].id;
        id_select_size = parseInt(id_select_size) * cantidadProducto;
        totalNuevo = parseInt(id_select_size) + total;
    }else if(!objetivo){
        var id_select_objetivo = document.getElementById("targets_select5").selectedOptions[0].id;
        id_select_objetivo = parseInt(id_select_objetivo) * cantidadProducto;
        totalNuevo = parseInt(id_select_objetivo) + total;
    }else{
        var productPrice = document.getElementById("product-price5").innerText;
        productPrice = productPrice.replace(/\./g,'');
        productPrice = productPrice.replace(/\ /g,'');
        productPrice = parseInt(productPrice.replace(/\$/g,''));
        var price = document.getElementById("product-price5").classList.add("hide");
        var price_second = document.getElementById("product-price_second5").classList.remove("hide");
        var pSimple = productPrice * cantidadProducto;
        totalNuevo = pSimple + total;
    }
    
    totalNuevo = totalNuevo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    totalNuevo = totalNuevo.split('').reverse().join('').replace(/^[\.]/,'');
    var product_price = $('#product-price5').hasClass('hide');
    if(product_price){
        $('#product-price_second5').html('$ ' + totalNuevo);
    }else{
        $('#product-price5').html('$ ' + totalNuevo);
    }
    calSubtotal();
}
//Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
function cambiarPrecioSelectChange5(){
    var cantidad = document.getElementById("cantidadProducto5").value;

    if(cantidad > 1){
        var precio = document.getElementById("product-price5").textContent;
        precio = precio.replace(/\./g,'');
        precio = precio.replace(/\$/g,'');
        var precionuevo = precio * cantidad;
        //Formateo el numero con separador de miles
        precionuevo = precionuevo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        precionuevo = precionuevo.split('').reverse().join('').replace(/^[\.]/,'');
        //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
        $('#product-price_second5').html('$ ' + precionuevo);
        var label_precio = document.getElementById("product-price5").classList.add("hide");
        var label_precio_nuevo = document.getElementById("product-price_second5").classList.remove("hide");
        calSubtotal();
    }
}




//////////////6





//Para obtener tamaño, objetivo y precio de un producto
function producto6(){
    var id_producto = document.getElementById("product6").value;
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
                $('.select_sizes6').remove();
                //Muestra el select de tamaños
                var sizes = document.getElementById("sizes6").classList.remove("hide");
                //Remueve todas las opciones de el select de objetivos
                $('.select_targets6').remove();
                //Oculta el select de objetivos
                var target = document.getElementById("objetivo6").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional6").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple6').html('');
                //si todos los tamaños son mayores a 0 entonces se acumulan en el select de tamaño
                if(nombre1.price != 0 && nombre2.price != 0 && nombre3.price != 0){
                    //Numeros sin puntos
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    //Inserto las opciones en el select de objetivos
                    $('#sizes_select6').append('<option id='+ precio1 +' class="select_sizes6" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select6').append('<option id='+ precio2 +' class="select_sizes6" selected="selected" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#sizes_select6').append('<option id='+ precio3 +' class="select_sizes6" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    //Coloca el valor de el campo dos ya que esta por defecto
                    $('#product-price6').html('$ ' + nombre2.price);
                }
                //Si algun tamaño es mayor a 0 pero los demas no entonces se acumula una sola opcion en el select de tamaño
                if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price == 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    $('#sizes_select6').html('<option id='+ precio1 +' class="select_sizes6" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#product-price6').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price == 0){
                    var precio2 = nombre2.price.replace(/\./g,'');
                    $('#sizes_select6').html('<option id='+ precio2 +' class="select_sizes6" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#product-price6').html('$ ' + nombre2.price);
                }
                if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price > 0){
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select6').html('<option id='+ precio3 +' class="select_sizes6" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price6').html('$ ' + nombre3.price);
                }
                if(nombre1.price > 0 && nombre2.price > 0 && nombre3.price == 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    $('#sizes_select6').html('<option id='+ precio1 +' class="select_sizes6" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select6').append('<option id='+ precio2 +' class="select_sizes6" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#product-price6').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price > 0){
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select6').html('<option id='+ precio2 +' class="select_sizes6" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#sizes_select6').append('<option id='+ precio3 +' class="select_sizes6" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price6').html('$ ' + nombre2.price);
                }
                if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price > 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select6').html('<option id='+ precio1 +' class="select_sizes6" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select6').append('<option id='+ precio3 +' class="select_sizes6" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price6').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0){
                    sizes = document.getElementById("sizes6").classList.add("hide");
                    target = document.getElementById("objetivo6").classList.add("hide");
                    $('#product-price6').html('$ 0');
                }
                var label_precio = document.getElementById("product-price6").classList.remove("hide");
                var label_precio_nuevo = document.getElementById("product-price_second6").classList.add("hide");
                //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
                cambiarPrecioSelectChange6();
                calSubtotal();
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional6").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple6').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
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
                $('.select_sizes6').remove();
                //Oculta el select de tamaño
                var sizes = document.getElementById("sizes6").classList.add("hide");
                //Remueve todas las opciones de el select de objetivos
                $('.select_targets6').remove();
                //Muestra el select de objetivos
                var target = document.getElementById("objetivo6").classList.remove("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional6").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple6').html('');
                //si todos los objetivos son mayores a 0 entonces se acumulan en el select de tamaño
                if(nombre1.price != '' && nombre2.price != '' && nombre3.price != '' && nombre4.price != '' && nombre5.price != '' && nombre6.price != ''){
                    //Numeros sin puntos
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    var precio4 = nombre4.price.replace(/\./g,'');
                    var precio5 = nombre5.price.replace(/\./g,'');
                    var precio6 = nombre6.price.replace(/\./g,'');
                    $('#targets_select6').html('');
                    //Inserto las opciones en el select de objetivos
                    $('#targets_select6').append('<option id='+ precio1 +' class="select_targets6" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#targets_select6').append('<option id='+ precio2 +' class="select_targets6" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#targets_select6').append('<option id='+ precio3 +' class="select_targets6" selected="selected" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#targets_select6').append('<option id='+ precio4 +' class="select_targets6" value='+ nombre4.id + '>' + nombre4.nombre + '</option>');
                    $('#targets_select6').append('<option id='+ precio5 +' class="select_targets6" value='+ nombre5.id + '>' + nombre5.nombre + '</option>');
                    $('#targets_select6').append('<option id='+ precio6 +' class="select_targets6" value='+ nombre6.id + '>' + nombre6.nombre + '</option>');
                    //Coloca el valor de el campo dos ya que esta por defecto
                    $('#product-price6').html('$ ' + nombre3.price);
                }
                // //Si algun tamaño es mayor a 0 pero los demas no entonces se acumula una sola opcion en el select de tamaño
                // if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio1 = nombre1.price.replace(/\./g,'');
                //     $('#targets_select6').html('<option id='+ precio1 +' class="select_targets6" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                //     $('#product-price6').html('$ ' + nombre1.price);
                // }
                // if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio2 = nombre2.price.replace(/\./g,'');
                //     $('#targets_select6').html('<option id='+ precio2 +' class="select_targets6" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                //     $('#product-price6').html('$ ' + nombre2.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price > 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio3 = nombre3.price.replace(/\./g,'');
                //     $('#targets_select6').html('<option id='+ precio3 +' class="select_targets6" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                //     $('#product-price6').html('$ ' + nombre3.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price > 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio4 = nombre4.price.replace(/\./g,'');
                //     $('#targets_select6').html('<option id='+ precio4 +' class="select_targets6" value='+ nombre4.id + '>' + nombre4.nombre + '</option>');
                //     $('#product-price6').html('$ ' + nombre4.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price > 0 && nombre6.price == 0){
                //     var precio5 = nombre5.price.replace(/\./g,'');
                //     $('#targets_select6').html('<option id='+ precio5 +' class="select_targets6" value='+ nombre5.id + '>' + nombre5.nombre + '</option>');
                //     $('#product-price6').html('$ ' + nombre5.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price > 0){
                //     var precio6 = nombre6.price.replace(/\./g,'');
                //     $('#targets_select6').html('<option id='+ precio6 +' class="select_targets6" value='+ nombre6.id + '>' + nombre6.nombre + '</option>');
                //     $('#product-price6').html('$ ' + nombre6.price);
                // }
                var label_precio = document.getElementById("product-price6").classList.remove("hide");
                var label_precio_nuevo = document.getElementById("product-price_second6").classList.add("hide");
                calSubtotal();
                //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
                cambiarPrecioSelectChange6();
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional6").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple6').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
            }else if(dato[0].simple){
                $('.select_sizes6').remove();
                var sizes = document.getElementById("sizes6").classList.add("hide");
                $('.select_targets6').remove();
                var target = document.getElementById("objetivo6").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional6").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple6').html('');
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional6").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple6').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
                $('#product-price6').html('$ ' + dato[0].precio);
                calSubtotal();
            }else{
                //Si no hay tamaño, ni hay objetivo ni hay precio entonces
                //remueve las opciones del select de tamaño y remueve las opciones del select de objetivos
                //tambien deja el precio en 0
                $('.select_sizes6').remove();
                var sizes = document.getElementById("sizes6").classList.add("hide");
                $('.select_targets6').remove();
                var target = document.getElementById("objetivo6").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional6").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple6').html('');
                $('#product-price6').html('$ 0');
                calSubtotal();
                alert(dato[0].datos);
            }
        }
    });
}
function precioSize6(){
    //Obtengo el id del option seleccionado
    var id_select_size = document.getElementById("sizes_select6").selectedOptions[0].id;
    //Formateo el numero con separador de miles
    id_select_size = id_select_size.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    id_select_size = id_select_size.split('').reverse().join('').replace(/^[\.]/,'');
    //Cambio el precio segun el id seleccionado
    $('#product-price6').html('$ ' + id_select_size);

    //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
    var label_precio = document.getElementById("product-price6").classList.remove("hide");
    var label_precio_nuevo = document.getElementById("product-price_second6").classList.add("hide");
    calSubtotal();
    //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
    cambiarPrecioSelectChange6();
    adicional6();
}
function precioObjetivo6(){
    //Obtengo el id del option seleccionado
    var id_select_objetivo = document.getElementById("targets_select6").selectedOptions[0].id;
    //Formateo el numero con separador de miles
    id_select_objetivo = id_select_objetivo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    id_select_objetivo = id_select_objetivo.split('').reverse().join('').replace(/^[\.]/,'');
    //Cambio el precio segun el id seleccionado
    $('#product-price6').html('$ ' + id_select_objetivo);

    //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
    var label_precio = document.getElementById("product-price6").classList.remove("hide");
    var label_precio_nuevo = document.getElementById("product-price_second6").classList.add("hide");
    calSubtotal();
    //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
    cambiarPrecioSelectChange6();
    adicional6();
}
function adicional6(){
    var values = $('#multiple6').val();
    var nValues = values.length;
    var total = 0;
    for(var i = 0; i<nValues; i++){
        total = total + parseInt(values[i]);
    }
    var sizes = $('#sizes6').hasClass('hide');
    var objetivo = $('#objetivo6').hasClass('hide');
    var totalNuevo = 0;
    if(nValues > 0){
        $('#cantidadProducto6').val(1);
        $('#cantidadProducto6').attr('disabled', 'disabled');
    }else{
        $('#cantidadProducto6').removeAttr('disabled');
    }
    var cantidadProducto = document.getElementById("cantidadProducto6").value;
    if(!sizes){
        var id_select_size = document.getElementById("sizes_select6").selectedOptions[0].id;
        id_select_size = parseInt(id_select_size) * cantidadProducto;
        totalNuevo = parseInt(id_select_size) + total;
    }else if(!objetivo){
        var id_select_objetivo = document.getElementById("targets_select6").selectedOptions[0].id;
        id_select_objetivo = parseInt(id_select_objetivo) * cantidadProducto;
        totalNuevo = parseInt(id_select_objetivo) + total;
    }else{
        var productPrice = document.getElementById("product-price6").innerText;
        productPrice = productPrice.replace(/\./g,'');
        productPrice = productPrice.replace(/\ /g,'');
        productPrice = parseInt(productPrice.replace(/\$/g,''));
        var price = document.getElementById("product-price6").classList.add("hide");
        var price_second = document.getElementById("product-price_second6").classList.remove("hide");
        var pSimple = productPrice * cantidadProducto;
        totalNuevo = pSimple + total;
    }
    
    totalNuevo = totalNuevo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    totalNuevo = totalNuevo.split('').reverse().join('').replace(/^[\.]/,'');
    var product_price = $('#product-price6').hasClass('hide');
    if(product_price){
        $('#product-price_second6').html('$ ' + totalNuevo);
    }else{
        $('#product-price6').html('$ ' + totalNuevo);
    }
    calSubtotal();
}
//Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
function cambiarPrecioSelectChange6(){
    var cantidad = document.getElementById("cantidadProducto6").value;

    if(cantidad > 1){
        var precio = document.getElementById("product-price6").textContent;
        precio = precio.replace(/\./g,'');
        precio = precio.replace(/\$/g,'');
        var precionuevo = precio * cantidad;
        //Formateo el numero con separador de miles
        precionuevo = precionuevo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        precionuevo = precionuevo.split('').reverse().join('').replace(/^[\.]/,'');
        //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
        $('#product-price_second6').html('$ ' + precionuevo);
        var label_precio = document.getElementById("product-price6").classList.add("hide");
        var label_precio_nuevo = document.getElementById("product-price_second6").classList.remove("hide");
        calSubtotal();
    }
}




//////////////7





//Para obtener tamaño, objetivo y precio de un producto
function producto7(){
    var id_producto = document.getElementById("product7").value;
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
                $('.select_sizes7').remove();
                //Muestra el select de tamaños
                var sizes = document.getElementById("sizes7").classList.remove("hide");
                //Remueve todas las opciones de el select de objetivos
                $('.select_targets7').remove();
                //Oculta el select de objetivos
                var target = document.getElementById("objetivo7").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional7").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple7').html('');
                //si todos los tamaños son mayores a 0 entonces se acumulan en el select de tamaño
                if(nombre1.price != 0 && nombre2.price != 0 && nombre3.price != 0){
                    //Numeros sin puntos
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    //Inserto las opciones en el select de objetivos
                    $('#sizes_select7').append('<option id='+ precio1 +' class="select_sizes7" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select7').append('<option id='+ precio2 +' class="select_sizes7" selected="selected" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#sizes_select7').append('<option id='+ precio3 +' class="select_sizes7" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    //Coloca el valor de el campo dos ya que esta por defecto
                    $('#product-price7').html('$ ' + nombre2.price);
                }
                //Si algun tamaño es mayor a 0 pero los demas no entonces se acumula una sola opcion en el select de tamaño
                if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price == 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    $('#sizes_select7').html('<option id='+ precio1 +' class="select_sizes7" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#product-price7').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price == 0){
                    var precio2 = nombre2.price.replace(/\./g,'');
                    $('#sizes_select7').html('<option id='+ precio2 +' class="select_sizes7" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#product-price7').html('$ ' + nombre2.price);
                }
                if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price > 0){
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select7').html('<option id='+ precio3 +' class="select_sizes7" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price7').html('$ ' + nombre3.price);
                }
                if(nombre1.price > 0 && nombre2.price > 0 && nombre3.price == 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    $('#sizes_select7').html('<option id='+ precio1 +' class="select_sizes7" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select7').append('<option id='+ precio2 +' class="select_sizes7" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#product-price7').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price > 0){
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select7').html('<option id='+ precio2 +' class="select_sizes7" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#sizes_select7').append('<option id='+ precio3 +' class="select_sizes7" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price7').html('$ ' + nombre2.price);
                }
                if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price > 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select7').html('<option id='+ precio1 +' class="select_sizes7" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select7').append('<option id='+ precio3 +' class="select_sizes7" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price7').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0){
                    sizes = document.getElementById("sizes7").classList.add("hide");
                    target = document.getElementById("objetivo7").classList.add("hide");
                    $('#product-price7').html('$ 0');
                }
                var label_precio = document.getElementById("product-price7").classList.remove("hide");
                var label_precio_nuevo = document.getElementById("product-price_second7").classList.add("hide");
                //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
                cambiarPrecioSelectChange7();
                calSubtotal();
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional7").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple7').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
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
                $('.select_sizes7').remove();
                //Oculta el select de tamaño
                var sizes = document.getElementById("sizes7").classList.add("hide");
                //Remueve todas las opciones de el select de objetivos
                $('.select_targets7').remove();
                //Muestra el select de objetivos
                var target = document.getElementById("objetivo7").classList.remove("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional7").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple7').html('');
                //si todos los objetivos son mayores a 0 entonces se acumulan en el select de tamaño
                if(nombre1.price != '' && nombre2.price != '' && nombre3.price != '' && nombre4.price != '' && nombre5.price != '' && nombre6.price != ''){
                    //Numeros sin puntos
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    var precio4 = nombre4.price.replace(/\./g,'');
                    var precio5 = nombre5.price.replace(/\./g,'');
                    var precio6 = nombre6.price.replace(/\./g,'');
                    $('#targets_select7').html('');
                    //Inserto las opciones en el select de objetivos
                    $('#targets_select7').append('<option id='+ precio1 +' class="select_targets7" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#targets_select7').append('<option id='+ precio2 +' class="select_targets7" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#targets_select7').append('<option id='+ precio3 +' class="select_targets7" selected="selected" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#targets_select7').append('<option id='+ precio4 +' class="select_targets7" value='+ nombre4.id + '>' + nombre4.nombre + '</option>');
                    $('#targets_select7').append('<option id='+ precio5 +' class="select_targets7" value='+ nombre5.id + '>' + nombre5.nombre + '</option>');
                    $('#targets_select7').append('<option id='+ precio6 +' class="select_targets7" value='+ nombre6.id + '>' + nombre6.nombre + '</option>');
                    //Coloca el valor de el campo dos ya que esta por defecto
                    $('#product-price7').html('$ ' + nombre3.price);
                }
                // //Si algun tamaño es mayor a 0 pero los demas no entonces se acumula una sola opcion en el select de tamaño
                // if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio1 = nombre1.price.replace(/\./g,'');
                //     $('#targets_select7').html('<option id='+ precio1 +' class="select_targets7" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                //     $('#product-price7').html('$ ' + nombre1.price);
                // }
                // if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio2 = nombre2.price.replace(/\./g,'');
                //     $('#targets_select7').html('<option id='+ precio2 +' class="select_targets7" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                //     $('#product-price7').html('$ ' + nombre2.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price > 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio3 = nombre3.price.replace(/\./g,'');
                //     $('#targets_select7').html('<option id='+ precio3 +' class="select_targets7" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                //     $('#product-price7').html('$ ' + nombre3.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price > 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio4 = nombre4.price.replace(/\./g,'');
                //     $('#targets_select7').html('<option id='+ precio4 +' class="select_targets7" value='+ nombre4.id + '>' + nombre4.nombre + '</option>');
                //     $('#product-price7').html('$ ' + nombre4.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price > 0 && nombre6.price == 0){
                //     var precio5 = nombre5.price.replace(/\./g,'');
                //     $('#targets_select7').html('<option id='+ precio5 +' class="select_targets7" value='+ nombre5.id + '>' + nombre5.nombre + '</option>');
                //     $('#product-price7').html('$ ' + nombre5.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price > 0){
                //     var precio6 = nombre6.price.replace(/\./g,'');
                //     $('#targets_select7').html('<option id='+ precio6 +' class="select_targets7" value='+ nombre6.id + '>' + nombre6.nombre + '</option>');
                //     $('#product-price7').html('$ ' + nombre6.price);
                // }
                var label_precio = document.getElementById("product-price7").classList.remove("hide");
                var label_precio_nuevo = document.getElementById("product-price_second7").classList.add("hide");
                calSubtotal();
                //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
                cambiarPrecioSelectChange7();
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional7").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple7').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
            }else if(dato[0].simple){
                $('.select_sizes7').remove();
                var sizes = document.getElementById("sizes7").classList.add("hide");
                $('.select_targets7').remove();
                var target = document.getElementById("objetivo7").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional7").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple7').html('');
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional7").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple7').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
                $('#product-price7').html('$ ' + dato[0].precio);
                calSubtotal();
            }else{
                //Si no hay tamaño, ni hay objetivo ni hay precio entonces
                //remueve las opciones del select de tamaño y remueve las opciones del select de objetivos
                //tambien deja el precio en 0
                $('.select_sizes7').remove();
                var sizes = document.getElementById("sizes7").classList.add("hide");
                $('.select_targets7').remove();
                var target = document.getElementById("objetivo7").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional7").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple7').html('');
                $('#product-price7').html('$ 0');
                calSubtotal();
                alert(dato[0].datos);
            }
        }
    });
}
function precioSize7(){
    //Obtengo el id del option seleccionado
    var id_select_size = document.getElementById("sizes_select7").selectedOptions[0].id;
    //Formateo el numero con separador de miles
    id_select_size = id_select_size.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    id_select_size = id_select_size.split('').reverse().join('').replace(/^[\.]/,'');
    //Cambio el precio segun el id seleccionado
    $('#product-price7').html('$ ' + id_select_size);

    //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
    var label_precio = document.getElementById("product-price7").classList.remove("hide");
    var label_precio_nuevo = document.getElementById("product-price_second7").classList.add("hide");
    calSubtotal();
    //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
    cambiarPrecioSelectChange7();
    adicional7();
}
function precioObjetivo7(){
    //Obtengo el id del option seleccionado
    var id_select_objetivo = document.getElementById("targets_select7").selectedOptions[0].id;
    //Formateo el numero con separador de miles
    id_select_objetivo = id_select_objetivo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    id_select_objetivo = id_select_objetivo.split('').reverse().join('').replace(/^[\.]/,'');
    //Cambio el precio segun el id seleccionado
    $('#product-price7').html('$ ' + id_select_objetivo);

    //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
    var label_precio = document.getElementById("product-price7").classList.remove("hide");
    var label_precio_nuevo = document.getElementById("product-price_second7").classList.add("hide");
    calSubtotal();
    //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
    cambiarPrecioSelectChange7();
    adicional7();
}
function adicional7(){
    var values = $('#multiple7').val();
    var nValues = values.length;
    var total = 0;
    for(var i = 0; i<nValues; i++){
        total = total + parseInt(values[i]);
    }
    var sizes = $('#sizes7').hasClass('hide');
    var objetivo = $('#objetivo7').hasClass('hide');
    var totalNuevo = 0;
    if(nValues > 0){
        $('#cantidadProducto7').val(1);
        $('#cantidadProducto7').attr('disabled', 'disabled');
    }else{
        $('#cantidadProducto7').removeAttr('disabled');
    }
    var cantidadProducto = document.getElementById("cantidadProducto7").value;
    if(!sizes){
        var id_select_size = document.getElementById("sizes_select7").selectedOptions[0].id;
        id_select_size = parseInt(id_select_size) * cantidadProducto;
        totalNuevo = parseInt(id_select_size) + total;
    }else if(!objetivo){
        var id_select_objetivo = document.getElementById("targets_select7").selectedOptions[0].id;
        id_select_objetivo = parseInt(id_select_objetivo) * cantidadProducto;
        totalNuevo = parseInt(id_select_objetivo) + total;
    }else{
        var productPrice = document.getElementById("product-price7").innerText;
        productPrice = productPrice.replace(/\./g,'');
        productPrice = productPrice.replace(/\ /g,'');
        productPrice = parseInt(productPrice.replace(/\$/g,''));
        var price = document.getElementById("product-price7").classList.add("hide");
        var price_second = document.getElementById("product-price_second7").classList.remove("hide");
        var pSimple = productPrice * cantidadProducto;
        totalNuevo = pSimple + total;
    }
    
    totalNuevo = totalNuevo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    totalNuevo = totalNuevo.split('').reverse().join('').replace(/^[\.]/,'');
    var product_price = $('#product-price7').hasClass('hide');
    if(product_price){
        $('#product-price_second7').html('$ ' + totalNuevo);
    }else{
        $('#product-price7').html('$ ' + totalNuevo);
    }
    calSubtotal();
}
//Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
function cambiarPrecioSelectChange7(){
    var cantidad = document.getElementById("cantidadProducto7").value;

    if(cantidad > 1){
        var precio = document.getElementById("product-price7").textContent;
        precio = precio.replace(/\./g,'');
        precio = precio.replace(/\$/g,'');
        var precionuevo = precio * cantidad;
        //Formateo el numero con separador de miles
        precionuevo = precionuevo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        precionuevo = precionuevo.split('').reverse().join('').replace(/^[\.]/,'');
        //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
        $('#product-price_second7').html('$ ' + precionuevo);
        var label_precio = document.getElementById("product-price7").classList.add("hide");
        var label_precio_nuevo = document.getElementById("product-price_second7").classList.remove("hide");
        calSubtotal();
    }
}

//////////////8





//Para obtener tamaño, objetivo y precio de un producto
function producto8(){
    var id_producto = document.getElementById("product8").value;
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
                $('.select_sizes8').remove();
                //Muestra el select de tamaños
                var sizes = document.getElementById("sizes8").classList.remove("hide");
                //Remueve todas las opciones de el select de objetivos
                $('.select_targets8').remove();
                //Oculta el select de objetivos
                var target = document.getElementById("objetivo8").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional8").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple8').html('');
                //si todos los tamaños son mayores a 0 entonces se acumulan en el select de tamaño
                if(nombre1.price != 0 && nombre2.price != 0 && nombre3.price != 0){
                    //Numeros sin puntos
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    //Inserto las opciones en el select de objetivos
                    $('#sizes_select8').append('<option id='+ precio1 +' class="select_sizes8" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select8').append('<option id='+ precio2 +' class="select_sizes8" selected="selected" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#sizes_select8').append('<option id='+ precio3 +' class="select_sizes8" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    //Coloca el valor de el campo dos ya que esta por defecto
                    $('#product-price8').html('$ ' + nombre2.price);
                }
                //Si algun tamaño es mayor a 0 pero los demas no entonces se acumula una sola opcion en el select de tamaño
                if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price == 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    $('#sizes_select8').html('<option id='+ precio1 +' class="select_sizes8" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#product-price8').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price == 0){
                    var precio2 = nombre2.price.replace(/\./g,'');
                    $('#sizes_select8').html('<option id='+ precio2 +' class="select_sizes8" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#product-price8').html('$ ' + nombre2.price);
                }
                if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price > 0){
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select8').html('<option id='+ precio3 +' class="select_sizes8" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price8').html('$ ' + nombre3.price);
                }
                if(nombre1.price > 0 && nombre2.price > 0 && nombre3.price == 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    $('#sizes_select8').html('<option id='+ precio1 +' class="select_sizes8" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select8').append('<option id='+ precio2 +' class="select_sizes8" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#product-price8').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price > 0){
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select8').html('<option id='+ precio2 +' class="select_sizes8" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#sizes_select8').append('<option id='+ precio3 +' class="select_sizes8" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price8').html('$ ' + nombre2.price);
                }
                if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price > 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select8').html('<option id='+ precio1 +' class="select_sizes8" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select8').append('<option id='+ precio3 +' class="select_sizes8" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price8').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0){
                    sizes = document.getElementById("sizes8").classList.add("hide");
                    target = document.getElementById("objetivo8").classList.add("hide");
                    $('#product-price8').html('$ 0');
                }
                var label_precio = document.getElementById("product-price8").classList.remove("hide");
                var label_precio_nuevo = document.getElementById("product-price_second8").classList.add("hide");
                //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
                cambiarPrecioSelectChange8();
                calSubtotal();
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional8").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple8').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
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
                $('.select_sizes8').remove();
                //Oculta el select de tamaño
                var sizes = document.getElementById("sizes8").classList.add("hide");
                //Remueve todas las opciones de el select de objetivos
                $('.select_targets8').remove();
                //Muestra el select de objetivos
                var target = document.getElementById("objetivo8").classList.remove("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional8").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple8').html('');
                //si todos los objetivos son mayores a 0 entonces se acumulan en el select de tamaño
                if(nombre1.price != '' && nombre2.price != '' && nombre3.price != '' && nombre4.price != '' && nombre5.price != '' && nombre6.price != ''){
                    //Numeros sin puntos
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    var precio4 = nombre4.price.replace(/\./g,'');
                    var precio5 = nombre5.price.replace(/\./g,'');
                    var precio6 = nombre6.price.replace(/\./g,'');
                    $('#targets_select8').html('');
                    //Inserto las opciones en el select de objetivos
                    $('#targets_select8').append('<option id='+ precio1 +' class="select_targets8" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#targets_select8').append('<option id='+ precio2 +' class="select_targets8" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#targets_select8').append('<option id='+ precio3 +' class="select_targets8" selected="selected" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#targets_select8').append('<option id='+ precio4 +' class="select_targets8" value='+ nombre4.id + '>' + nombre4.nombre + '</option>');
                    $('#targets_select8').append('<option id='+ precio5 +' class="select_targets8" value='+ nombre5.id + '>' + nombre5.nombre + '</option>');
                    $('#targets_select8').append('<option id='+ precio6 +' class="select_targets8" value='+ nombre6.id + '>' + nombre6.nombre + '</option>');
                    //Coloca el valor de el campo dos ya que esta por defecto
                    $('#product-price8').html('$ ' + nombre3.price);
                }
                // //Si algun tamaño es mayor a 0 pero los demas no entonces se acumula una sola opcion en el select de tamaño
                // if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio1 = nombre1.price.replace(/\./g,'');
                //     $('#targets_select8').html('<option id='+ precio1 +' class="select_targets8" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                //     $('#product-price8').html('$ ' + nombre1.price);
                // }
                // if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio2 = nombre2.price.replace(/\./g,'');
                //     $('#targets_select8').html('<option id='+ precio2 +' class="select_targets8" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                //     $('#product-price8').html('$ ' + nombre2.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price > 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio3 = nombre3.price.replace(/\./g,'');
                //     $('#targets_select8').html('<option id='+ precio3 +' class="select_targets8" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                //     $('#product-price8').html('$ ' + nombre3.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price > 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio4 = nombre4.price.replace(/\./g,'');
                //     $('#targets_select8').html('<option id='+ precio4 +' class="select_targets8" value='+ nombre4.id + '>' + nombre4.nombre + '</option>');
                //     $('#product-price8').html('$ ' + nombre4.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price > 0 && nombre6.price == 0){
                //     var precio5 = nombre5.price.replace(/\./g,'');
                //     $('#targets_select8').html('<option id='+ precio5 +' class="select_targets8" value='+ nombre5.id + '>' + nombre5.nombre + '</option>');
                //     $('#product-price8').html('$ ' + nombre5.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price > 0){
                //     var precio6 = nombre6.price.replace(/\./g,'');
                //     $('#targets_select8').html('<option id='+ precio6 +' class="select_targets8" value='+ nombre6.id + '>' + nombre6.nombre + '</option>');
                //     $('#product-price8').html('$ ' + nombre6.price);
                // }
                var label_precio = document.getElementById("product-price8").classList.remove("hide");
                var label_precio_nuevo = document.getElementById("product-price_second8").classList.add("hide");
                calSubtotal();
                //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
                cambiarPrecioSelectChange8();
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional8").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple8').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
            }else if(dato[0].simple){
                $('.select_sizes8').remove();
                var sizes = document.getElementById("sizes8").classList.add("hide");
                $('.select_targets8').remove();
                var target = document.getElementById("objetivo8").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional8").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple8').html('');
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional8").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple8').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
                $('#product-price8').html('$ ' + dato[0].precio);
                calSubtotal();
            }else{
                //Si no hay tamaño, ni hay objetivo ni hay precio entonces
                //remueve las opciones del select de tamaño y remueve las opciones del select de objetivos
                //tambien deja el precio en 0
                $('.select_sizes8').remove();
                var sizes = document.getElementById("sizes8").classList.add("hide");
                $('.select_targets8').remove();
                var target = document.getElementById("objetivo8").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional8").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple8').html('');
                $('#product-price8').html('$ 0');
                calSubtotal();
                alert(dato[0].datos);
            }
        }
    });
}
function precioSize8(){
    //Obtengo el id del option seleccionado
    var id_select_size = document.getElementById("sizes_select8").selectedOptions[0].id;
    //Formateo el numero con separador de miles
    id_select_size = id_select_size.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    id_select_size = id_select_size.split('').reverse().join('').replace(/^[\.]/,'');
    //Cambio el precio segun el id seleccionado
    $('#product-price8').html('$ ' + id_select_size);

    //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
    var label_precio = document.getElementById("product-price8").classList.remove("hide");
    var label_precio_nuevo = document.getElementById("product-price_second8").classList.add("hide");
    calSubtotal();
    //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
    cambiarPrecioSelectChange8();
    adicional8();
}
function precioObjetivo8(){
    //Obtengo el id del option seleccionado
    var id_select_objetivo = document.getElementById("targets_select8").selectedOptions[0].id;
    //Formateo el numero con separador de miles
    id_select_objetivo = id_select_objetivo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    id_select_objetivo = id_select_objetivo.split('').reverse().join('').replace(/^[\.]/,'');
    //Cambio el precio segun el id seleccionado
    $('#product-price8').html('$ ' + id_select_objetivo);

    //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
    var label_precio = document.getElementById("product-price8").classList.remove("hide");
    var label_precio_nuevo = document.getElementById("product-price_second8").classList.add("hide");
    calSubtotal();
    //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
    cambiarPrecioSelectChange8();
    adicional8();
}
function adicional8(){
    var values = $('#multiple8').val();
    var nValues = values.length;
    var total = 0;
    for(var i = 0; i<nValues; i++){
        total = total + parseInt(values[i]);
    }
    var sizes = $('#sizes8').hasClass('hide');
    var objetivo = $('#objetivo8').hasClass('hide');
    var totalNuevo = 0;
    if(nValues > 0){
        $('#cantidadProducto8').val(1);
        $('#cantidadProducto8').attr('disabled', 'disabled');
    }else{
        $('#cantidadProducto8').removeAttr('disabled');
    }
    var cantidadProducto = document.getElementById("cantidadProducto8").value;
    if(!sizes){
        var id_select_size = document.getElementById("sizes_select8").selectedOptions[0].id;
        id_select_size = parseInt(id_select_size) * cantidadProducto;
        totalNuevo = parseInt(id_select_size) + total;
    }else if(!objetivo){
        var id_select_objetivo = document.getElementById("targets_select8").selectedOptions[0].id;
        id_select_objetivo = parseInt(id_select_objetivo) * cantidadProducto;
        totalNuevo = parseInt(id_select_objetivo) + total;
    }else{
        var productPrice = document.getElementById("product-price8").innerText;
        productPrice = productPrice.replace(/\./g,'');
        productPrice = productPrice.replace(/\ /g,'');
        productPrice = parseInt(productPrice.replace(/\$/g,''));
        var price = document.getElementById("product-price8").classList.add("hide");
        var price_second = document.getElementById("product-price_second8").classList.remove("hide");
        var pSimple = productPrice * cantidadProducto;
        totalNuevo = pSimple + total;
    }
    
    totalNuevo = totalNuevo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    totalNuevo = totalNuevo.split('').reverse().join('').replace(/^[\.]/,'');
    var product_price = $('#product-price8').hasClass('hide');
    if(product_price){
        $('#product-price_second8').html('$ ' + totalNuevo);
    }else{
        $('#product-price8').html('$ ' + totalNuevo);
    }
    calSubtotal();
}
//Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
function cambiarPrecioSelectChange8(){
    var cantidad = document.getElementById("cantidadProducto8").value;

    if(cantidad > 1){
        var precio = document.getElementById("product-price8").textContent;
        precio = precio.replace(/\./g,'');
        precio = precio.replace(/\$/g,'');
        var precionuevo = precio * cantidad;
        //Formateo el numero con separador de miles
        precionuevo = precionuevo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        precionuevo = precionuevo.split('').reverse().join('').replace(/^[\.]/,'');
        //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
        $('#product-price_second8').html('$ ' + precionuevo);
        var label_precio = document.getElementById("product-price8").classList.add("hide");
        var label_precio_nuevo = document.getElementById("product-price_second8").classList.remove("hide");
        calSubtotal();
    }
}






//////////////9





//Para obtener tamaño, objetivo y precio de un producto
function producto9(){
    var id_producto = document.getElementById("product9").value;
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
                $('.select_sizes9').remove();
                //Muestra el select de tamaños
                var sizes = document.getElementById("sizes9").classList.remove("hide");
                //Remueve todas las opciones de el select de objetivos
                $('.select_targets9').remove();
                //Oculta el select de objetivos
                var target = document.getElementById("objetivo9").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional9").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple9').html('');
                //si todos los tamaños son mayores a 0 entonces se acumulan en el select de tamaño
                if(nombre1.price != 0 && nombre2.price != 0 && nombre3.price != 0){
                    //Numeros sin puntos
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    //Inserto las opciones en el select de objetivos
                    $('#sizes_select9').append('<option id='+ precio1 +' class="select_sizes9" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select9').append('<option id='+ precio2 +' class="select_sizes9" selected="selected" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#sizes_select9').append('<option id='+ precio3 +' class="select_sizes9" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    //Coloca el valor de el campo dos ya que esta por defecto
                    $('#product-price9').html('$ ' + nombre2.price);
                }
                //Si algun tamaño es mayor a 0 pero los demas no entonces se acumula una sola opcion en el select de tamaño
                if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price == 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    $('#sizes_select9').html('<option id='+ precio1 +' class="select_sizes9" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#product-price9').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price == 0){
                    var precio2 = nombre2.price.replace(/\./g,'');
                    $('#sizes_select9').html('<option id='+ precio2 +' class="select_sizes9" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#product-price9').html('$ ' + nombre2.price);
                }
                if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price > 0){
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select9').html('<option id='+ precio3 +' class="select_sizes9" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price9').html('$ ' + nombre3.price);
                }
                if(nombre1.price > 0 && nombre2.price > 0 && nombre3.price == 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    $('#sizes_select9').html('<option id='+ precio1 +' class="select_sizes9" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select9').append('<option id='+ precio2 +' class="select_sizes9" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#product-price9').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price > 0){
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select9').html('<option id='+ precio2 +' class="select_sizes9" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#sizes_select9').append('<option id='+ precio3 +' class="select_sizes9" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price9').html('$ ' + nombre2.price);
                }
                if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price > 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select9').html('<option id='+ precio1 +' class="select_sizes9" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select9').append('<option id='+ precio3 +' class="select_sizes9" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price9').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0){
                    sizes = document.getElementById("sizes9").classList.add("hide");
                    target = document.getElementById("objetivo9").classList.add("hide");
                    $('#product-price9').html('$ 0');
                }
                var label_precio = document.getElementById("product-price9").classList.remove("hide");
                var label_precio_nuevo = document.getElementById("product-price_second9").classList.add("hide");
                //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
                cambiarPrecioSelectChange9();
                calSubtotal();
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional9").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple9').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
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
                $('.select_sizes9').remove();
                //Oculta el select de tamaño
                var sizes = document.getElementById("sizes9").classList.add("hide");
                //Remueve todas las opciones de el select de objetivos
                $('.select_targets9').remove();
                //Muestra el select de objetivos
                var target = document.getElementById("objetivo9").classList.remove("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional9").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple9').html('');
                //si todos los objetivos son mayores a 0 entonces se acumulan en el select de tamaño
                if(nombre1.price != '' && nombre2.price != '' && nombre3.price != '' && nombre4.price != '' && nombre5.price != '' && nombre6.price != ''){
                    //Numeros sin puntos
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    var precio4 = nombre4.price.replace(/\./g,'');
                    var precio5 = nombre5.price.replace(/\./g,'');
                    var precio6 = nombre6.price.replace(/\./g,'');
                    $('#targets_select9').html('');
                    //Inserto las opciones en el select de objetivos
                    $('#targets_select9').append('<option id='+ precio1 +' class="select_targets9" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#targets_select9').append('<option id='+ precio2 +' class="select_targets9" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#targets_select9').append('<option id='+ precio3 +' class="select_targets9" selected="selected" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#targets_select9').append('<option id='+ precio4 +' class="select_targets9" value='+ nombre4.id + '>' + nombre4.nombre + '</option>');
                    $('#targets_select9').append('<option id='+ precio5 +' class="select_targets9" value='+ nombre5.id + '>' + nombre5.nombre + '</option>');
                    $('#targets_select9').append('<option id='+ precio6 +' class="select_targets9" value='+ nombre6.id + '>' + nombre6.nombre + '</option>');
                    //Coloca el valor de el campo dos ya que esta por defecto
                    $('#product-price9').html('$ ' + nombre3.price);
                }
                // //Si algun tamaño es mayor a 0 pero los demas no entonces se acumula una sola opcion en el select de tamaño
                // if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio1 = nombre1.price.replace(/\./g,'');
                //     $('#targets_select9').html('<option id='+ precio1 +' class="select_targets9" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                //     $('#product-price9').html('$ ' + nombre1.price);
                // }
                // if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio2 = nombre2.price.replace(/\./g,'');
                //     $('#targets_select9').html('<option id='+ precio2 +' class="select_targets9" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                //     $('#product-price9').html('$ ' + nombre2.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price > 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio3 = nombre3.price.replace(/\./g,'');
                //     $('#targets_select9').html('<option id='+ precio3 +' class="select_targets9" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                //     $('#product-price9').html('$ ' + nombre3.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price > 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio4 = nombre4.price.replace(/\./g,'');
                //     $('#targets_select9').html('<option id='+ precio4 +' class="select_targets9" value='+ nombre4.id + '>' + nombre4.nombre + '</option>');
                //     $('#product-price9').html('$ ' + nombre4.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price > 0 && nombre6.price == 0){
                //     var precio5 = nombre5.price.replace(/\./g,'');
                //     $('#targets_select9').html('<option id='+ precio5 +' class="select_targets9" value='+ nombre5.id + '>' + nombre5.nombre + '</option>');
                //     $('#product-price9').html('$ ' + nombre5.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price > 0){
                //     var precio6 = nombre6.price.replace(/\./g,'');
                //     $('#targets_select9').html('<option id='+ precio6 +' class="select_targets9" value='+ nombre6.id + '>' + nombre6.nombre + '</option>');
                //     $('#product-price9').html('$ ' + nombre6.price);
                // }
                var label_precio = document.getElementById("product-price9").classList.remove("hide");
                var label_precio_nuevo = document.getElementById("product-price_second9").classList.add("hide");
                calSubtotal();
                //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
                cambiarPrecioSelectChange9();
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional9").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple9').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
            }else if(dato[0].simple){
                $('.select_sizes9').remove();
                var sizes = document.getElementById("sizes9").classList.add("hide");
                $('.select_targets9').remove();
                var target = document.getElementById("objetivo9").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional9").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple9').html('');
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional9").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple9').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
                $('#product-price9').html('$ ' + dato[0].precio);
                calSubtotal();
            }else{
                //Si no hay tamaño, ni hay objetivo ni hay precio entonces
                //remueve las opciones del select de tamaño y remueve las opciones del select de objetivos
                //tambien deja el precio en 0
                $('.select_sizes9').remove();
                var sizes = document.getElementById("sizes9").classList.add("hide");
                $('.select_targets9').remove();
                var target = document.getElementById("objetivo9").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional9").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple9').html('');
                $('#product-price9').html('$ 0');
                calSubtotal();
                alert(dato[0].datos);
            }
        }
    });
}
function precioSize9(){
    //Obtengo el id del option seleccionado
    var id_select_size = document.getElementById("sizes_select9").selectedOptions[0].id;
    //Formateo el numero con separador de miles
    id_select_size = id_select_size.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    id_select_size = id_select_size.split('').reverse().join('').replace(/^[\.]/,'');
    //Cambio el precio segun el id seleccionado
    $('#product-price9').html('$ ' + id_select_size);

    //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
    var label_precio = document.getElementById("product-price9").classList.remove("hide");
    var label_precio_nuevo = document.getElementById("product-price_second9").classList.add("hide");
    calSubtotal();
    //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
    cambiarPrecioSelectChange9();
    adicional9();
}
function precioObjetivo9(){
    //Obtengo el id del option seleccionado
    var id_select_objetivo = document.getElementById("targets_select9").selectedOptions[0].id;
    //Formateo el numero con separador de miles
    id_select_objetivo = id_select_objetivo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    id_select_objetivo = id_select_objetivo.split('').reverse().join('').replace(/^[\.]/,'');
    //Cambio el precio segun el id seleccionado
    $('#product-price9').html('$ ' + id_select_objetivo);

    //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
    var label_precio = document.getElementById("product-price9").classList.remove("hide");
    var label_precio_nuevo = document.getElementById("product-price_second9").classList.add("hide");
    calSubtotal();
    //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
    cambiarPrecioSelectChange9();
    adicional9();
}
function adicional9(){
    var values = $('#multiple9').val();
    var nValues = values.length;
    var total = 0;
    for(var i = 0; i<nValues; i++){
        total = total + parseInt(values[i]);
    }
    var sizes = $('#sizes9').hasClass('hide');
    var objetivo = $('#objetivo9').hasClass('hide');
    var totalNuevo = 0;
    if(nValues > 0){
        $('#cantidadProducto9').val(1);
        $('#cantidadProducto9').attr('disabled', 'disabled');
    }else{
        $('#cantidadProducto9').removeAttr('disabled');
    }
    var cantidadProducto = document.getElementById("cantidadProducto9").value;
    if(!sizes){
        var id_select_size = document.getElementById("sizes_select9").selectedOptions[0].id;
        id_select_size = parseInt(id_select_size) * cantidadProducto;
        totalNuevo = parseInt(id_select_size) + total;
    }else if(!objetivo){
        var id_select_objetivo = document.getElementById("targets_select9").selectedOptions[0].id;
        id_select_objetivo = parseInt(id_select_objetivo) * cantidadProducto;
        totalNuevo = parseInt(id_select_objetivo) + total;
    }else{
        var productPrice = document.getElementById("product-price9").innerText;
        productPrice = productPrice.replace(/\./g,'');
        productPrice = productPrice.replace(/\ /g,'');
        productPrice = parseInt(productPrice.replace(/\$/g,''));
        var price = document.getElementById("product-price9").classList.add("hide");
        var price_second = document.getElementById("product-price_second9").classList.remove("hide");
        var pSimple = productPrice * cantidadProducto;
        totalNuevo = pSimple + total;
    }
    
    totalNuevo = totalNuevo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    totalNuevo = totalNuevo.split('').reverse().join('').replace(/^[\.]/,'');
    var product_price = $('#product-price9').hasClass('hide');
    if(product_price){
        $('#product-price_second9').html('$ ' + totalNuevo);
    }else{
        $('#product-price9').html('$ ' + totalNuevo);
    }
    calSubtotal();
}
//Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
function cambiarPrecioSelectChange9(){
    var cantidad = document.getElementById("cantidadProducto9").value;

    if(cantidad > 1){
        var precio = document.getElementById("product-price9").textContent;
        precio = precio.replace(/\./g,'');
        precio = precio.replace(/\$/g,'');
        var precionuevo = precio * cantidad;
        //Formateo el numero con separador de miles
        precionuevo = precionuevo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        precionuevo = precionuevo.split('').reverse().join('').replace(/^[\.]/,'');
        //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
        $('#product-price_second9').html('$ ' + precionuevo);
        var label_precio = document.getElementById("product-price9").classList.add("hide");
        var label_precio_nuevo = document.getElementById("product-price_second9").classList.remove("hide");
        calSubtotal();
    }
}





//////////////10





//Para obtener tamaño, objetivo y precio de un producto
function producto10(){
    var id_producto = document.getElementById("product10").value;
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
                $('.select_sizes10').remove();
                //Muestra el select de tamaños
                var sizes = document.getElementById("sizes10").classList.remove("hide");
                //Remueve todas las opciones de el select de objetivos
                $('.select_targets10').remove();
                //Oculta el select de objetivos
                var target = document.getElementById("objetivo10").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional10").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple10').html('');
                //si todos los tamaños son mayores a 0 entonces se acumulan en el select de tamaño
                if(nombre1.price != 0 && nombre2.price != 0 && nombre3.price != 0){
                    //Numeros sin puntos
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    //Inserto las opciones en el select de objetivos
                    $('#sizes_select10').append('<option id='+ precio1 +' class="select_sizes10" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select10').append('<option id='+ precio2 +' class="select_sizes10" selected="selected" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#sizes_select10').append('<option id='+ precio3 +' class="select_sizes10" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    //Coloca el valor de el campo dos ya que esta por defecto
                    $('#product-price10').html('$ ' + nombre2.price);
                }
                //Si algun tamaño es mayor a 0 pero los demas no entonces se acumula una sola opcion en el select de tamaño
                if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price == 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    $('#sizes_select10').html('<option id='+ precio1 +' class="select_sizes10" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#product-price10').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price == 0){
                    var precio2 = nombre2.price.replace(/\./g,'');
                    $('#sizes_select10').html('<option id='+ precio1 +' class="select_sizes10" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#product-price10').html('$ ' + nombre2.price);
                }
                if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price > 0){
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select10').html('<option id='+ precio1 +' class="select_sizes10" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price10').html('$ ' + nombre3.price);
                }
                if(nombre1.price > 0 && nombre2.price > 0 && nombre3.price == 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    $('#sizes_select10').html('<option id='+ precio1 +' class="select_sizes10" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select10').append('<option id='+ precio2 +' class="select_sizes10" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#product-price10').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price > 0){
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select10').html('<option id='+ precio2 +' class="select_sizes10" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#sizes_select10').append('<option id='+ precio3 +' class="select_sizes10" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price10').html('$ ' + nombre2.price);
                }
                if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price > 0){
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    $('#sizes_select10').html('<option id='+ precio1 +' class="select_sizes10" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#sizes_select10').append('<option id='+ precio3 +' class="select_sizes10" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#product-price10').html('$ ' + nombre1.price);
                }
                if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0){
                    sizes = document.getElementById("sizes10").classList.add("hide");
                    target = document.getElementById("objetivo10").classList.add("hide");
                    $('#product-price10').html('$ 0');
                }
                var label_precio = document.getElementById("product-price10").classList.remove("hide");
                var label_precio_nuevo = document.getElementById("product-price_second10").classList.add("hide");
                //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
                cambiarPrecioSelectChange10();
                calSubtotal();
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional10").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple10').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
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
                $('.select_sizes10').remove();
                //Oculta el select de tamaño
                var sizes = document.getElementById("sizes10").classList.add("hide");
                //Remueve todas las opciones de el select de objetivos
                $('.select_targets10').remove();
                //Muestra el select de objetivos
                var target = document.getElementById("objetivo10").classList.remove("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional10").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple10').html('');
                //si todos los objetivos son mayores a 0 entonces se acumulan en el select de tamaño
                if(nombre1.price != '' && nombre2.price != '' && nombre3.price != '' && nombre4.price != '' && nombre5.price != '' && nombre6.price != ''){
                    //Numeros sin puntos
                    var precio1 = nombre1.price.replace(/\./g,'');
                    var precio2 = nombre2.price.replace(/\./g,'');
                    var precio3 = nombre3.price.replace(/\./g,'');
                    var precio4 = nombre4.price.replace(/\./g,'');
                    var precio5 = nombre5.price.replace(/\./g,'');
                    var precio6 = nombre6.price.replace(/\./g,'');
                    $('#targets_select10').html('');
                    //Inserto las opciones en el select de objetivos
                    $('#targets_select10').append('<option id='+ precio1 +' class="select_targets10" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                    $('#targets_select10').append('<option id='+ precio2 +' class="select_targets10" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                    $('#targets_select10').append('<option id='+ precio3 +' class="select_targets10" selected="selected" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                    $('#targets_select10').append('<option id='+ precio4 +' class="select_targets10" value='+ nombre4.id + '>' + nombre4.nombre + '</option>');
                    $('#targets_select10').append('<option id='+ precio5 +' class="select_targets10" value='+ nombre5.id + '>' + nombre5.nombre + '</option>');
                    $('#targets_select10').append('<option id='+ precio6 +' class="select_targets10" value='+ nombre6.id + '>' + nombre6.nombre + '</option>');
                    //Coloca el valor de el campo dos ya que esta por defecto
                    $('#product-price10').html('$ ' + nombre3.price);
                }
                // //Si algun tamaño es mayor a 0 pero los demas no entonces se acumula una sola opcion en el select de tamaño
                // if(nombre1.price > 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio1 = nombre1.price.replace(/\./g,'');
                //     $('#targets_select10').html('<option id='+ precio1 +' class="select_targets10" value='+ nombre1.id + '>' + nombre1.nombre + '</option>');
                //     $('#product-price10').html('$ ' + nombre1.price);
                // }
                // if(nombre1.price == 0 && nombre2.price > 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio2 = nombre2.price.replace(/\./g,'');
                //     $('#targets_select10').html('<option id='+ precio2 +' class="select_targets10" value='+ nombre2.id + '>' + nombre2.nombre + '</option>');
                //     $('#product-price10').html('$ ' + nombre2.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price > 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio3 = nombre3.price.replace(/\./g,'');
                //     $('#targets_select10').html('<option id='+ precio3 +' class="select_targets10" value='+ nombre3.id + '>' + nombre3.nombre + '</option>');
                //     $('#product-price10').html('$ ' + nombre3.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price > 0 && nombre5.price == 0 && nombre6.price == 0){
                //     var precio4 = nombre4.price.replace(/\./g,'');
                //     $('#targets_select10').html('<option id='+ precio4 +' class="select_targets10" value='+ nombre4.id + '>' + nombre4.nombre + '</option>');
                //     $('#product-price10').html('$ ' + nombre4.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price > 0 && nombre6.price == 0){
                //     var precio5 = nombre5.price.replace(/\./g,'');
                //     $('#targets_select10').html('<option id='+ precio5 +' class="select_targets10" value='+ nombre5.id + '>' + nombre5.nombre + '</option>');
                //     $('#product-price10').html('$ ' + nombre5.price);
                // }
                // if(nombre1.price == 0 && nombre2.price == 0 && nombre3.price == 0 && nombre4.price == 0 && nombre5.price == 0 && nombre6.price > 0){
                //     var precio6 = nombre6.price.replace(/\./g,'');
                //     $('#targets_select10').html('<option id='+ precio6 +' class="select_targets10" value='+ nombre6.id + '>' + nombre6.nombre + '</option>');
                //     $('#product-price10').html('$ ' + nombre6.price);
                // }
                var label_precio = document.getElementById("product-price10").classList.remove("hide");
                var label_precio_nuevo = document.getElementById("product-price_second10").classList.add("hide");
                calSubtotal();
                //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
                cambiarPrecioSelectChange10();
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional10").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple10').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
            }else if(dato[0].simple){
                $('.select_sizes10').remove();
                var sizes = document.getElementById("sizes10").classList.add("hide");
                $('.select_targets10').remove();
                var target = document.getElementById("objetivo10").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional10").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple10').html('');
                //ADICIONAL
                dato.forEach(function(element) {
                    if(element.adicion){
                        if(element.adicion.length > 0){
                            var adicional = document.getElementById("adicional10").classList.remove("hide");
                            for(var i = 0; i<element.adicion.length; i++){
                                var precio = element.adicion[i].price.replace(/\./g,'');
                                var id = element.adicion[i].id;
                                $('#multiple10').append('<option id="'+ id +'" value="'+ precio +'">' + element.adicion[i].nombre + " $ " + element.adicion[i].price + '</option>');
                            }
                        }
                    }
                });
                $('#product-price10').html('$ ' + dato[0].precio);
                calSubtotal();
            }else{
                //Si no hay tamaño, ni hay objetivo ni hay precio entonces
                //remueve las opciones del select de tamaño y remueve las opciones del select de objetivos
                //tambien deja el precio en 0
                $('.select_sizes10').remove();
                var sizes = document.getElementById("sizes10").classList.add("hide");
                $('.select_targets10').remove();
                var target = document.getElementById("objetivo10").classList.add("hide");
                //Oculta el select de adicionales
                var adicional = document.getElementById("adicional10").classList.add("hide");
                //Remueve las opciones del select de adicionales
                $('#multiple10').html('');
                $('#product-price10').html('$ 0');
                calSubtotal();
                alert(dato[0].datos);
            }
        }
    });
}
function precioSize10(){
    //Obtengo el id del option seleccionado
    var id_select_size = document.getElementById("sizes_select10").selectedOptions[0].id;
    //Formateo el numero con separador de miles
    id_select_size = id_select_size.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    id_select_size = id_select_size.split('').reverse().join('').replace(/^[\.]/,'');
    //Cambio el precio segun el id seleccionado
    $('#product-price10').html('$ ' + id_select_size);

    //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
    var label_precio = document.getElementById("product-price10").classList.remove("hide");
    var label_precio_nuevo = document.getElementById("product-price_second10").classList.add("hide");
    calSubtotal();
    //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
    cambiarPrecioSelectChange10();
    adicional10();
}
function precioObjetivo10(){
    //Obtengo el id del option seleccionado
    var id_select_objetivo = document.getElementById("targets_select10").selectedOptions[0].id;
    //Formateo el numero con separador de miles
    id_select_objetivo = id_select_objetivo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    id_select_objetivo = id_select_objetivo.split('').reverse().join('').replace(/^[\.]/,'');
    //Cambio el precio segun el id seleccionado
    $('#product-price10').html('$ ' + id_select_objetivo);

    //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
    var label_precio = document.getElementById("product-price10").classList.remove("hide");
    var label_precio_nuevo = document.getElementById("product-price_second10").classList.add("hide");
    calSubtotal();
    //Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
    cambiarPrecioSelectChange10();
    adicional10();
}
function adicional10(){
    var values = $('#multiple10').val();
    var nValues = values.length;
    var total = 0;
    for(var i = 0; i<nValues; i++){
        total = total + parseInt(values[i]);
    }
    var sizes = $('#sizes10').hasClass('hide');
    var objetivo = $('#objetivo10').hasClass('hide');
    var totalNuevo = 0;
    if(nValues > 0){
        $('#cantidadProducto10').val(1);
        $('#cantidadProducto10').attr('disabled', 'disabled');
    }else{
        $('#cantidadProducto10').removeAttr('disabled');
    }
    var cantidadProducto = document.getElementById("cantidadProducto10").value;
    if(!sizes){
        var id_select_size = document.getElementById("sizes_select10").selectedOptions[0].id;
        id_select_size = parseInt(id_select_size) * cantidadProducto;
        totalNuevo = parseInt(id_select_size) + total;
    }else if(!objetivo){
        var id_select_objetivo = document.getElementById("targets_select10").selectedOptions[0].id;
        id_select_objetivo = parseInt(id_select_objetivo) * cantidadProducto;
        totalNuevo = parseInt(id_select_objetivo) + total;
    }else{
        var productPrice = document.getElementById("product-price10").innerText;
        productPrice = productPrice.replace(/\./g,'');
        productPrice = productPrice.replace(/\ /g,'');
        productPrice = parseInt(productPrice.replace(/\$/g,''));
        var price = document.getElementById("product-price10").classList.add("hide");
        var price_second = document.getElementById("product-price_second10").classList.remove("hide");
        var pSimple = productPrice * cantidadProducto;
        totalNuevo = pSimple + total;
    }
    
    totalNuevo = totalNuevo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    totalNuevo = totalNuevo.split('').reverse().join('').replace(/^[\.]/,'');
    var product_price = $('#product-price10').hasClass('hide');
    if(product_price){
        $('#product-price_second10').html('$ ' + totalNuevo);
    }else{
        $('#product-price10').html('$ ' + totalNuevo);
    }
    calSubtotal();
}
//Por si el cliente toma un nuevo producto cuando ya habia cambiado la cantidad
function cambiarPrecioSelectChange10(){
    var cantidad = document.getElementById("cantidadProducto10").value;

    if(cantidad > 1){
        var precio = document.getElementById("product-price10").textContent;
        precio = precio.replace(/\./g,'');
        precio = precio.replace(/\$/g,'');
        var precionuevo = precio * cantidad;
        //Formateo el numero con separador de miles
        precionuevo = precionuevo.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        precionuevo = precionuevo.split('').reverse().join('').replace(/^[\.]/,'');
        //Cambio el precio segun el nuevo resultado en un nuevo div, el anterior lo oculto
        $('#product-price_second10').html('$ ' + precionuevo);
        var label_precio = document.getElementById("product-price10").classList.add("hide");
        var label_precio_nuevo = document.getElementById("product-price_second10").classList.remove("hide");
        calSubtotal();
    }
}