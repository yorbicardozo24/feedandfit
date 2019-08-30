<?php
session_start();
	if(isset($_SESSION["usuario"])){

	require 'includes/conexion.php';
	require 'includes/fun.php';

	include('includes/header/header.php');
	include('includes/menu/menu.php');

	$sql = "SELECT * FROM delivery_zones ORDER BY id ASC";
    $resultado = pg_query($sql);
?>
<div class="content">
    <section class="content-header">
        <div class="row">
            <h1>
                Zonas Domiciliarias
                <small>
                    <a data-toggle="collapse" data-target="#nuevaZona" class="btn btn-primary">Nueva Zona Domiciliaria</a>
                </small>
            </h1>
        </div>
        <section class="content-header collapse" id="nuevaZona">
            <div class="row"><h1>Nueva zona</h1></div>
            <div class="box-body" id="box-body">
                <div class="row box box-body">
                    <div class="col-md-12">
                        <div class="box-primary">
                            <form autocomplete="off">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="nuevaZonaInput">Nombre</label>
                                        <input type="text" id="nuevaZonaInput" class="form-control" autofocus />
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="nuevaZonaPrice">Precio</label>
                                        <input type="text" onkeyup="format(this)" id="nuevaZonaPrice" class="form-control"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>¿Activo? <input type="checkbox" id="activo" checked /></label>
                                    </div>
                                </div>
                                <div class="row box-footer">
                                    <div class="col-md-12">
                                        <input type="submit" value="Guardar" onClick="guardarZona()" class="btn btn-primary" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="row">
            <div class="box" style="padding-top: 15px; padding-bottom: 15px;">
            <table id="example" class="table table-striped table-bordered" style="width:100%; font-size: 12px;">
        		<thead>
		            <tr>
		            	<th>ID</th>
		                <th>Nombre</th>
		                <th>Precio</th>
		                <th>¿Activa?</th>
		                <th></th>
		                <th></th>
		            </tr>
        		</thead>
        		<tbody>
        			<?php
        			while ($line = pg_fetch_array($resultado, null, PGSQL_ASSOC)) {
        				$activo = $line['active'];
        				$precio = number_format($line['price'], 0, '', '.');
        			?>
        			<tr>
        				<td><?php echo $line['id']; ?></td>
        				<td><?php echo $line['name']; ?></td>
        				<td><?php echo ("$ $precio"); ?></td>
        				<?php if ($activo == 't') { ?>
        				<td>
        					<label class="switch">
							<input type="checkbox" id="<?php echo $line['id']; ?>" onClick="habilitado(id)" checked>
								<span class="slider round"></span>
							</label>
						</td>
        				<?php }else{ ?>
        				<td>
        					<label class="switch">
							<input type="checkbox" id="<?php echo $line['id']; ?>" onClick="habilitado(id)">
								<span class="slider round"></span>
							</label>
						</td>
        				<?php } ?>
        				<td class="edit">
                        	<a href="#" id="<?php echo $line['id'];?>" data-href="#" data-toggle="modal" data-target="#editarZona" title="Editar Zona">
                            	<i class="fa fa-edit"></i>
                        	</a>
                    	</td>
                    	<td class="eraser">
                        	<a href="#" data-href="includes/zonas/eliminarZona.php?id=<?php echo $line['id'];?>" data-toggle="modal" data-target="#confirm-delete" title="Eliminar Zona">
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
                    <h4 class="modal-title" id="myModalLabel">Eliminar Zona</h4>
                </div>
                <div class="modal-body">
                    <p>¿Deseas eliminar esta zona?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-danger btn-ok">Eliminar</a>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL EDITAR-->
    <div class="modal fade" id="editarZona" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <section class="content-header" id="content-header">
        <div class="row" id="nueva-orden"><h1>Editar Zona</h1></div>
        <div class="box-body" id="box-body">
            <div class="row box" style="padding-top: 15px;">
                <div class="col-md-12">
                    <div class="box-primary">
                        <form autocomplete="off">
                            <div class="row">
                                <!-- <div class="col-md-3"></div> -->
                                <div class="col-md-6 form-group">
                                    <label for="nombreZonaEdit">Nombre</label>
                                    <input id="nombreZonaEdit" class="form-control" type="text"/>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="precioZonaEdit">Precio</label>
                                    <input id="precioZonaEdit" onkeyup="format(this)" class="form-control" type="text"/>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>¿Activo? <input type="checkbox" id="activoZonaEdit"/></label>
                                </div>
                            </div>
                            <div class="row box-footer">
                                <div class="col-md-12">
                                    <a onclick="editarZona(id)" class="btn btn-primary btn-ok">Guardar</a>
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
//Eliminar
    $('#confirm-delete').on('show.bs.modal', function(e) {
     $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));

     $('.debug-url').html('Delete URL: <strong>' + $(this).find(
         '.btn-ok').attr('href') + '</strong>');
    });
//EDITAR ZONA
$('#editarZona').on('show.bs.modal', function(e) {
    var see = e.relatedTarget.id;
    var url = "includes/zonas/editarZona.php";
    $(this).find('.btn-ok').attr('id', see);

    $.ajax({
        type: "POST",
        url: url,
        data: {data : see, from: 'ver'},
        success: function(data){
            var datos = JSON.parse(data);
            var precio = datos.precio;
            precio = precio.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
            precio = precio.split('').reverse().join('').replace(/^[\.]/,'');

            $('#nombreZonaEdit').val(datos.nombre);
            $('#precioZonaEdit').val(precio);
            if(datos.activo == 't'){
                $('#activoZonaEdit').prop("checked", true);
            }else{
                $('#activoZonaEdit').prop("checked", false);
            }
            
        }
    });
});
function editarZona(id){
    event.preventDefault();
    var nombre = document.getElementById("nombreZonaEdit").value;
    var precio = parseInt(document.getElementById("precioZonaEdit").value.replace(/\./g,''));
    var activo = document.getElementById("activoZonaEdit").checked;
    if(activo){
        activo = 'true';
    }else{
        activo = 'false';
    }
    if(!isNaN(nombre)){
        alertify.error("Por favor escriba un nombre");
    }else{
        if(!precio != 0){
            alertify.error("Por favor escriba un precio");
        }else{
            var datos = {
                checked: activo,
                nombre: nombre,
                precio: precio,
                id: id
            }
            var datos = JSON.stringify(datos);

            var url = "includes/zonas/editarZona.php";
            $.ajax({
            type: "POST",
            url: url,
            data: {data : datos, from: 'editar'},
            success: function(data){
                if(data == '1'){
                    window.location.href ="/zonas";
                }
            }
            });
        }
    }
}
function format(input){
    var num = input.value.replace(/\./g,'');

    if(!isNaN(num)){
        num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        num = num.split('').reverse().join('').replace(/^[\.]/,'');
        input.value = num;
    }else{
        alert('Solo se permiten numeros');
        input.value = input.value.replace(/[^\d\.]*/g,'');
    }
   
}
function guardarZona(){
    event.preventDefault();
    var nombre = document.getElementById("nuevaZonaInput").value;
    var precio = parseInt(document.getElementById("nuevaZonaPrice").value.replace(/\./g,''));
    var activo = document.getElementById("activo").checked;
    if(activo){
        activo = 'true';
    }else{
        activo = 'false';
    }
    if(!isNaN(nombre)){
        alertify.error("Por favor escriba un nombre");
    }else{
        if(!precio != 0){
            alertify.error("Por favor escriba un precio");
        }else{
            var datos = {
                checked: activo,
                nombre: nombre,
                precio: precio
            }
            var datos = JSON.stringify(datos);

            var url = "includes/zonas/addZona.php";
            $.ajax({
            type: "POST",
            url: url,
            data: {data : datos},
            success: function(data){
                if(data == '1'){
                    window.location.href ="/zonas";
                }
            }
            });
        }
    }
}

function habilitado(id){
    var elemento = document.getElementById(id).checked;

    var datos = {
        checked: elemento,
        id: id
    }
    var datos = JSON.stringify(datos);

    var url = "includes/zonas/actualizarZona.php";
    $.ajax({
        type: "POST",
        url: url,
        data: {data : datos},
        success: function(data){
        }
    });
}
</script>
<?php
include('includes/footer/footer.php');
    } else { 
        header("Location: index"); 
    } 
?>