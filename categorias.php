<?php
session_start();

	if(isset($_SESSION["usuario"])){

	require 'includes/conexion.php';
	require 'includes/fun.php';

	include('includes/header/header.php');
	include('includes/menu/menu.php');

	//Consulto las categorias
    $sql = "SELECT * FROM categories ORDER BY id ASC";
    $resultado = pg_query($sql);
?>
<div class="content">
	<section class="content-header">
        <div class="row">
            <h1>
                Categorías
                <small>
                    <a data-toggle="collapse" data-target="#nuevaCategoria" class="btn btn-info">Nueva categoría</a>
                </small>
            </h1>
        </div>
        <section class="content-header collapse" id="nuevaCategoria">
	        <div class="row"><h1>Nueva categoría</h1></div>
	        <div class="box-body" id="box-body">
	            <div class="row box box-body">
	                <div class="col-md-12">
	                    <div class="box-primary">
	                        <form autocomplete="off">
	                            <div class="row">
	                                <div class="col-md-9 form-group">
	                                    <label>Nombre</label>
	                                    <input type="text" id="nuevaCategoriaInput" class="form-control" autofocus />
	                                </div>
	                            </div>
	                            <div class="row">
	                            	<div class="col-md-3">
	                                    <label>¿Activo? <input type="checkbox" id="activo" checked /></label>
	                                </div>
	                            </div>
	                            <div class="row box-footer">
	                                <div class="col-md-12">
	                                    <input type="submit" value="Guardar" onClick="guardarCategoria()" class="btn btn-primary" />
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
			            	<th class="hide">ID</th>
			                <th>Nombre</th>
			                <th>¿Activa?</th>
			                <th></th>
			            </tr>
			        </thead>

			        <tbody>
			        	<?php while ($line = pg_fetch_array($resultado, null, PGSQL_ASSOC)) { 
			        		$activo = $line['active'];

			        		$habilitado = "<label class=\"switch\"><input id=".$line['id']." type=\"checkbox\" onClick=\"habilitado(id)\" checked><span class=\"slider round\"></span></label>";
			        		$inhabilitado = "<label class=\"switch\"><input id=".$line['id']." type=\"checkbox\" onClick=\"habilitado(id)\"><span class=\"slider round\"></span></label>";

			        		if($activo == 't'){
			        			$active = $habilitado;
			        		}else{
			        			$active = $inhabilitado;
			        		}
			        	?>
			        		<tr>
			        			<td class="hide"><?php echo $line['id']; ?></td>
			        			<td><?php echo ucfirst(strtolower($line['name'])); ?></td>
			        			<td><?php echo $active; ?></td>
			        			<td class="eraser">
                        			<a href="#" data-href="includes/productos/eliminar_categoria.php?id=<?php echo $line['id'];?>" data-toggle="modal" data-target="#confirm-delete" title="Eliminar Categoría">
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
					<h4 class="modal-title" id="myModalLabel">Eliminar Categoría</h4>
				</div>
				<div class="modal-body">
					<p>¿Deseas eliminar esta categoría?</p>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<a class="btn btn-danger btn-ok">Eliminar</a>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
    //Eliminar
    $('#confirm-delete').on('show.bs.modal', function(e) {
     $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));

     $('.debug-url').html('Delete URL: <strong>' + $(this).find(
         '.btn-ok').attr('href') + '</strong>');
    });
    //ACTUALIZAR ESTADO DE LA CATEGORIA
    function habilitado(id){
	var elemento = document.getElementById(id).checked;

    var datos = {
    	checked: elemento,
    	id: id
    }
    var datos = JSON.stringify(datos);

    var url = "includes/productos/actualizarCategoria.php";
    $.ajax({
		type: "POST",
        url: url,
        data: {data : datos},
        success: function(data){
        }
    });
	}
	function guardarCategoria(){
		event.preventDefault();
		var nombre = document.getElementById("nuevaCategoriaInput").value;
		if(!isNaN(nombre)){
            if ( document.getElementById('alerta') ){
                $('#alerta').remove();
            }
            var alerta = document.createElement("div");
            alerta.className = "alert alert-danger";
            alerta.id = "alerta";

            var node = document.createTextNode("Por favor escriba un nombre.");
            alerta.appendChild(node);

            var padre = document.getElementById("nuevaCategoria");
            var hijo = document.getElementById("box-body");

            padre.insertBefore(alerta, hijo);

            window.scroll({top: 0, behavior: 'smooth'});
            var selectCliente = document.getElementById("nuevaCategoriaInput").focus();
        }else{
        	if ( document.getElementById('alerta') ){
                $('#alerta').remove();
            }

            var activo = document.getElementById("activo").checked;
            
            var datos = {
		    	checked: activo,
		    	nombre: nombre
		    }
		    var datos = JSON.stringify(datos);

		    var url = "includes/productos/addCategoria.php";
		    $.ajax({
				type: "POST",
		        url: url,
		        data: {data : datos},
		        success: function(data){
		        	if(data == '1'){
                        window.location.href ="/categorias";
                    }
		        }
		    });
        }
	}
</script>
<?php
include('includes/footer/footer.php');
    } else { 
        header("Location: index"); 
    } 
?>