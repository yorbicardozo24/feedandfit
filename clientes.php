<?php
session_start();
	if(isset($_SESSION["usuario"])){

	require 'includes/conexion.php';
	require 'includes/fun.php';

	include('includes/header/header.php');
	include('includes/menu/menu.php');

	$sql = "SELECT
			customers.id,
			customers.name,
			customers.last_name,
			customers.email,
			customers.phone,
			customers.observations,
			customers.delivery_time,
            customers.selected_time
			FROM customers 
			ORDER BY name ASC";
    $resultado = pg_query($sql);

    $que = "SELECT * FROM delivery_zones ORDER BY name ASC";
    $resul = pg_query($que);
?>
<div class="content">
    <section class="content-header">
        <div class="row">
            <h1>
                Clientes
                <small>
                    <a href="nuevoCliente" class="btn btn-primary">Nuevo Cliente</a>
                </small>
            </h1>
        </div>
        <div class="row">
            <div class="box" style="padding-top: 15px; padding-bottom: 15px;">
            <table id="example" class="table table-striped table-bordered" style="width:100%; font-size: 12px;">
        		<thead>
		            <tr>
		            	<th>ID</th>
		                <th>Cliente</th>
		                <th>Correo</th>
		                <th>Teléfono</th>
		                <th>Observaciones</th>
		                <th>Hora Entrega</th>
		                <th></th>
		                <th></th>
		                <th></th>
		            </tr>
        		</thead>
        		<tbody>
        			<?php
        			while ($line = pg_fetch_array($resultado, null, PGSQL_ASSOC)) {
        			?>
        			<tr>
        				<td><?php echo $line['id']; ?></td>
        				<td><?php echo $line['name'] . " " . $line['last_name']; ?></td>
        				<td><?php echo $line['email']; ?></td>
        				<td><?php echo $line['phone']; ?></td>
        				<td><?php echo $line['observations']; ?></td>
                        <?php if($line['selected_time'] == 't'){ ?>
        				<td><?php echo date('h:i A', strtotime($line['delivery_time'])); ?></td>
                        <?php }else{ ?>
                        <td></td>
                        <?php } ?>
        				<td class="see">
                        	<a href="#" id="<?php echo $line['id'];?>" data-href="#" data-toggle="modal" data-target="#ver-cliente" title="Ver Cliente">
                            	<i class="far fa-eye"></i>
                        	</a>
                    	</td>
        				<td class="edit">
                        	<a href="#" id="<?php echo $line['id'];?>" data-href="#" data-toggle="modal" data-target="#editarCliente" title="Editar Cliente">
                            	<i class="fa fa-edit"></i>
                        	</a>
                    	</td>
                    	<td class="eraser">
                        	<a href="#" data-href="includes/clientes/eliminarCliente.php?id=<?php echo $line['id'];?>" data-toggle="modal" data-target="#confirm-delete" title="Eliminar Cliente">
                            	<i class="fa fa-eraser"></i>
                        	</a>
                    	</td>
        			</tr>
        			<?php } ?>
        		</tbody>
    		</table>
			</div>
		</div>
	</section>
    <!-- MODAL ELIMINAR-->
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Eliminar Cliente</h4>
                </div>
                <div class="modal-body">
                    <p>¿Deseas eliminar este cliente?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-danger btn-ok">Eliminar</a>
                </div>
            </div>
        </div>
    </div>
        <!-- MODAL VER PEDIDO -->
    <div class="modal fade" id="ver-cliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="row">
                    <div class="col-xs-12">
                        <h2>
                            <span id="idCliente"></span>
                            <br/>
                            <small class="pull-right" id="fecha"></small>
                            <small id="nombreCliente"></small>
                        </h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4"><p id="observacionCliente"></p></div>
                    <div class="col-sm-4"><span id="identificacion"></span></div>
                    <div class="col-sm-4">
                        <p id="correoCliente"></p>
                        <p id="telefonoCliente"></p>
                        <p id="hora"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Dirección</th>
                                    <th>Zona</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="contentDireccion">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
        <!-- VENTANA MODAL EDITAR PRODUCTO -->
    <div class="modal fade" id="editarCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <section class="content-header" id="content-header">
        <div class="row" id="nueva-orden"><h1>Editar Cliente</h1></div>
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
                                <input id="horaCliente" type="time" class="form-control" placeholder="Hora" />
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
                                <div id="adicionHijo"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt20">
                                    <a onclick="addRow()" style="cursor: pointer">Añadir otra dirección</a>
                                </div>
                            </div>
                            <div class="row box-footer">
                                <div class="col-md-12">
                                    <a onclick="guardarCliente(id)" class="btn btn-primary btn-ok">Guardar</a>
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
</div>
<script type="text/javascript">
$('#confirm-delete').on('show.bs.modal', function(e) {
     $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));

     $('.debug-url').html('Delete URL: <strong>' + $(this).find(
         '.btn-ok').attr('href') + '</strong>');
});
//Ver cliente
$('#ver-cliente').on('show.bs.modal', function(e) {
    var see = e.relatedTarget.id;
    var url = "includes/clientes/verClientes.php";
    
    $.ajax({
        type: "POST",
        url: url,
        data: {data : see,},
        success: function(data){
            var datos = JSON.parse(data);
            $('#idCliente').html("# " + datos.id);
            $('#nombreCliente').html(datos.nombreCliente + " " + datos.apellidoCliente);
            $('#fecha').html(datos.fecha);
            $('#observacionCliente').html(datos.observacion);
            $('#identificacion').html("<b>NIT/CC: </b>" + datos.numero);
            $('#correoCliente').html("<b>Correo: </b>" + datos.email);
            $('#telefonoCliente').html("<b>Teléfono: </b>" + datos.telefono);
            $('#hora').html("<b>Hora: </b>" + datos.hora);
            var nDirecciones = datos.direcciones.length;
            $('#contentDireccion').html('');
            for(var i = 0; i < nDirecciones; i++){
                $('#contentDireccion').append('<tr><td></td><td>'+ datos.direcciones[i].direccion + '</td><td>'+ datos.direcciones[i].zona +'</td><td></td></tr>');
            }
        }
    });
});
//EDITAR CLIENTE
$('#editarCliente').on('show.bs.modal', function(e) {
    var see = e.relatedTarget.id;
    var url = "includes/clientes/verClientes.php";
    $(this).find('.btn-ok').attr('id', see);

    $.ajax({
        type: "POST",
        url: url,
        data: {data : see,},
        success: function(data){
            var datos = JSON.parse(data);
            
            $('#nombres').val(datos.nombreCliente);
            $('#apellidos').val(datos.apellidoCliente);
            $('#email').val(datos.email);
            $('#phone').val(datos.telefono);
            $('#documento').val(datos.numero);
            if(!datos.tipo){
                $('#tipo').val(0);
            }else{
                $('#tipo').val(datos.tipo);
            }
            $('#horaCliente').val(datos.hour);
            $('#observaciones').val(datos.observacion);

            $('#adicionHijo').html('');
            var nDirecciones = datos.direcciones.length;

            for(i = 0; i<nDirecciones; i++){
                addDireccion(datos.direcciones[i].direccion, datos.direcciones[i].idZona, i);
            }
        }
    });
});
function addRow() {
    var filas = document.getElementById("adicionHijo").childElementCount;
    if(filas == 0){
    var url = "includes/clientes/buscarZonas.php";
    $.ajax({
      type: "POST",
      url: url,
      data: {data: url},
      success: function(data){
        var datos = JSON.parse(data);
        var textoHtml = `
        <div class="row" id="0">
            <div class="col-md-4">
                <label for="adicionalNombre0">Dirección</label>
                <input id="adicionalNombre0" type="text" class="form-control" placeholder="Dirección"/>
            </div>
            <div class="col-md-4">
                <label for="adicionalZona0" style="margin-right: 53px;">Zona Domiciliaría</label>
                <select id="adicionalZona0" class="form-control">
                  <option value="Escoge">Escoge</option>
                `;
        var n = datos.length;
        var opciones = "";
        for(x = 0; x<n; x++){
          opciones = opciones + "<option value="+ datos[x].id +">"+ datos[x].nombre +"</option>"
        }
        var footer = `
            </select>
            </div>
            <div class="col-md-2">
                <a class="borrarProducto" id="borrarProducto" onClick="eliminarFila(0)"><i class="fas fa-trash-alt"></i></a>
                </div>
        </div>
    `;
    var content = textoHtml + opciones + footer;
    $('#adicionHijo').append(content);
    $('#adicionalZona0').select2();
    }
    });

    }else{
        var fila = parseInt(document.getElementById("adicionHijo").lastElementChild.id);
        var idFila = fila + 1;
        addDireccion('', 'Escoge', idFila);
    }
}
function addDireccion(direccionCliente, idZona ,i) {
  var url = "includes/clientes/buscarZonas.php";
    $.ajax({
      type: "POST",
      url: url,
      data: {data: url},
      success: function(data){
        var datos = JSON.parse(data);
        var textoHtml = `
        <div class="row" id="`+ i +`">
            <div class="col-md-4">
                <label for="adicionalNombre`+i+`">Dirección</label>
                <input id="adicionalNombre`+i+`" type="text" class="form-control" placeholder="Dirección" value="`+ direccionCliente +`"/>
            </div>
            <div class="col-md-4">
                <label for="adicionalZona`+i+`" style="margin-right: 53px;">Zona Domiciliaría</label>
                <select id="adicionalZona`+i+`" class="form-control">
                  <option value="Escoge">Escoge</option>
                `;
        var n = datos.length;
        var opciones = "";
        for(x = 0; x<n; x++){
          opciones = opciones + "<option value="+ datos[x].id +">"+ datos[x].nombre +"</option>"
        }
        var footer = `
            </select>
            </div>
            <div class="col-md-2">
                <a class="borrarProducto" id="borrarProducto" onClick="eliminarFila(`+i+`)"><i class="fas fa-trash-alt"></i></a>
                </div>
        </div>
    `;
    var content = textoHtml + opciones + footer;
    $('#adicionHijo').append(content);
    var nombreZonaSelect = "#adicionalZona" + i;

    $(nombreZonaSelect).val(idZona);
    $(nombreZonaSelect).select2();
      }
    });
}
function eliminarFila(id){
    $('#adicionHijo #' + id).remove();
}
function guardarCliente(id){
  var nombres = document.getElementById("nombres").value;
  var apellidos = document.getElementById("apellidos").value;
  var tipo = document.getElementById("tipo").value;
  var documento = document.getElementById("documento").value;
  var email = document.getElementById("email").value;
  var phone = document.getElementById("phone").value;
  var hora = document.getElementById("horaCliente").value;
  var observaciones = document.getElementById("observaciones").value;
  
  if (!nombres){ 
    alertify.error("Por favor escriba el nombre del cliente.");
    window.scroll({top: 0, behavior: 'smooth'});
    document.getElementById("nombres").focus();
  }else{
    if(!apellidos){
      alertify.error("Por favor escriba el apellido del cliente.");
      window.scroll({top: 0, behavior: 'smooth'});
      document.getElementById("apellidos").focus();
    }else{
        var filas = document.getElementById("adicionHijo").childElementCount;
        if(filas == 0){
            alertify.error("Por favor escoja una dirección.");
        }else{
            var fila = document.getElementById("adicionHijo");
            var adicionalesCount = [];
            var direccionAdicional = "";
            var zona = 0;
            if(filas == 1){
                direccionAdicional = fila.children[0].children[0].children[1].value;
                zona = fila.children[0].children[1].children[1].value;
                if(!direccionAdicional || zona == 'Escoge'){
                    adicionalesCount = 'false';
                }else{
                    adicionalesCount.push({
                        direccionAdicional: direccionAdicional,
                        zona: zona
                    });
                }
            }else{
                for(i=0; i<filas; i++){
                    direccionAdicional = fila.children[i].children[0].children[1].value;
                    zona = fila.children[i].children[1].children[1].value;
              
                    adicionalesCount.push({
                        direccionAdicional: direccionAdicional,
                        zona: zona
                    });
          
                }
            }
        
        if (adicionalesCount == 'false'){
          alertify.error("Por favor escriba una dirección con la zona domiciliaría.");
        }else{
          var datos = {
            nombres: nombres,
            apellidos: apellidos,
            tipo: tipo,
            documento: documento,
            email: email,
            phone: phone,
            hora: hora,
            observaciones: observaciones,
            adicionalesCount: adicionalesCount
          }
          datos = JSON.stringify(datos);
          var url = "includes/clientes/addCliente.php";
          $.ajax({
            type: "POST",
            url: url,
            data: {data : datos, from: 'edit', id: id},
            success: function(data){
                if(data == 1){
                    window.location.href ="clientes";
                }
            }
          });
        }
        }
    }
  }
}
</script>
<?php
include('includes/footer/footer.php');
    } else { 
        header("Location: index"); 
    } 
?> 