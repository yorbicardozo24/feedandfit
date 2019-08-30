<?php
    session_start();
    //Verifico si hay una session activa
    if(isset($_SESSION["usuario"])){
    //Incluyo la conexion a la base de datos y a la funcion que destruye la session despues de un tiempo
    require 'includes/conexion.php';
    require 'includes/fun.php';
    //Incluyo el header y el menu
    include('includes/header/header.php');
    include('includes/menu/menu.php');

    $id_pedido = $_GET['id'];

    $sql = "SELECT * FROM orders WHERE id = '$id_pedido'";
    $resultado = pg_query($sql);
    $fila = pg_fetch_array($resultado, null, PGSQL_ASSOC);
    $facturaId = $fila['legal_invoice_id'];
    //Consulto los todos los clientes
    $sql = "SELECT * FROM customers ORDER BY name ASC";
    $resultado = pg_query($sql);
    //Consulto todos los productos que esten activos
    $query = "SELECT * FROM products WHERE active = 'true' ORDER BY name ASC";
    $result = pg_query($query);
    $que = "SELECT * FROM delivery_zones ORDER BY name ASC";
    $resul = pg_query($que);
?>
<div class="content">
    <input type="hidden" id="idPedido" value="<?php echo $id_pedido; ?>">
    <div id="contenedor_carga">
        <div id="carga"></div>
    </div>
    <section class="content-header" id="content-header">
        <div class="row" id="nueva_orden"><h1>Actualizar Orden #</h1></div>
        <div class="box-body" id="box-body">
            <div class="row box box-body">
                <div class="col-md-12">
                    <div class="box-primary">
                        <form autocomplete="off">
                            <?php if($facturaId > 0){ ?>
                            <div id="oculto">
                            <?php }else{ ?>
                            <div id="bien">
                            <?php } ?>
                            <div class="row">
                                <!-- <div class="col-md-3"></div> -->
                                <div class="col-md-3 form-group">
                                    <label>Cliente</label>
                                    <a data-toggle="modal" data-target="#addCliente" style="cursor: pointer;">Nuevo cliente</a>
                                    <!-- Select para direccion del cliente -->
                                    <select id="select" class="form-control" onChange="selectId()">
                                        <option>Escoge</option>
                                        <?php while ($line = pg_fetch_array($resultado, null, PGSQL_ASSOC)) { ?>
                                        <option value="<?php echo $line['id'];?>"><?php echo $line['name'] . " " . $line['last_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Hora de Entrega</label>
                                        <input type="time" class="form-control" id="horaEntrega" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Forma de pago</label>
                                        <select class="form-control" id="forma-pago">
                                            <option selected="selected" value="2">Efectivo</option>
                                            <option value="1">Consignación</option>
                                            <option value="3">iFood</option>
                                            <option value="4">UberEats</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Direcciones</label>
                                    <a id="addNuevaDireccion" data-toggle="modal" data-target="#addDireccion" style="cursor: pointer;">Nueva Dirección</a>
                                    <select id="direccion_select" class="form-control" multiple="multiple" onChange="selectDirection()"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Observación</label>
                                        <textarea class="form-control" id="observacion"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 style="font-weight: 400;">Selección de productos</h4>
                                </div>
                                <div class="col-md-12" id="addNewProduct">
                                    <div class="row" id="1">
                                        <div class="col-md-3">
                                            <label>Producto</label>
                                            <!-- Select para precio del producto -->
                                            <select class="form-control" id="product" onChange="producto()">
                                                <option value="Escoge">Escoge</option>
                                                <?php while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) { ?>
                                                <option value="<?php echo $row['id'];?>"><?php echo $row['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div id="sizes" class="col-md-2 hide">
                                            <label>Tamaño</label>
                                            <select class="form-control" id="sizes_select" onChange="precioSize()">
                                                <option>Escoge tamaño</option>
                                            </select>
                                        </div>
                                        <div id="objetivo" class="col-md-2 hide">
                                            <label>Objetivo</label>
                                            <select class="form-control" id="targets_select" onChange="precioObjetivo()">
                                                <option>Elige objetivo</option>
                                            </select>
                                        </div>
                                        <div id="adicional" class="col-md-2 hide">
                                            <label>Adicional</label>
                                            <select onchange="adicional()" class="form-control" multiple="multiple" id="multiple" style="width:120px">
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Cantidad</label>
                                            <input id="cantidadProducto" class="form-control only-number" type="number" onChange="adicional()" value="1" min="1" />
                                        </div>
                                        <div class="col-md-2 precios">
                                            <label>Precio</label>
                                            <div id="product-price" style="padding-top: 5px;">$0</div>
                                            <div id="product-price_second" style="padding-top: 5px;" class="hide"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if($facturaId > 0){ ?>
                            <?php }else{ ?>
                            <div class="row">
                                <div class="col-md-12 mt20">
                                    <a style="cursor: pointer" onClick="addProduct('nuevoPedido')">Añadir otro producto</a>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="row mt20">
                                <div class="col-md-2">
                                    <label>Descuento</label>
                                    <br />
                                    <span style="margin-top: -5px; padding-right: 30px;">
                                        <label class="form-check-label" for="porcentaje">%</label>
                                        <input class="form-check-input" type="radio" name="radios" id="porcentaje" value="porcentaje">
                                    </span>
                                    <span style="margin-top: -5px;">
                                        <label class="form-check-label" for="ve">$</label>
                                        <input class="form-check-input" type="radio" name="radios" id="ve" value="ve" checked>
                                    </span>
                                    <input class="form-control only-number" type="text" onkeyup="format(this)" id="descuento" />
                                </div>
                            </div>
                            <div class="row mt20">
                                <div class="col-md-4">
                                    <label>Valor Domicilio</label>
                                    <h5 id="domicilio">$ 0</h5>
                                </div>
                                <div class="col-md-4">
                                    <label>Subtotal</label>
                                    <h5 id="subtotal">$ 0</h5>
                                    <input id="subtotalHide" value="" class="hide" />
                                </div>
                                <div class="col-md-4">
                                    <label>Total</label>
                                    <h1 id="totalPedido">$ 0</h1>
                                </div>
                            </div>
                            </div>
                            <div class="row box-footer">
                                <div class="col-md-12">
                                    <input type="submit" value="Guardar" onClick="actualizarPedido()" class="btn btn-primary" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
        <!-- VENTANA MODAL NUEVO CLIENTE -->
    <div class="modal fade" id="addCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <section class="content-header" id="content-header">
        <div class="row" id="nueva-orden"><h1>Nuevo Cliente</h1></div>
        <div class="box-body" id="box-body">
            <div class="row box box-body">
                <div class="col-md-12">
                    <div class="box-primary">
                        <form autocomplete="off">
                            <div class="row">
                                <!-- <div class="col-md-3"></div> -->
                                <div class="col-md-3 form-group">
                                    <label for="nombres">Nombres</label>
                                    <input id="nombres" class="form-control" type="text" placeholder="Nombres" />
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="apellidos">Apellidos</label>
                                    <input id="apellidos" class="form-control" type="text" placeholder="Apellidos" />
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Tipo Documento</label>
                                    <select id="tipo" class="form-control">
                                      <option value="0">Elige tipo</option>
                                      <option value="CC" selected>CC</option>
                                      <option value="NIT">NIT</option>
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="documento">No. Documento</label>
                                    <input id="documento" class="form-control" type="text" placeholder="No. Documento" />
                                </div>
                            </div>
                            <div class="row">
                              <div class="col-md-3 form-group">
                                <label for="email">Correo Electrónico</label>
                                <input id="email" type="text" class="form-control" placeholder="Correo Electrónico" />
                              </div>
                              <div class="col-md-3 form-group">
                                <label for="phone">Teléfono</label>
                                <input id="phone" type="tel" class="form-control" placeholder="Teléfono" />
                              </div>
                              <div class="col-md-3 form-group">
                                <label for="hora">Hora</label>
                                <input id="hora" type="time" class="form-control" placeholder="Hora" />
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6 form-group">
                                <label for="observaciones">Observaciones</label>
                                <textarea id="observaciones" class="form-control" placeholder="Observaciones"></textarea>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <h4>Direcciones del Cliente</h4>
                            </div>
                            <div class="row" id="adicion">
                              <div class="row" id="1">
                                <div class="col-md-3 form-group">
                                  <label for="direccion">Dirección</label>
                                  <input id="direccion" type="text" class="form-control" placeholder="Dirección" />
                                </div>
                                <div class="col-md-3 form-group">
                                  <label for="zona">Zona Domiciliaría</label>
                                  <select id="zona" class="form-control" style="width: 150px;">
                                    <option value="Escoge">Escoge</option>
                                    <?php while ($row = pg_fetch_array($resul, null, PGSQL_ASSOC)) { ?>
                                    <option value="<?php echo $row['id'];?>"><?php echo ucfirst(strtolower($row['name'])); ?></option>
                                    <?php } ?>
                                   </select>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt20">
                                    <a onclick="addDireccion()" style="cursor: pointer">Añadir otra dirección</a>
                                </div>
                            </div>
                            <div class="row box-footer">
                                <div class="col-md-12">
                                    <a onclick="guardarCliente('nuevoPedido')" class="btn btn-primary">Guardar</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</div>
    </div>
<!-- VENTANA MODAL NUEVA DIRECCION -->
    <div class="modal fade" id="addDireccion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <section class="content-header" id="content-header">
        <div class="row" id="nueva-orden"><h1>Nueva Dirección</h1></div>
        <div class="box-body" id="box-body">
            <div class="row box" style="padding-top: 15px;">
                <div class="col-md-12">
                    <div class="box-primary">
                        <form autocomplete="off">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>Cliente</label>
                                    <!-- Select para direccion del cliente -->
                                    <select id="clienteNewZona" class="form-control" style="width: 200px;">
                                        <option>Escoge</option>
                                        <?php 
                                        $clienteNewZona = "SELECT * FROM customers ORDER BY name ASC";
                                        $resultadoClienteNewZona = pg_query($clienteNewZona);
                                        while ($line = pg_fetch_array($resultadoClienteNewZona, null, PGSQL_ASSOC)) { ?>
                                        <option value="<?php echo $line['id'];?>"><?php echo $line['name'] . " " . $line['last_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                  <label for="direccion">Dirección</label>
                                  <input id="direccionNewZona" type="text" class="form-control" placeholder="Dirección" />
                                </div>
                                <div class="col-md-4 form-group">
                                  <label for="newZona">Zona Domiciliaría</label>
                                  <select id="newZona" class="form-control" style="width: 200px;">
                                    <option value="Escoge">Escoge</option>
                                    <?php
                                    $zona = "SELECT * FROM delivery_zones ORDER BY name ASC";
                                    $resulZona = pg_query($zona); 
                                    while ($fila = pg_fetch_array($resulZona, null, PGSQL_ASSOC)) { 
                                        ?>
                                    <option value="<?php echo $fila['id'];?>"><?php echo ucfirst(strtolower($fila['name'])); ?></option>
                                    <?php } ?>
                                   </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <a class="btn btn-primary" onclick="newDireccion()">Guardar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#zona').select2();
        $('#newZona').select2();
        $('#clienteNewZona').select2();
    });
    $("#subtotal").bind("DOMSubtreeModified",function(){
        var subtotalNeto = document.getElementById("subtotal").innerText;
        subtotalNeto = subtotalNeto.replace(/\./g,'');
        subtotalNeto = subtotalNeto.replace(/\ /g,'');
        subtotalNeto = parseInt(subtotalNeto.replace(/\$/g,''));
        // var subtotalNeto = $('#subtotalHide').val();
        if(!subtotalNeto){
            subtotalNeto = 0;
        }
        var domicilio = document.getElementById("domicilio").innerText;
        domicilio = domicilio.replace(/\./g,'');
        domicilio = domicilio.replace(/\ /g,'');
        domicilio = parseInt(domicilio.replace(/\$/g,''));
        var total = domicilio + parseInt(subtotalNeto);
        total = total.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        total = total.split('').reverse().join('').replace(/^[\.]/,'');
        $('#totalPedido').html('$ ' + total);
    });
    $("#domicilio").bind("DOMSubtreeModified",function(){
        var subtotalNeto = document.getElementById("subtotal").innerText;
        subtotalNeto = subtotalNeto.replace(/\./g,'');
        subtotalNeto = subtotalNeto.replace(/\ /g,'');
        subtotalNeto = parseInt(subtotalNeto.replace(/\$/g,''));
        // var subtotalNeto = $('#subtotalHide').val();
        if(!subtotalNeto){
            subtotalNeto = 0;
        }
        var domicilio = document.getElementById("domicilio").innerText;
        domicilio = domicilio.replace(/\./g,'');
        domicilio = domicilio.replace(/\ /g,'');
        domicilio = parseInt(domicilio.replace(/\$/g,''));
        var total = domicilio + parseInt(subtotalNeto);
        total = total.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        total = total.split('').reverse().join('').replace(/^[\.]/,'');
        $('#totalPedido').html('$ ' + total);
    });
    function eliminarProducto(id){
        var fila = parseInt(document.getElementById("addNewProduct").childElementCount);
        $('#addNewProduct #' + id).remove();
        calSubtotal();
        if(fila == '2'){
            var fila = document.getElementById("addNewProduct");
            var idFila = fila.children[0].id;
            $('#addNewProduct #' + idFila + ' .col-md-1').remove();
        }
    }
    function newDireccion(){
        event.preventDefault();
        var cliente = document.getElementById("clienteNewZona").value;
        var direccion = document.getElementById("direccionNewZona").value;
        var zona = document.getElementById("newZona").value;

        if(cliente === "Escoge"){
            alertify.error("Por favor seleccione un cliente.");
            window.scroll({top: 0, behavior: 'smooth'});
            var selectCliente = document.getElementById("clienteNewZona").focus();
        }else{
            if(!direccion){
                alertify.error("Por favor escriba una dirección.");
                window.scroll({top: 0, behavior: 'smooth'});
                var selectCliente = document.getElementById("direccionNewZona").focus();
            }else{
                if(zona == "Escoge"){
                    alertify.error("Por favor seleccione una zona.");
                    window.scroll({top: 0, behavior: 'smooth'});
                    var selectCliente = document.getElementById("newZona").focus();
                }else{
                    var datos = {
                        idCliente: cliente,
                        direccion: direccion,
                        idZona: zona
                    }
                    datos = JSON.stringify(datos);
                    var url = "includes/zonas/addDireccion.php";
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {data: datos},
                        success: function(data){
                            if(data == '1'){
                                alertify.success("Dirección añadida correctamente.");
                                location.reload();
                            }else{
                                alertify.error("Datos incorrectos, por favor revise.");
                            }
                        }
                    });
                }
            }
        }
    }
    function format(input){
        var num = input.value.replace(/\./g,'');
        var metodoDescuento = $('input:radio[name=radios]:checked').val();

        if(metodoDescuento == 've'){
            $('#descuento').removeAttr('maxlength');
            if(!isNaN(num)){
                num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
                num = num.split('').reverse().join('').replace(/^[\.]/,'');
                input.value = num;
            }else{
                alert('Solo se permiten numeros');
                input.value = input.value.replace(/[^\d\.]*/g,'');
            }
        }else{
            $('#descuento').attr('maxlength', '2');
            if(!isNaN(num)){
                num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
                num = num.split('').reverse().join('').replace(/^[\.]/,'');
                input.value = num;
            }else{
                alert('Solo se permiten numeros');
                input.value = input.value.replace(/[^\d\.]*/g,'');
            }
        }

        calSubtotal();

    }
    $('input:radio[name=radios]').change(function(){
        $('#descuento').val(' ');
        var descuentoP = document.getElementById("descuento").value;
        var value_without_space = $.trim(descuentoP);
        $('#descuento').val(value_without_space);
        $('#descuento').focus();
        calSubtotal();
    });
    //Para guardar info del pedido
    function actualizarPedido(){
        event.preventDefault();
        var idOrden = document.getElementById("idOrden").value;
        var datos = [];
        //Captura el cliente
        var cliente = document.getElementById("select").value;
        if(cliente === "Escoge"){
            alertify.error("Por favor seleccione un cliente.");
            window.scroll({top: 0, behavior: 'smooth'});
            var selectCliente = document.getElementById("select").focus();
        }else{
            var productoInicial = document.getElementById("addNewProduct").children[0].children[0].children[1].value;
            if(productoInicial === 'Escoge'){
                alertify.error("Por favor seleccione un producto.");
                window.scroll({top: 0, behavior: 'smooth'});
            }else{
                var horaEntrega = document.getElementById("horaEntrega").value;

                var obtenerFecha = new Date();
                var dia = obtenerFecha.getDate();
                var mes = obtenerFecha.getMonth() + 1;
                var year = obtenerFecha.getFullYear();


                var hora = obtenerFecha.getHours();
                var minutos = obtenerFecha.getMinutes();
                var segundos = obtenerFecha.getSeconds();

                var fecha_actual = year + '/' + mes + '/' + dia + ' ' + hora + ':' + minutos + ':' + segundos;

                if(horaEntrega === ''){
                    var sumarsesion = 40; //40 minutos haciendo el pedido
                    obtenerFecha.setMinutes(minutos + sumarsesion);
                    if(minutos < 20){
                        minutos = obtenerFecha.getMinutes();
                    }else{
                        minutos = obtenerFecha.getMinutes();

                        switch(minutos){
                        case 0:
                            minutos = '00';
                        case 1:
                            minutos = '01';
                            break;
                        case 2:
                            minutos = "02";
                            break;
                        case 3:
                            minutos = "03";
                            break;
                        case 4:
                            minutos = "04";
                            break;
                        case 5:
                            minutos = "05";
                            break;
                        case 6:
                            minutos = "06";
                            break;
                        case 7:
                            minutos = "07";
                            break;
                        case 8:
                            minutos = "08";
                            break;
                        case 9:
                            minutos = "09";
                            break;
                    }
                        hora = hora + 1;
                    }
                    horaEntrega = hora + ':' + minutos;
                }
                //Fecha
                var fecha = year + '/' + mes + '/' + dia + ' ' + hora + ':' + minutos + ':' + segundos;
                //Forma de pago
                var formaPago = document.getElementById("forma-pago").value;
                //Direccion del cliente
                var direccionCliente = document.getElementById("direccion_select").selectedOptions[0].value;
                //Observacion
                var observacion = document.getElementById("observacion").value;
                //Productos
                var filasProductos = document.getElementById("addNewProduct").childElementCount;
                var filas = document.getElementById("addNewProduct");
                var cantidad = 0;
                var productos = [];
                for(var y = 0; y < filasProductos; y++){
                    cantidad = filas.children[y].children[4].children[1].value;
                    if(cantidad > 1){
                        precio = filas.children[y].children[5].children[2].textContent;
                    }else{
                        precio = filas.children[y].children[5].children[1].textContent;
                    }
                    var adicional = filas.children[y].children[3].children[1];
                    var nAdicional = filas.children[y].children[3].children[1].selectedOptions.length;
                    var adicionalArray = [];
                    for(var x = 0; x<nAdicional; x++){
                        adicionalArray.push({
                            nombre: adicional.selectedOptions[x].text,
                            precio: adicional.selectedOptions[x].value,
                            id: adicional.selectedOptions[x].id
                        });
                    }

                    productos.push({
                        producto: filas.children[y].children[0].children[1].value,
                        size: filas.children[y].children[1].children[1].value,
                        objetivo: filas.children[y].children[2].children[1].value,
                        cantidad: cantidad,
                        adicional: adicionalArray,
                        precio: precio
                    });
                }
                //SUBTOTAL
                var subtotalNeto = $('#subtotalHide').val();
                //Descuento
                var descuento = document.getElementById("descuento").value;
                //Por los puntos
                descuento = descuento.replace(/\./g,'');
                //Por los espacios
                descuento = descuento.replace(/\ /g,'');
                var metodoDescuento = $('input:radio[name=radios]:checked').val();
                //Domicilio
                var domicilio = document.getElementById("domicilio").textContent;
                datos.push({
                    idPedido: idOrden,
                    cliente: cliente,
                    fecha: fecha_actual,
                    horaEntrega: horaEntrega,
                    formaPago: formaPago,
                    direccionCliente: direccionCliente,
                    observacionCliente: observacion,
                    productos: productos,
                    metodoDescuento: metodoDescuento,
                    valorDescuento: descuento,
                    valorDomicilio: domicilio,
                    subtotalNeto: subtotalNeto
                });
                console.log(datos);
                datos = JSON.stringify(datos);
                var url = "includes/pedidos/actualizar_pedido.php";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {data: datos},
                    success: function(data){
                        console.log(data);
                        if(data == '1'){
                            window.location.href ="../pedidos";
                        }else{
                            alertify.error("Datos invalidos, por favor revise.");
                        }
                    }
                });
            }
        }
    }
window.onload = function(){
    var id = document.getElementById("idPedido").value;
    var url = "includes/pedidos/ver_pedido.php";
    $.ajax({
        async: false,
        type: "POST",
        url: url,
        data: {data: id, from: "editarPedido", historial: "no"},
        success: function(data){      
            var datos = JSON.parse(data);
            //TEXTO DE ACTUALIZAR ORDEN
            var nueva_orden = document.getElementById("nueva_orden").innerHTML = '<h1>Actualizar Orden #'+ id +' </h1><input type="hidden" id="idOrden" value="'+id+'" />';
            //PARA SELECCIONAR CLIENTE
            var select = document.getElementById("select");
            var cliente = datos.id_cliente;
            for(var i=1;i<select.length;i++){
                if(select.options[i].value==cliente){
                    // seleccionamos el valor que coincide
                    select.selectedIndex=i;
                }
            }
            $('#select').select2();
            selectId();
            //PARA SELECCIONAR DIRECCION
            var direccionSelect = document.getElementById("direccion_select");
            var direccion = datos.direccion;
            for(var i=1;i<direccionSelect.length;i++){
                if(direccionSelect.options[i].text==direccion){
                    // seleccionamos el valor que coincide
                    direccionSelect.selectedIndex=i;
                }
            }
            selectDirection();
            //PARA LA HORA DE ENTREGA
            var hora = datos.horaentrega;
            var horaEntrega = document.getElementById('horaEntrega').value = hora;
            //PARA OBSERVACION
            $('#observacion').val(datos.observacionPedido);
            //PARA LA FORMA DE PAGO
            var formapago = document.getElementById("forma-pago");
            var forma_pago = parseInt(datos.formapago);
            if(forma_pago == 2){
                formapago.selectedIndex = 'Efectivo';
            }else if(forma_pago == 1){
                formapago.selectedIndex = 'Consignación';
            }else if(forma_pago == 3){
                formapago.selectedIndex = 'iFood';
            }else if(forma_pago == 4){
                formapago.selectedIndex = 'UberEats';
            }
            //PRODUCTOS
            var nProduct = datos.productos.length;
            for(var a = 0; a<nProduct; a++){
                if(a == 0){
                    var product = document.getElementById("product");
                    var productoo = datos.productos[0].id;
                
                    for(var i=1;i<product.length;i++){
                        if(product.options[i].value === productoo){
                            //seleccionamos el valor que coincide
                            product.selectedIndex=i;
                            producto();
                        }
                    }
                    $('#cantidadProducto').val(datos.productos[0].cantidad);
                    $('#product').select2();

                    if($('#sizes').hasClass('hide')  == true){
                        var targets_select = document.getElementById("targets_select");
                        for(var i=0;i<targets_select.length;i++){
                            if(targets_select.options[i].text === datos.productos[0].size){
                                //seleccionamos el valor que coincide
                                targets_select.selectedIndex=i;
                            }
                        }
                        if(targets_select.length > 1){
                            precioObjetivo();
                        }
                        if($('#adicional').hasClass('hide') == false){
                            var adicionalSelect = document.getElementById("multiple");
                            var nAdicional = datos.productos[0].adiciones.length;
                            for(var x = 0; x<adicionalSelect.length; x++){
                                for(var i = 0; i<nAdicional; i++){
                                    if(adicionalSelect.options[x].id == datos.productos[0].adiciones[i].id){
                                        $("#multiple option[id="+datos.productos[0].adiciones[i].id+"]").prop("selected",true);
                                    }
                                }
                            }
                            $('#multiple').select2();
                            adicional();
                        }
                    }else{
                        var size_select = document.getElementById("sizes_select");
                        for(var i=0;i<size_select.length;i++){
                            if(size_select.options[i].text === datos.productos[0].size){
                                //seleccionamos el valor que coincide
                                size_select.selectedIndex=i;
                            }
                        }
                        if(size_select.length > 1){
                            precioSize();
                        }
                        if($('#adicional').hasClass('hide') == false){
                            var adicionalSelect = document.getElementById("multiple");
                            var nAdicional = datos.productos[0].adiciones.length;
                            for(var x = 0; x<adicionalSelect.length; x++){
                                for(var i = 0; i<nAdicional; i++){
                                    if(adicionalSelect.options[x].id == datos.productos[0].adiciones[i].id){
                                        $("#multiple option[id="+datos.productos[0].adiciones[i].id+"]").prop("selected",true);
                                    }
                                }
                            }
                            $('#multiple').select2();
                            adicional();
                        }
                    }
                }else if(a == 1){
                    addProduct('nuevoPedido');
                    var product = document.getElementById("product2");
                    var productoo = datos.productos[1].id;
            
                    for(var i=1;i<product.length;i++){
                        if(product.options[i].value === productoo){
                            //seleccionamos el valor que coincide
                            product.selectedIndex=i;
                            producto2();
                        }
                    }
                    $('#cantidadProducto2').val(datos.productos[1].cantidad);
                    $('#product2').select2();

                    if($('#sizes2').hasClass('hide') == true){
                        var targets_select = document.getElementById("targets_select2");
                        for(var i=0;i<targets_select.length;i++){
                            if(targets_select.options[i].text === datos.productos[1].size){
                                //seleccionamos el valor que coincide
                                targets_select.selectedIndex=i;
                            }
                        }
                        if(targets_select.length > 1){
                            precioObjetivo2();
                        }
                        if($('#adicional2').hasClass('hide') == false){
                            var adicionalSelect = document.getElementById("multiple2");
                            var nAdicional = datos.productos[1].adiciones.length;
                            for(var x = 0; x<adicionalSelect.length; x++){
                                for(var i = 0; i<nAdicional; i++){
                                    if(adicionalSelect.options[x].id == datos.productos[1].adiciones[i].id){
                                        $("#multiple2 option[id="+datos.productos[1].adiciones[i].id+"]").prop("selected",true);
                                    }
                                }
                            }
                            $('#multiple2').select2();
                            adicional2();
                        }
                    }else{
                        var size_select = document.getElementById("sizes_select2");
                        for(var i=0;i<size_select.length;i++){
                            if(size_select.options[i].text === datos.productos[1].size){
                                //seleccionamos el valor que coincide
                                size_select.selectedIndex=i;
                            }
                        }
                        if(size_select.length > 1){
                            precioSize2();
                        }
                        if($('#adicional2').hasClass('hide') == false){
                            var adicionalSelect = document.getElementById("multiple2");
                            var nAdicional = datos.productos[1].adiciones.length;
                            for(var x = 0; x<adicionalSelect.length; x++){
                                for(var i = 0; i<nAdicional; i++){
                                    if(adicionalSelect.options[x].id == datos.productos[1].adiciones[i].id){
                                        $("#multiple2 option[id="+datos.productos[1].adiciones[i].id+"]").prop("selected",true);
                                    }
                                }
                            }
                            $('#multiple2').select2();
                            adicional2();
                        }
                    }
                }else if(a == 2){
                    addProduct('nuevoPedido');
                    var product = document.getElementById("product3");
                    var productoo = datos.productos[2].id;
            
                    for(var i=1;i<product.length;i++){
                        if(product.options[i].value === productoo){
                            //seleccionamos el valor que coincide
                            product.selectedIndex=i;
                            producto3();
                        }
                    }
                    $('#cantidadProducto3').val(datos.productos[2].cantidad);
                    $('#product3').select2();
                            
                    if($('#sizes3').hasClass('hide') == true){
                        var targets_select = document.getElementById("targets_select3");
                        for(var i=0;i<targets_select.length;i++){
                            if(targets_select.options[i].text === datos.productos[2].size){
                                //seleccionamos el valor que coincide
                                targets_select.selectedIndex=i;
                            }
                        }
                        if(targets_select.length > 1){
                            precioObjetivo3();
                        }
                        
                        if($('#adicional3').hasClass('hide') == false){
                            var adicionalSelect = document.getElementById("multiple3");
                            var nAdicional = datos.productos[2].adiciones.length;
                            for(var x = 0; x<adicionalSelect.length; x++){
                                for(var i = 0; i<nAdicional; i++){
                                    if(adicionalSelect.options[x].id == datos.productos[2].adiciones[i].id){
                                        $("#multiple3 option[id="+datos.productos[2].adiciones[i].id+"]").prop("selected",true);
                                    }
                                }
                            }
                            $('#multiple3').select2();
                            adicional3();
                        }
                    }else{
                        var size_select = document.getElementById("sizes_select3");
                        for(var i=0;i<size_select.length;i++){
                            if(size_select.options[i].text === datos.productos[2].size){
                                //seleccionamos el valor que coincide
                                size_select.selectedIndex=i;
                            }
                        }
                        if(size_select.length > 1){
                            precioSize3();
                        }
                        
                        if($('#adicional3').hasClass('hide') == false){
                            var adicionalSelect = document.getElementById("multiple3");
                            var nAdicional = datos.productos[2].adiciones.length;
                            for(var x = 0; x<adicionalSelect.length; x++){
                                for(var i = 0; i<nAdicional; i++){
                                    if(adicionalSelect.options[x].id == datos.productos[2].adiciones[i].id){
                                        $("#multiple3 option[id="+datos.productos[2].adiciones[i].id+"]").prop("selected",true);
                                    }
                                }
                            }
                            $('#multiple3').select2();
                            adicional3();
                        }
                    }

                }else if(a == 3){
                    addProduct('nuevoPedido');
                    var product = document.getElementById("product4");
                    var productoo = datos.productos[3].id;
            
                    for(var i=1;i<product.length;i++){
                        if(product.options[i].value === productoo){
                            //seleccionamos el valor que coincide
                            product.selectedIndex=i;
                            producto4();
                        }
                    }
                    $('#cantidadProducto4').val(datos.productos[3].cantidad);
                    $('#product4').select2();
                    
                    if($('#sizes4').hasClass('hide') == true){
                        var targets_select = document.getElementById("targets_select4");
                        for(var i=0;i<targets_select.length;i++){
                            if(targets_select.options[i].text === datos.productos[3].size){
                                //seleccionamos el valor que coincide
                                targets_select.selectedIndex=i;
                            }
                        }
                        if(targets_select.length > 1){
                            precioObjetivo4();
                        }
                        if($('#adicional4').hasClass('hide') == false){
                            var adicionalSelect = document.getElementById("multiple4");
                            var nAdicional = datos.productos[3].adiciones.length;
                            for(var x = 0; x<adicionalSelect.length; x++){
                                for(var i = 0; i<nAdicional; i++){
                                    if(adicionalSelect.options[x].id == datos.productos[3].adiciones[i].id){
                                        $("#multiple4 option[id="+datos.productos[3].adiciones[i].id+"]").prop("selected",true);
                                    }
                                }
                            }
                            $('#multiple4').select2();
                            adicional4();
                        }
                    }else{
                        var size_select = document.getElementById("sizes_select4");
                        for(var i=0;i<size_select.length;i++){
                            if(size_select.options[i].text === datos.productos[3].size){
                                //seleccionamos el valor que coincide
                                size_select.selectedIndex=i;
                            }
                        }
                        if(size_select.length > 1){
                            precioSize4();
                        }
                        if($('#adicional4').hasClass('hide') == false){
                            var adicionalSelect = document.getElementById("multiple4");
                            var nAdicional = datos.productos[3].adiciones.length;
                            for(var x = 0; x<adicionalSelect.length; x++){
                                for(var i = 0; i<nAdicional; i++){
                                    if(adicionalSelect.options[x].id == datos.productos[3].adiciones[i].id){
                                        $("#multiple4 option[id="+datos.productos[3].adiciones[i].id+"]").prop("selected",true);
                                    }
                                }
                            }
                            $('#multiple4').select2();
                            adicional4();
                        }
                    }
                }else if(a == 4){
                    addProduct('nuevoPedido');
                    var product = document.getElementById("product5");
                    var productoo = datos.productos[4].id;
            
                    for(var i=1;i<product.length;i++){
                        if(product.options[i].value === productoo){
                            //seleccionamos el valor que coincide
                            product.selectedIndex=i;
                            producto5();
                        }
                    }
                    $('#cantidadProducto5').val(datos.productos[4].cantidad);
                    $('#product5').select2();
                    
                    if($('#sizes5').hasClass('hide') == true){
                        var targets_select = document.getElementById("targets_select5");
                        for(var i=0;i<targets_select.length;i++){
                            if(targets_select.options[i].text === datos.productos[4].size){
                                //seleccionamos el valor que coincide
                                targets_select.selectedIndex=i;
                            }
                        }
                        if(targets_select.length > 1){
                            precioObjetivo5();
                        }
                        if($('#adicional5').hasClass('hide') == false){
                            var adicionalSelect = document.getElementById("multiple5");
                            var nAdicional = datos.productos[4].adiciones.length;
                            for(var x = 0; x<adicionalSelect.length; x++){
                                for(var i = 0; i<nAdicional; i++){
                                    if(adicionalSelect.options[x].id == datos.productos[4].adiciones[i].id){
                                        $("#multiple5 option[id="+datos.productos[4].adiciones[i].id+"]").prop("selected",true);
                                    }
                                }
                            }
                            $('#multiple5').select2();
                            adicional5();
                        }
                    }else{
                        var size_select = document.getElementById("sizes_select5");
                        for(var i=0;i<size_select.length;i++){
                            if(size_select.options[i].text === datos.productos[4].size){
                                //seleccionamos el valor que coincide
                                size_select.selectedIndex=i;
                            }
                        }
                        if(size_select.length > 1){
                            precioSize5();
                        }
                        if($('#adicional5').hasClass('hide') == false){
                            var adicionalSelect = document.getElementById("multiple5");
                            var nAdicional = datos.productos[4].adiciones.length;
                            for(var x = 0; x<adicionalSelect.length; x++){
                                for(var i = 0; i<nAdicional; i++){
                                    if(adicionalSelect.options[x].id == datos.productos[4].adiciones[i].id){
                                        $("#multiple5 option[id="+datos.productos[4].adiciones[i].id+"]").prop("selected",true);
                                    }
                                }
                            }
                            $('#multiple5').select2();
                            adicional5();
                        }
                    }
                }else if(a == 5){
                    addProduct('nuevoPedido');
                    var product = document.getElementById("product6");
                    var productoo = datos.productos[5].id;
            
                    for(var i=1;i<product.length;i++){
                        if(product.options[i].value === productoo){
                            //seleccionamos el valor que coincide
                            product.selectedIndex=i;
                            producto6();
                        }
                    }
                    $('#cantidadProducto6').val(datos.productos[5].cantidad);
                    $('#product6').select2();
                    
                    if($('#sizes6').hasClass('hide') == true){
                        var targets_select = document.getElementById("targets_select6");
                        for(var i=0;i<targets_select.length;i++){
                            if(targets_select.options[i].text === datos.productos[5].size){
                                //seleccionamos el valor que coincide
                                targets_select.selectedIndex=i;
                            }
                        }
                        if(targets_select.length > 1){
                            precioObjetivo6();
                        }
                        if($('#adicional6').hasClass('hide') == false){
                            var adicionalSelect = document.getElementById("multiple6");
                            var nAdicional = datos.productos[5].adiciones.length;
                            for(var x = 0; x<adicionalSelect.length; x++){
                                for(var i = 0; i<nAdicional; i++){
                                    if(adicionalSelect.options[x].id == datos.productos[5].adiciones[i].id){
                                        $("#multiple6 option[id="+datos.productos[5].adiciones[i].id+"]").prop("selected",true);
                                    }
                                }
                            }
                            $('#multiple6').select2();
                            adicional6();
                        }
                    }else{
                        var size_select = document.getElementById("sizes_select6");
                        for(var i=0;i<size_select.length;i++){
                            if(size_select.options[i].text === datos.productos[5].size){
                                //seleccionamos el valor que coincide
                                size_select.selectedIndex=i;
                            }
                        }
                        if(size_select.length > 1){
                            precioSize6();
                        }
                        if($('#adicional6').hasClass('hide') == false){
                            var adicionalSelect = document.getElementById("multiple6");
                            var nAdicional = datos.productos[5].adiciones.length;
                            for(var x = 0; x<adicionalSelect.length; x++){
                                for(var i = 0; i<nAdicional; i++){
                                    if(adicionalSelect.options[x].id == datos.productos[5].adiciones[i].id){
                                        $("#multiple6 option[id="+datos.productos[5].adiciones[i].id+"]").prop("selected",true);
                                    }
                                }
                            }
                            $('#multiple6').select2();
                            adicional6();
                        }
                    }
                }else if(a == 6){
                    addProduct('nuevoPedido');
                    var product = document.getElementById("product7");
                    var productoo = datos.productos[6].id;
            
                    for(var i=1;i<product.length;i++){
                        if(product.options[i].value === productoo){
                            //seleccionamos el valor que coincide
                            product.selectedIndex=i;
                            producto7();
                        }
                    }
                    $('#cantidadProducto7').val(datos.productos[6].cantidad);
                    $('#product7').select2();
                    
                    if($('#sizes7').hasClass('hide') == true){
                        var targets_select = document.getElementById("targets_select7");
                        for(var i=0;i<targets_select.length;i++){
                            if(targets_select.options[i].text === datos.productos[6].size){
                                //seleccionamos el valor que coincide
                                targets_select.selectedIndex=i;
                            }
                        }
                        if(targets_select.length > 1){
                            precioObjetivo7();
                        }
                        if($('#adicional7').hasClass('hide') == false){
                            var adicionalSelect = document.getElementById("multiple7");
                            var nAdicional = datos.productos[6].adiciones.length;
                            for(var x = 0; x<adicionalSelect.length; x++){
                                for(var i = 0; i<nAdicional; i++){
                                    if(adicionalSelect.options[x].id == datos.productos[6].adiciones[i].id){
                                        $("#multiple7 option[id="+datos.productos[6].adiciones[i].id+"]").prop("selected",true);
                                    }
                                }
                            }
                            $('#multiple7').select2();
                            adicional7();
                        }
                    }else{
                        var size_select = document.getElementById("sizes_select7");
                        for(var i=0;i<size_select.length;i++){
                            if(size_select.options[i].text === datos.productos[6].size){
                                //seleccionamos el valor que coincide
                                size_select.selectedIndex=i;
                            }
                        }
                        if(size_select.length > 1){
                            precioSize7();
                        }
                        if($('#adicional7').hasClass('hide') == false){
                            var adicionalSelect = document.getElementById("multiple7");
                            var nAdicional = datos.productos[6].adiciones.length;
                            for(var x = 0; x<adicionalSelect.length; x++){
                                for(var i = 0; i<nAdicional; i++){
                                    if(adicionalSelect.options[x].id == datos.productos[6].adiciones[i].id){
                                        $("#multiple7 option[id="+datos.productos[6].adiciones[i].id+"]").prop("selected",true);
                                    }
                                }
                            }
                            $('#multiple7').select2();
                            adicional7();
                        }
                    }
                }else if(a == 7){
                    addProduct('nuevoPedido');
                    var product = document.getElementById("product8");
                    var productoo = datos.productos[7].id;
            
                    for(var i=1;i<product.length;i++){
                        if(product.options[i].value === productoo){
                            //seleccionamos el valor que coincide
                            product.selectedIndex=i;
                            producto8();
                        }
                    }
                    $('#cantidadProducto8').val(datos.productos[7].cantidad);
                    $('#product8').select2();

                    if($('#sizes8').hasClass('hide') == true){
                        var targets_select = document.getElementById("targets_select8");
                        for(var i=0;i<targets_select.length;i++){
                            if(targets_select.options[i].text === datos.productos[7].size){
                                //seleccionamos el valor que coincide
                                targets_select.selectedIndex=i;
                            }
                        }
                        if(targets_select.length > 1){
                            precioObjetivo8();
                        }
                        if($('#adicional8').hasClass('hide') == false){
                            var adicionalSelect = document.getElementById("multiple8");
                            var nAdicional = datos.productos[7].adiciones.length;
                            for(var x = 0; x<adicionalSelect.length; x++){
                                for(var i = 0; i<nAdicional; i++){
                                    if(adicionalSelect.options[x].id == datos.productos[7].adiciones[i].id){
                                        $("#multiple8 option[id="+datos.productos[7].adiciones[i].id+"]").prop("selected",true);
                                    }
                                }
                            }
                            $('#multiple8').select2();
                            adicional8();
                        }
                    }else{
                        var size_select = document.getElementById("sizes_select8");
                        for(var i=0;i<size_select.length;i++){
                            if(size_select.options[i].text === datos.productos[7].size){
                                //seleccionamos el valor que coincide
                                size_select.selectedIndex=i;
                            }
                        }
                        if(size_select.length > 1){
                            precioSize8();
                        }
                        if($('#adicional8').hasClass('hide') == false){
                            var adicionalSelect = document.getElementById("multiple8");
                            var nAdicional = datos.productos[7].adiciones.length;
                            for(var x = 0; x<adicionalSelect.length; x++){
                                for(var i = 0; i<nAdicional; i++){
                                    if(adicionalSelect.options[x].id == datos.productos[7].adiciones[i].id){
                                        $("#multiple8 option[id="+datos.productos[7].adiciones[i].id+"]").prop("selected",true);
                                    }
                                }
                            }
                            $('#multiple8').select2();
                            adicional8();
                        }
                    }
                }else if(a == 8){
                    addProduct('nuevoPedido');
                    var product = document.getElementById("product9");
                    var productoo = datos.productos[8].id;
            
                    for(var i=1;i<product.length;i++){
                        if(product.options[i].value === productoo){
                            //seleccionamos el valor que coincide
                            product.selectedIndex=i;
                            producto9();
                        }
                    }
                    $('#cantidadProducto9').val(datos.productos[8].cantidad);
                    $('#product9').select2();
                    
                    if($('#sizes9').hasClass('hide') == true){
                        var targets_select = document.getElementById("targets_select9");
                        for(var i=0;i<targets_select.length;i++){
                            if(targets_select.options[i].text === datos.productos[8].size){
                                //seleccionamos el valor que coincide
                                targets_select.selectedIndex=i;
                            }
                        }
                        if(targets_select.length > 1){
                            precioObjetivo9();
                        }
                        if($('#adicional9').hasClass('hide') == false){
                            var adicionalSelect = document.getElementById("multiple9");
                            var nAdicional = datos.productos[8].adiciones.length;
                            for(var x = 0; x<adicionalSelect.length; x++){
                                for(var i = 0; i<nAdicional; i++){
                                    if(adicionalSelect.options[x].id == datos.productos[8].adiciones[i].id){
                                        $("#multiple9 option[id="+datos.productos[8].adiciones[i].id+"]").prop("selected",true);
                                    }
                                }
                            }
                            $('#multiple9').select2();
                            adicional9();
                        }
                    }else{
                        var size_select = document.getElementById("sizes_select9");
                        for(var i=0;i<size_select.length;i++){
                            if(size_select.options[i].text === datos.productos[8].size){
                                //seleccionamos el valor que coincide
                                size_select.selectedIndex=i;
                            }
                        }
                        if(size_select.length > 1){
                            precioSize9();
                        }
                        if($('#adicional9').hasClass('hide') == false){
                            var adicionalSelect = document.getElementById("multiple9");
                            var nAdicional = datos.productos[8].adiciones.length;
                            for(var x = 0; x<adicionalSelect.length; x++){
                                for(var i = 0; i<nAdicional; i++){
                                    if(adicionalSelect.options[x].id == datos.productos[8].adiciones[i].id){
                                        $("#multiple9 option[id="+datos.productos[8].adiciones[i].id+"]").prop("selected",true);
                                    }
                                }
                            }
                            $('#multiple9').select2();
                            adicional9();
                        }
                    }
                }else if(a == 9){
                    addProduct('nuevoPedido');
                    var product = document.getElementById("product10");
                    var productoo = datos.productos[9].id;
            
                    for(var i=1;i<product.length;i++){
                        if(product.options[i].value === productoo){
                            //seleccionamos el valor que coincide
                            product.selectedIndex=i;
                            producto10();
                        }
                    }
                    $('#cantidadProducto10').val(datos.productos[9].cantidad);
                    $('#product10').select2();
                    
                    if($('#sizes10').hasClass('hide') == true){
                        var targets_select = document.getElementById("targets_select10");
                        for(var i=0;i<targets_select.length;i++){
                            if(targets_select.options[i].text === datos.productos[9].size){
                                //seleccionamos el valor que coincide
                                targets_select.selectedIndex=i;
                            }
                        }
                        if(targets_select.length > 1){
                            precioObjetivo10();
                        }
                        if($('#adicional10').hasClass('hide') == false){
                            var adicionalSelect = document.getElementById("multiple10");
                            var nAdicional = datos.productos[9].adiciones.length;
                            for(var x = 0; x<adicionalSelect.length; x++){
                                for(var i = 0; i<nAdicional; i++){
                                    if(adicionalSelect.options[x].id == datos.productos[9].adiciones[i].id){
                                        $("#multiple10 option[id="+datos.productos[9].adiciones[i].id+"]").prop("selected",true);
                                    }
                                }
                            }
                            $('#multiple10').select2();
                            adicional10();
                        }
                    }else{
                        var size_select = document.getElementById("sizes_select10");
                        for(var i=0;i<size_select.length;i++){
                            if(size_select.options[i].text === datos.productos[9].size){
                                //seleccionamos el valor que coincide
                                size_select.selectedIndex=i;
                            }
                        }
                        if(size_select.length > 1){
                            precioSize10();
                        }
                        if($('#adicional10').hasClass('hide') == false){
                            var adicionalSelect = document.getElementById("multiple10");
                            var nAdicional = datos.productos[9].adiciones.length;
                            for(var x = 0; x<adicionalSelect.length; x++){
                                for(var i = 0; i<nAdicional; i++){
                                    if(adicionalSelect.options[x].id == datos.productos[9].adiciones[i].id){
                                        $("#multiple10 option[id="+datos.productos[9].adiciones[i].id+"]").prop("selected",true);
                                    }
                                }
                            }
                            $('#multiple10').select2();
                            adicional10();
                        }
                    }
                }           
            }
            //DESCUENTO
            var descuento = parseInt(datos.descuento);
            if(descuento > 0){
                var radios = document.getElementsByName('radios');
                radios[1].checked = true;
                var descuento = document.getElementById("descuento").value = datos.descuento;
            }
        }
    });
    $('#oculto').find('input,button,textarea,select').attr('disabled', 'disabled');
    $('#oculto').find('a').addClass("hide");
    var addNuevaDireccion = document.getElementById("addNuevaDireccion").classList.remove("hide");
    $('#direccion_select').removeAttr('disabled');
    $('#forma-pago').removeAttr('disabled');
    $('#horaEntrega').removeAttr('disabled');
    $('#observacion').removeAttr('disabled');
    var load = document.getElementById("contenedor_carga");
        load.style.visibility = 'hidden';
        load.style.opacity = '0';

    setTimeout("$('#carga').remove()",3000);
}
</script>
<?php
    include('includes/pedidos/funcionPedidos.php');
    include('includes/footer/footer.php');

    }else{
        header("Location: index");
    }
?>