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
    <section class="content-header" id="content-header">
        <div class="row" id="nueva-orden"><h1>Nueva Orden de Pedido</h1></div>
        <div class="box-body" id="box-body">
            <div class="row box box-body">
                <div class="col-md-12">
                    <div class="box-primary">
                        <form autocomplete="off">
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
                                <div class="col-md-3 hide">
                                    <div class="form-group">
                                        <label>Forma de pago</label>
                                        <select class="form-control" id="forma-pago">
                                            <option value="2">Efectivo</option>
                                            <option value="1">Consignación</option>
                                            <option value="3">iFood</option>
                                            <option value="4">UberEats</option>
                                            <option selected="selected" value="5">Sin Cobro</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-7 form-group">
                                    <label>Direcciones</label>
                                    <a data-toggle="modal" data-target="#addDireccion" style="cursor: pointer;">Nueva Dirección</a>
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
                                            <label style="margin-right: 150px;">Adicional</label>
                                            <select onchange="adicional()" class="form-control" multiple="multiple" id="multiple" style="width:170px">
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Cantidad</label>
                                            <input id="cantidadProducto" class="form-control only-number" type="number" onChange="adicional()" value="1" min="1" />
                                        </div>
                                        <div class="col-md-2 precios hide">
                                            <label>Precio</label>
                                            <div id="product-price" style="padding-top: 5px;">$0</div>
                                            <div id="product-price_second" style="padding-top: 5px;" class="hide"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt20">
                                    <a style="cursor: pointer" onClick="addProduct('nuevaOrden')">Añadir otro producto</a>
                                </div>
                            </div>
                            <div class="row mt20 hide">
                                <div class="col-md-2">
                                    <label>Descuento</label>
                                    <br />
                                    <span style="margin-top: -5px; padding-right: 30px;">
                                        <label class="form-check-label" for="porcentaje">%</label>
                                        <input class="form-check-input" type="radio" name="radios" id="porcentaje" value="porcentaje" checked>
                                    </span>
                                    <span style="margin-top: -5px;">
                                        <label class="form-check-label" for="ve">$</label>
                                        <input class="form-check-input" type="radio" name="radios" id="ve" value="ve">
                                    </span>
                                    <input class="form-control only-number" type="text" onkeyup="format(this)" id="descuento" />
                                </div>
                            </div>
                            <div class="row mt20 hide">
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
                            <div class="row box-footer">
                                <div class="col-md-12">
                                    <input type="submit" value="Guardar" onClick="guardarPedido()" class="btn btn-primary" />
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
                                    <a onclick="guardarCliente('nuevaOrden')" class="btn btn-primary">Guardar</a>
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
        $('#select').select2();
        $('#product').select2();
        $('#zona').select2();
        $('#newZona').select2();
        $('#clienteNewZona').select2();
        $('#multiple').select2();
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
                                window.location.href ="../nueva_orden";
                            }
                        }
                    });
                }
            }
        }
    }

    //Para guardar info del pedido
    function guardarPedido(){
        event.preventDefault();
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
                    if(hora < 11){
                        hora = 11;
                    }
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
                        adicional: adicionalArray
                    });
                }
                
                datos.push({
                    cliente: cliente,
                    fecha: fecha_actual,
                    horaEntrega: horaEntrega,
                    formaPago: formaPago,
                    direccionCliente: direccionCliente,
                    observacionCliente: observacion,
                    productos: productos
                });
                datos = JSON.stringify(datos);
                var url = "includes/pedidos/crear_ordenPedido.php";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {data: datos},
                    success: function(data){
                        if(data == '1'){
                            window.location.href ="../pedidos";
                        }
                    }
                });
            }
        }
    }

</script>
<?php
include('includes/clientes/nuevoClienteJs.php');
include('includes/pedidos/funcionPedidos.php');
include('includes/footer/footer.php');
    } else {
        header("Location: index");
    }
?>
