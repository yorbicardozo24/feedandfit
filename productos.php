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
	//Consulto los pedidos del dia donde no hayan anulado el pedido
    $sql = "SELECT * FROM categories WHERE active = true ORDER BY id ASC";
    $resultado = pg_query($sql);
    //NUMERO DE FILAS DE LA CONSULTA
    $rows = pg_num_rows($resultado);

?>
<div class="content">
	<section class="content-header">
        <div class="row">
            <h1>
                Productos
                <small>
                    <a href="nuevoProducto" class="btn btn-info">Nuevo producto</a>
                    <a href="categorias" class="btn btn-primary">Categorías</a>
                </small>
            </h1>
            <input type="hidden" id="rows" value="<?php echo $rows; ?>">
        </div>
        
		<div class="row">
			<ul class="nav nav-tabs">
				<?php
                //Ciclo para mostrar las categorías
                $x = 0;
                while ($line = pg_fetch_array($resultado, null, PGSQL_ASSOC)) {
                	$categoria = ucfirst(strtolower($line['name']));
                	$id_categoria = $line['id'];
                ?>
                <?php if($id_categoria != 1){ ?>
                <li>
                	<a data-toggle="tab" href="<?php echo ("#menu" . $id_categoria); ?>"><?php echo $categoria; ?></a>
                </li>
                <?php }else{ ?>
				<li class="active">
					<a data-toggle="tab" href="<?php echo ("#menu1"); ?>"><?php echo $categoria; ?></a>
				</li>
                <?php } $x = $x + 1; ?>
				<?php } ?>
  			</ul>
  				<!-- AQUI ES DONDE COLOCO EL CONTENIDO CON JS -->
  				<div id="contenedor_carga" style="height: 50%; background-color: transparent;">
    				<div id="carga"></div>
				</div>
			  	<div class="tab-content" id="listadoCategorias">
			  	</div>
		</div>
    </section>
    <!-- MODAL EDITAR -->
    <div class="modal fade" id="editProducto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 1000px;">
            <div class="modal-content">
                <section class="content-header" id="content-header">
        <div class="row"><h1>Editar Producto</h1></div>
        <div class="box-body" id="box-body">
            <div class="row box box-body" style="padding-bottom: 15px;">
                <div class="col-md-12">
                    <div class="box-primary">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Nombre</label>
                                <input type="text" id="nombre" class="form-control" placeholder="Nombre">
                            </div>
                            <div class="col-md-6">
                                <label>Categoría</label>
                                <select class="form-control" id="categoria">
                                    <option>Escoge</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt20">
                            <div class="col-md-6">
                                <label>Descripción</label>
                                <textarea placeholder="Descripción del producto" id="descripcionEdit" class="form-control"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label>Foto</label>
                                <input type="file" id="foto">
                            </div>
                        </div>
                        <div class="row mt20">
                            <div class="col-md-12 mt10">
                                <div class="col-md-4" style="display: flex; flex-direction: column; align-items: center;">
                                    <label>¿Habilitado?</label>
                                    <label class="switch">
                                        <input id="habilitado" type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="col-md-4" style="display: flex; flex-direction: column; align-items: center;">
                                    <label>¿Menú del día almuerzo?</label>
                                    <label class="switch">
                                        <input id="menuDiaAlmuerzo" type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="col-md-4" style="display: flex; flex-direction: column; align-items: center;">
                                    <label>¿Menú del día cena?</label>
                                    <label class="switch">
                                        <input id="menuDiaCena" type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt20">
                            <div class="row box-gray">
                                <div class="box-header">
                                    <div class="col-md-6" style="padding-top: 5px;">
                                        <h4>Simple</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pull-right">
                                            <label>&nbsp;</label>
                                            <label class="switch">
                                                <input id="simpleInput" onclick="tipo(id)" type="checkbox" data-toggle="collapse" data-target="#simple">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row content-header collapse mt20" id="simple">
                                <section>
                                    <div class="col-md-2">
                                        <label>Precio</label>
                                        <input class="form-control only-number" id="precioSimple" type="text" onkeyup="format(this)">
                                    </div>
                                </section>
                            </div>
                        </div>
                        <div class="row mt20">
                            <div class="row box-gray">
                                <div class="box-header">
                                    <div class="col-md-6" style="padding-top: 5px;">
                                        <h4>Objetivo</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pull-right">
                                            <label>&nbsp;</label>
                                            <label class="switch">
                                                <input id="objetivoInput" onclick="tipo(id)" type="checkbox" data-toggle="collapse" data-target="#objetivo">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row content-header collapse" id="objetivo">
                                <div class="box-body" style="">
                                    <div class="col-md-12 mb10">
                                        <div class="col-md-6">
                                            <label for="fP">Fuente de Proteína</label>
                                            <input class="form-control" id="fP" placeholder="Ingrese fuente de proteína" type="text">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="fC">Fuente de Carbohidrato</label>
                                            <input class="form-control" id="fC" placeholder="Ingrese fuente de carbohidrato" type="text">
                                        </div>
                                    </div>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>F. Prot.</th>
                                                <th>F. Carb.</th>
                                                <th>Ensalada</th>
                                                <th>Calorías</th>
                                                <th>Prot.</th>
                                                <th>Carb.</th>
                                                <th>Fat</th>
                                                <th>Precio</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>0 Carbs</td>
                                                <td><input id="carbsFp" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="carbsFc" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="carbsEnsalada" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="carbsCalorias" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="carbsProt" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="carbsCarb" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="carbsFat" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="carbsPrecio" class="form-control only-number" type="number" value="0" min="0"></td>
                                            </tr>
                                            <tr>
                                                <td>Quemar grasa</td>
                                                <td><input id="qGrasaFp" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="qGrasaFc" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="qGrasaEnsalada" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="qGrasaCalorias" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="qGrasaProt" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="qGrasaCarb" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="qGrasaFat" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="qGrasaPrecio" class="form-control only-number" type="number" value="0" min="0"></td>
                                            </tr>
                                            <tr>
                                                <td>Mantener</td>
                                                <td><input id="mantenerFp" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="mantenerFc" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="mantenerEnsalada" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="mantenerCalorias" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="mantenerProt" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="mantenerCarb" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="mantenerFat" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="mantenerPrecio" class="form-control only-number" type="number" value="0" min="0"></td>
                                            </tr>
                                            <tr>
                                                <td>L</td>
                                                <td><input id="lFp" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="lFc" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="lEnsalada" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="lCalorias" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="lProt" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="lCarb" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="lFat" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="lPrecio" class="form-control only-number" type="number" value="0" min="0"></td>
                                            </tr>
                                            <tr>
                                                <td>XL</td>
                                                <td><input id="xlFp" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="xlFc" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="xlEnsalada" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="xlCalorias" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="xlProt" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="xlCarb" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="xlFat" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="xlPrecio" class="form-control only-number" type="number" value="0" min="0"></td>
                                            </tr>
                                            <tr>
                                                <td>XXL</td>
                                                <td><input id="xxlFp" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="xxlFc" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="xxlEnsalada" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="xxlCalorias" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="xxlProt" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="xxlCarb" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="xxlFat" class="form-control only-number" type="number" value="0" min="0"></td>
                                                <td><input id="xxlPrecio" class="form-control only-number" type="number" value="0" min="0"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row mt20">
                            <div class="row box-gray">
                                <div class="box-header">
                                    <div class="col-md-6" style="padding-top: 5px;">
                                        <h4>Tamaño</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pull-right">
                                            <label>&nbsp;</label>
                                            <label class="switch">
                                                <input id="sizeInput" onclick="tipo(id)" type="checkbox" data-toggle="collapse" data-target="#size">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row content-header collapse mt20" id="size">
                                <section>
                                    <div class="col-md-12">
                                        <div class="col-md-2" style="text-align: center;">
                                            <h4>Pequeño</h4>    
                                        </div>
                                        <div class="col-md-2">
                                            <label class="switch">
                                                <input id="sizeSmall" type="checkbox" data-toggle="collapse" data-target="#small">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row collapse" id="small">
                                        <div class="col-md-12">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-11">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="caloriasSmall">Calorías</label>
                                                        <input class="form-control only-number" type="number" value="0" min="0" id="caloriasSmall">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="proteinasSmall">Proteína</label>
                                                        <input class="form-control only-number" type="number" value="0" min="0" id="proteinasSmall">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="carbohidratoSmall">Carbohidrato</label>
                                                        <input class="form-control only-number" type="number" value="0" min="0" id="carbohidratoSmall">
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
                                                    <div class="col-md-6">
                                                        <label for="fatSmall">Fat</label>
                                                        <input class="form-control only-number" type="number" value="0" min="0" name="" id="fatSmall">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="precioSmall">Precio</label>
                                                        <input class="form-control only-number" id="precioSmall" type="number" value="0" min="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <section>
                                    <div class="col-md-12">
                                        <div class="col-md-2" style="text-align: center;">
                                            <h4>Mediano</h4>    
                                        </div>
                                        <div class="col-md-2">
                                            <label class="switch">
                                                <input id="sizeMediano" type="checkbox" data-toggle="collapse" data-target="#mediano">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row collapse" id="mediano">
                                        <div class="col-md-12">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-11">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="caloriasMediano">Calorías</label>
                                                        <input class="form-control only-number" type="number" value="0" min="0" id="caloriasMediano">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="proteinasMediano">Proteína</label>
                                                        <input class="form-control only-number" type="number" value="0" min="0" id="proteinasMediano">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="carbohidratoMediano">Carbohidrato</label>
                                                        <input class="form-control only-number" type="number" value="0" min="0" id="carbohidratoMediano">
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
                                                    <div class="col-md-6">
                                                        <label for="fatMediano">Fat</label>
                                                        <input class="form-control only-number" type="number" value="0" min="0" name="" id="fatMediano">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="precioMediano">Precio</label>
                                                        <input class="form-control only-number" id="precioMediano" type="number" value="0" min="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <section>
                                    <div class="col-md-12">
                                        <div class="col-md-2" style="text-align: center;">
                                            <h4>Grande</h4>    
                                        </div>
                                        <div class="col-md-2">
                                            <label class="switch">
                                                <input id="sizeGrande" type="checkbox" data-toggle="collapse" data-target="#grande">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row collapse" id="grande">
                                        <div class="col-md-12">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-11">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="caloriasGrande">Calorías</label>
                                                        <input class="form-control only-number" type="number" value="0" min="0" id="caloriasGrande">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="proteinasGrande">Proteína</label>
                                                        <input class="form-control only-number" type="number" value="0" min="0" id="proteinasGrande">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="carbohidratoGrande">Carbohidrato</label>
                                                        <input class="form-control only-number" type="number" value="0" min="0" id="carbohidratoGrande">
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
                                                    <div class="col-md-6">
                                                        <label for="fatGrande">Fat</label>
                                                        <input class="form-control only-number" type="number" value="0" min="0" name="" id="fatGrande">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="precioGrande">Precio</label>
                                                        <input class="form-control only-number" id="precioGrande" type="number" value="0" min="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                        <div class="row mt20">
                            <div class="row box-gray">
                                <div class="box-header">
                                    <div class="col-md-6" style="padding-top: 5px;">
                                        <h4>Indicaciones Adicionales</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pull-right" style="padding-right: 20px;">
                                            <h4 style="cursor: pointer;"><a data-toggle="collapse" data-target="#adicionales"><i class="fas fa-plus"></i></a></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row collapse" id="adicionales">
                                <div class="row" id="adicion">
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-3">
                                        <a onclick="addAdicional()" style="cursor: pointer;">Agregar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row box-footer">
                            <div class="col-md-12">
                                <a onclick="guardarProducto(id)" class="btn btn-primary btn-ok">Actualizar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
            </div>
        </div>
    </div>
    <!-- MODAL VER-->
    <div class="modal fade" id="ver-producto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="row">
                    <div class="col-xs-12" id="cabecera-see">
                        <h2>
                            <span id="idProducto"></span>
                            <br/>
                            <small class="pull-right" id="fecha">2019-04-05</small>
                            <small id="nombreProducto"></small>
                        </h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4"><p id="descripcion"></p></div>
                    <div class="col-sm-4"><span id="categoria"></span></div>
                    <div class="col-sm-4">
                        <p></p>
                        <p></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="tipo">
                    </div>
                </div>
                <div class="mt20"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL ELIMINAR--> 
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Eliminar Producto</h4>
                </div>
                <div class="modal-body">
                    <p>¿Deseas eliminar este producto?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <a class="btn btn-danger btn-ok">Eliminar</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function format(input){
        var num = input.value.replace(/\./g,'');
        var metodoDescuento = $('input:radio[name=radios]:checked').val();

        if(!isNaN(num)){
            num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
            num = num.split('').reverse().join('').replace(/^[\.]/,'');
            input.value = num;
        }else{
            alert('Solo se permiten numeros');
            input.value = input.value.replace(/[^\d\.]*/g,'');
        }
    }
    //EDITAR PRODUCTO
    $('#editProducto').on('show.bs.modal', function(e) {
        var url = "includes/productos/verProducto.php";
        var idProducto = e.relatedTarget.id;
        $(this).find('.btn-ok').attr('id', idProducto);

        $.ajax({
            type: "POST",
            url: url,
            data: {data : idProducto},
            success: function(data){
                var datos = JSON.parse(data);

                $('#nombre').val(datos[0].nombre);
                var categoria = datos[0].idCategoria;
                $.ajax({
                    type: "POST",
                    url: "includes/productos/listarCategoria.php",
                    data: {data: idProducto},
                    success: function(data){
                        var datos = JSON.parse(data);
                        $('#categoria').html('');
                        $('#categoria').append('<option>Escoge</option>');
                        for(var i = 0; i<datos.length; i++){
                            $('#categoria').append('<option value="'+ datos[i].id +'">'+ datos[i].nombre +'</option>');
                        }
                        $('#categoria').val(categoria);
                    }
                });
                
                $('#descripcionEdit').val(datos[0].descripcion);
                var activo = datos[0].active;
                var menu_of_the_day_lunch = datos[0].menu_of_the_day_lunch;
                var menu_of_the_day_dinner = datos[0].menu_of_the_day_dinner;
                if(activo == 't'){
                    var habilitado = document.getElementById("habilitado").checked = true;
                }else{
                    var habilitado = document.getElementById("habilitado").checked = false;
                }
                if(menu_of_the_day_lunch == 't'){
                    var menuDiaAlmuerzo = document.getElementById("menuDiaAlmuerzo").checked = true;
                }else{
                    var menuDiaAlmuerzo = document.getElementById("menuDiaAlmuerzo").checked = false;
                }
                if(menu_of_the_day_dinner == 't'){
                    var menuDiaCena = document.getElementById("menuDiaCena").checked = true;
                }else{
                    var menuDiaCena = document.getElementById("menuDiaCena").checked = false;
                }
                var tipo = datos[0].tipo;

                if(tipo == 'size'){
                    var sizeInput = document.getElementById("sizeInput").checked = true;
                    document.getElementById("size").setAttribute("aria-expanded", true);
                    document.getElementById("size").style.height = "auto";
                    document.getElementById("size").classList.add("in");

                    var objetivoInput = document.getElementById("objetivoInput").checked;
                    if(objetivoInput){
                        document.getElementById("objetivoInput").checked = false;
                        document.getElementById("objetivo").setAttribute("aria-expanded", false);
                        document.getElementById("objetivo").style.height = "0px";
                        document.getElementById("objetivo").classList.remove("in");
                    }
                    var simpleInput = document.getElementById("simpleInput").checked;
                    if(simpleInput){
                        document.getElementById("simpleInput").checked = false;
                        document.getElementById("simple").setAttribute("aria-expanded", false);
                        document.getElementById("simple").style.height = "0px";
                        document.getElementById("simple").classList.remove("in");
                    }

                    var nAtributos = datos[0].atributos.length;
                    
                    for(var i = 0; i < nAtributos; i++) {
                        var size = datos[0].atributos[i].nombre;
                        if(size == 'pequeño'){                         
                            var caloriasSmall = parseInt(datos[0].atributos[i].calorias);
                            var proteinasSmall = parseInt(datos[0].atributos[i].proteinas);
                            var carbohidratoSmall = parseInt(datos[0].atributos[i].carbohidratos);
                            var fatSmall = parseInt(datos[0].atributos[i].fat);
                            var precioSmall = parseInt(datos[0].atributos[i].precio);
                            
                            if(caloriasSmall > 0 || proteinasSmall > 0 || carbohidratoSmall > 0 || fatSmall > 0 || precioSmall > 0){
                                var sizeSmall = document.getElementById("sizeSmall").checked = true;
                                document.getElementById("small").setAttribute("aria-expanded", true);
                                document.getElementById("small").style.height = "auto";
                                document.getElementById("small").classList.add("in");

                                $('#caloriasSmall').val(caloriasSmall);
                                $('#proteinasSmall').val(proteinasSmall);
                                $('#carbohidratoSmall').val(carbohidratoSmall);
                                $('#fatSmall').val(fatSmall);
                                $('#precioSmall').val(precioSmall);
                            }
                        }
                        if(size == 'mediano'){
                            var caloriasMediano = parseInt(datos[0].atributos[i].calorias);
                            var proteinasMediano = parseInt(datos[0].atributos[i].proteinas);
                            var carbohidratoMediano = parseInt(datos[0].atributos[i].carbohidratos);
                            var fatMediano = parseInt(datos[0].atributos[i].fat);
                            var precioMediano = parseInt(datos[0].atributos[i].precio);

                            if(caloriasMediano > 0 || proteinasMediano > 0 || carbohidratoMediano > 0 || fatMediano > 0 || precioMediano > 0){
                                var sizeMediano = document.getElementById("sizeMediano").checked = true;
                                document.getElementById("mediano").setAttribute("aria-expanded", true);
                                document.getElementById("mediano").style.height = "auto";
                                document.getElementById("mediano").classList.add("in");

                                $('#caloriasMediano').val(caloriasMediano);
                                $('#proteinasMediano').val(proteinasMediano);
                                $('#carbohidratoMediano').val(carbohidratoMediano);
                                $('#fatMediano').val(fatMediano);
                                $('#precioMediano').val(precioMediano);
                            }                            
                        }
                        if(size == 'grande'){
                            var caloriasGrande = parseInt(datos[0].atributos[i].calorias);
                            var proteinasGrande = parseInt(datos[0].atributos[i].proteinas);
                            var carbohidratoGrande = parseInt(datos[0].atributos[i].carbohidratos);
                            var fatGrande = parseInt(datos[0].atributos[i].fat);
                            var precioGrande = parseInt(datos[0].atributos[i].precio);

                            if(caloriasGrande > 0 || proteinasGrande > 0 || carbohidratoGrande > 0 || fatGrande > 0 || precioGrande > 0){
                                var sizeGrande = document.getElementById("sizeGrande").checked = true;
                                document.getElementById("grande").setAttribute("aria-expanded", true);
                                document.getElementById("grande").style.height = "auto";
                                document.getElementById("grande").classList.add("in");

                                $('#caloriasGrande').val(caloriasGrande);
                                $('#proteinasGrande').val(proteinasGrande);
                                $('#carbohidratoGrande').val(carbohidratoGrande);
                                $('#fatGrande').val(fatGrande);
                                $('#precioGrande').val(precioGrande);
                            }
                        }
                    }
                }else if(tipo == 'target'){
                    var objetivoInput = document.getElementById("objetivoInput").checked = true;
                    document.getElementById("objetivo").setAttribute("aria-expanded", true);
                    document.getElementById("objetivo").style.height = "auto";
                    document.getElementById("objetivo").classList.add("in");

                    var sizeInput = document.getElementById("sizeInput").checked;
                    if(sizeInput){
                        document.getElementById("sizeInput").checked = false;
                        document.getElementById("size").setAttribute("aria-expanded", false);
                        document.getElementById("size").style.height = "0px";
                        document.getElementById("size").classList.remove("in");
                    }
                    var simpleInput = document.getElementById("simpleInput").checked;
                    if(simpleInput){
                        document.getElementById("simpleInput").checked = false;
                        document.getElementById("simple").setAttribute("aria-expanded", false);
                        document.getElementById("simple").style.height = "0px";
                        document.getElementById("simple").classList.remove("in");
                    }

                    var nAtributos = datos[0].atributos.length;
                    
                    for(var i = 0; i < nAtributos; i++) {
                        var size = datos[0].atributos[i].nombre;
                        if(size == 'XXL'){
                            $('#xxlFp').val(datos[0].atributos[i].protein_source);
                            $('#xxlFc').val(datos[0].atributos[i].carb_source);
                            $('#xxlEnsalada').val(datos[0].atributos[i].salad);
                            $('#xxlCalorias').val(datos[0].atributos[i].calories);
                            $('#xxlProt').val(datos[0].atributos[i].protein);
                            $('#xxlCarb').val(datos[0].atributos[i].carb);
                            $('#xxlFat').val(datos[0].atributos[i].fat);
                            $('#xxlPrecio').val(datos[0].atributos[i].precio);
                        }
                        if(size == 'XL'){
                            $('#xlFp').val(datos[0].atributos[i].protein_source);
                            $('#xlFc').val(datos[0].atributos[i].carb_source);
                            $('#xlEnsalada').val(datos[0].atributos[i].salad);
                            $('#xlCalorias').val(datos[0].atributos[i].calories);
                            $('#xlProt').val(datos[0].atributos[i].protein);
                            $('#xlCarb').val(datos[0].atributos[i].carb);
                            $('#xlFat').val(datos[0].atributos[i].fat);
                            $('#xlPrecio').val(datos[0].atributos[i].precio);
                        }
                        if(size == 'L'){
                            $('#lFp').val(datos[0].atributos[i].protein_source);
                            $('#lFc').val(datos[0].atributos[i].carb_source);
                            $('#lEnsalada').val(datos[0].atributos[i].salad);
                            $('#lCalorias').val(datos[0].atributos[i].calories);
                            $('#lProt').val(datos[0].atributos[i].protein);
                            $('#lCarb').val(datos[0].atributos[i].carb);
                            $('#lFat').val(datos[0].atributos[i].fat);
                            $('#lPrecio').val(datos[0].atributos[i].precio);
                        }
                        if(size == 'Mantener'){
                            $('#mantenerFp').val(datos[0].atributos[i].protein_source);
                            $('#mantenerFc').val(datos[0].atributos[i].carb_source);
                            $('#mantenerEnsalada').val(datos[0].atributos[i].salad);
                            $('#mantenerCalorias').val(datos[0].atributos[i].calories);
                            $('#mantenerProt').val(datos[0].atributos[i].protein);
                            $('#mantenerCarb').val(datos[0].atributos[i].carb);
                            $('#mantenerFat').val(datos[0].atributos[i].fat);
                            $('#mantenerPrecio').val(datos[0].atributos[i].precio);
                        }
                        if(size == 'Quemar grasa'){
                            $('#qGrasaFp').val(datos[0].atributos[i].protein_source);
                            $('#qGrasaFc').val(datos[0].atributos[i].carb_source);
                            $('#qGrasaEnsalada').val(datos[0].atributos[i].salad);
                            $('#qGrasaCalorias').val(datos[0].atributos[i].calories);
                            $('#qGrasaProt').val(datos[0].atributos[i].protein);
                            $('#qGrasaCarb').val(datos[0].atributos[i].carb);
                            $('#qGrasaFat').val(datos[0].atributos[i].fat);
                            $('#qGrasaPrecio').val(datos[0].atributos[i].precio);
                        }
                        if(size == '0 Carbs'){
                            $('#carbsFp').val(datos[0].atributos[i].protein_source);
                            $('#carbsFc').val(datos[0].atributos[i].carb_source);
                            $('#carbsEnsalada').val(datos[0].atributos[i].salad);
                            $('#carbsCalorias').val(datos[0].atributos[i].calories);
                            $('#carbsProt').val(datos[0].atributos[i].protein);
                            $('#carbsCarb').val(datos[0].atributos[i].carb);
                            $('#carbsFat').val(datos[0].atributos[i].fat);
                            $('#carbsPrecio').val(datos[0].atributos[i].precio);
                        }
                    }

                }else if(tipo == 'simple'){
                    var simpleInput = document.getElementById("simpleInput").checked = true;
                    document.getElementById("simple").setAttribute("aria-expanded", true);
                    document.getElementById("simple").style.height = "auto";
                    document.getElementById("simple").classList.add("in");

                    var objetivoInput = document.getElementById("objetivoInput").checked;
                    if(objetivoInput){
                        document.getElementById("objetivoInput").checked = false;
                        document.getElementById("objetivo").setAttribute("aria-expanded", false);
                        document.getElementById("objetivo").style.height = "0px";
                        document.getElementById("objetivo").classList.remove("in");
                    }
                    var sizeInput = document.getElementById("sizeInput").checked;
                    if(sizeInput){
                        document.getElementById("sizeInput").checked = false;
                        document.getElementById("size").setAttribute("aria-expanded", false);
                        document.getElementById("size").style.height = "0px";
                        document.getElementById("size").classList.remove("in");
                    }
                    $('#precioSimple').val(datos[0].precio);
                }
                var adicion = `
                    <div class="row" id="1" style="margin-top: 10px;">
                        <div class="col-md-4">
                            <label for="adicionalNombre">Nombre</label>
                            <input id="adicionalNombre" class="form-control" type="text">
                        </div>
                        <div class="col-md-4">
                            <label for="adicionalPrecio">Precio</label>
                            <input id="adicionalPrecio" class="form-control only-number" type="number" value="0" min="0">
                        </div>
                    </div>
                `;
                $('#adicion').html(adicion);
                if(datos[0].additions.length != 0){
                    document.getElementById("adicionales").setAttribute("aria-expanded", true);
                    document.getElementById("adicionales").style.height = "auto";
                    document.getElementById("adicionales").classList.add("in");
                    if(datos[0].additions.length == 1){
                        var precio = datos[0].additions[0].price;
                        $('#adicionalNombre').val(datos[0].additions[0].nombre);
                        $('#adicionalPrecio').val(precio);
                    }else if(datos[0].additions.length > 1){
                        var x = 1;
                        for(var i = 0; i<datos[0].additions.length; i++){
                            if(i == 0){
                                var precio = datos[0].additions[0].price;
                                $('#adicionalNombre').val(datos[0].additions[0].nombre);
                                $('#adicionalPrecio').val(precio);
                            }else{
                                addAdicional();
                                $('#adicionalNombre' + x).val(datos[0].additions[i].nombre);
                                $('#adicionalPrecio' + x).val(datos[0].additions[i].price);
                            }

                            x = x + 1;
                        }
                    } 
                }else{
                    document.getElementById("adicionales").setAttribute("aria-expanded", false);
                    document.getElementById("adicionales").style.height = "0px";
                    document.getElementById("adicionales").classList.remove("in");
                }
            }
        });
    });
    function tipo(id){
        var elemento = document.getElementById(id).id;
        var elementoChecked = document.getElementById(id).checked;
        if(elemento == 'objetivoInput'){
            var sizeInput = document.getElementById("sizeInput").checked;
            if(sizeInput){
                document.getElementById("sizeInput").checked = false;
                document.getElementById("size").setAttribute("aria-expanded", false);
                document.getElementById("size").style.height = "0px";
                document.getElementById("size").classList.remove("in");
            }
            var simpleInput = document.getElementById("simpleInput").checked;
            if(simpleInput){
                document.getElementById("simpleInput").checked = false;
                document.getElementById("simple").setAttribute("aria-expanded", false);
                document.getElementById("simple").style.height = "0px";
                document.getElementById("simple").classList.remove("in");
            }
        }
        if(elemento == "sizeInput"){
            var objetivoInput = document.getElementById("objetivoInput").checked;
            if(objetivoInput){
                document.getElementById("objetivoInput").checked = false;
                document.getElementById("objetivo").setAttribute("aria-expanded", false);
                document.getElementById("objetivo").style.height = "0px";
                document.getElementById("objetivo").classList.remove("in");
            }
            var simpleInput = document.getElementById("simpleInput").checked;
            if(simpleInput){
                document.getElementById("simpleInput").checked = false;
                document.getElementById("simple").setAttribute("aria-expanded", false);
                document.getElementById("simple").style.height = "0px";
                document.getElementById("simple").classList.remove("in");
            }
        }
        if(elemento == "simpleInput"){
            var objetivoInput = document.getElementById("objetivoInput").checked;
            if(objetivoInput){
                document.getElementById("objetivoInput").checked = false;
                document.getElementById("objetivo").setAttribute("aria-expanded", false);
                document.getElementById("objetivo").style.height = "0px";
                document.getElementById("objetivo").classList.remove("in");
            }
            var sizeInput = document.getElementById("sizeInput").checked;
            if(sizeInput){
                document.getElementById("sizeInput").checked = false;
                document.getElementById("size").setAttribute("aria-expanded", false);
                document.getElementById("size").style.height = "0px";
                document.getElementById("size").classList.remove("in");
            }
        }
    }
    function addAdicional() {
        var fila = parseInt(document.getElementById("adicion").lastElementChild.id);
        var idFila = fila + 1;
        var textoHtml = `
            <div class="row" id="`+ idFila +`" style="margin-top: 10px;">
                <div class="col-md-4">
                    <label for="adicionalNombre`+idFila+`">Nombre</label>
                    <input id="adicionalNombre`+idFila+`" class="form-control" type="text">
                </div>
                <div class="col-md-4">
                    <label for="adicionalPrecio`+idFila+`">Precio</label>
                    <input id="adicionalPrecio`+idFila+`" class="form-control only-number" type="number" value="0" min="0">
                </div>
                <div class="col-md-2">
                    <a class="borrarProducto" id="borrarProducto" onClick="eliminarProducto(`+idFila+`)"><i class="fas fa-trash-alt"></i></a>
                    </div>
            </div>
        `;
        $('#adicion').append(textoHtml);
    }

    function eliminarProducto(id){
        $('#adicion #' + id).remove();
    }
    function guardarProducto(id){
        var nombre = document.getElementById("nombre").value;
        if (!nombre){ 
            alertify.error("Por favor escriba el nombre del producto.");
            window.scroll({top: 0, behavior: 'smooth'});
            document.getElementById("nombre").focus();
        }else{
            var categoria = document.getElementById("categoria").value;
            if (categoria == "Escoge"){
                alertify.error("Por favor seleccione la categoria del producto.");
                window.scroll({top: 0, behavior: 'smooth'});
                document.getElementById("categoria").focus();
            }else{
                var descripcion = document.getElementById("descripcionEdit").value;
                var habilitado = document.getElementById("habilitado").checked;
                var menuDiaAlmuerzo = document.getElementById("menuDiaAlmuerzo").checked;
                var menuDiaCena = document.getElementById("menuDiaCena").checked;

                var objetivoInput = document.getElementById("objetivoInput").checked;
                var sizeInput = document.getElementById("sizeInput").checked;
                var simpleInput = document.getElementById("simpleInput").checked;

                if(!objetivoInput && !sizeInput && !simpleInput){
                    alertify.error("Por favor especifique si lleva tamaño, objetivo o si es producto simple.");
                }else{
                    if(simpleInput){
                        var precioSimple = document.getElementById("precioSimple").value;
                        var precioSimple = precioSimple.replace(/\./g,'');
                        if(!precioSimple){
                            alertify.error("Por favor digite el precio del producto.");
                        }else{
                            var filas = document.getElementById("adicion").childElementCount;
                            var fila = document.getElementById("adicion");
                            var adicionalesCount = [];
                            var nombreAdicional = "";
                            var precio = 0;
                            var attr = $('#adicionales').hasClass('in'); 
                            if(attr){
                                if(filas == 1){
                                nombreAdicional = fila.children[0].children[0].children[1].value;
                                precio = fila.children[0].children[1].children[1].value;
                                if(nombreAdicional == '' || precio == ''){
                                    var precioNombre = 'false';
                                }
                                if(!nombreAdicional || !precio){
                                    adicionalesCount = 'false';
                                }else{
                                    adicionalesCount.push({
                                        nombreAdicional: nombreAdicional,
                                        precio: precio
                                    });
                                }
                                }else{
                                    for(i=0; i<filas; i++){
                                        nombreAdicional = fila.children[i].children[0].children[1].value;
                                        precio = fila.children[i].children[1].children[1].value;
                                        if(nombreAdicional == '' || precio == ''){
                                            var precioNombre = 'false';
                                        }
                                        
                                        adicionalesCount.push({
                                            nombreAdicional: nombreAdicional,
                                            precio: precio
                                        });
                                    
                                    }
                                }
                            }else{
                                var adicionalesCount = 'false';
                            }
                            var datos = [{
                                nombre: nombre,
                                categoria: categoria,
                                descripcion: descripcion,
                                habilitado: habilitado,
                                menuDiaAlmuerzo: menuDiaAlmuerzo,
                                menuDiaCena: menuDiaCena,
                                adicionales: adicionalesCount,
                                precio: precioSimple,
                                id: id
                            }];
                            if(precioNombre == 'false'){
                                alertify.error("Nombre o precio de Adicional invalido.");
                            }else{
                                datos = JSON.stringify(datos);
                                var url = "includes/productos/editProduct.php";
                                $.ajax({
                                    type: "POST",
                                    url: url,
                                    data: {data : datos, from: "simple"},
                                    success: function(data){
                                        if(data == 1){
                                            window.location.href ="productos";
                                        }else{
                                            alertify.error("Datos invalidos, por favor revise.");
                                        }
                                    }
                                });
                            }
                        }
                    }
                    if(objetivoInput){
                        var fuenteProteina = document.getElementById("fP").value;
                        var fuenteCarbohidrato = document.getElementById("fC").value;

                        var fuentes = {
                            fuenteProteina: fuenteProteina,
                            fuenteCarbohidrato: fuenteCarbohidrato
                        }

                        var carbsFp = document.getElementById("carbsFp").value;
                        var carbsFc = document.getElementById("carbsFc").value;
                        var carbsEnsalada = document.getElementById("carbsEnsalada").value;
                        var carbsCalorias = document.getElementById("carbsCalorias").value;
                        var carbsProt = document.getElementById("carbsProt").value;
                        var carbsCarb = document.getElementById("carbsCarb").value;
                        var carbsFat = document.getElementById("carbsFat").value;
                        var carbsPrecio = document.getElementById("carbsPrecio").value;

                        var carbs = {
                            carbsFp: carbsFp,
                            carbsFc: carbsFc,
                            carbsEnsalada: carbsEnsalada,
                            carbsCalorias: carbsCalorias,
                            carbsProt: carbsProt,
                            carbsCarb: carbsCarb,
                            carbsFat: carbsFat,
                            carbsPrecio: carbsPrecio
                        }

                        var qGrasaFp = document.getElementById("qGrasaFp").value;
                        var qGrasaFc = document.getElementById("qGrasaFc").value;
                        var qGrasaEnsalada = document.getElementById("qGrasaEnsalada").value;
                        var qGrasaCalorias = document.getElementById("qGrasaCalorias").value;
                        var qGrasaProt = document.getElementById("qGrasaProt").value;
                        var qGrasaCarb = document.getElementById("qGrasaCarb").value;
                        var qGrasaFat = document.getElementById("qGrasaFat").value;
                        var qGrasaPrecio = document.getElementById("qGrasaPrecio").value;

                        var qGrasa = {
                            qGrasaFp: qGrasaFp,
                            qGrasaFc: qGrasaFc,
                            qGrasaEnsalada: qGrasaEnsalada,
                            qGrasaCalorias: qGrasaCalorias,
                            qGrasaProt: qGrasaProt,
                            qGrasaCarb: qGrasaCarb,
                            qGrasaFat: qGrasaFat,
                            qGrasaPrecio: qGrasaPrecio
                        }

                        var mantenerFp = document.getElementById("mantenerFp").value;
                        var mantenerFc = document.getElementById("mantenerFc").value;
                        var mantenerEnsalada = document.getElementById("mantenerEnsalada").value;
                        var mantenerCalorias = document.getElementById("mantenerCalorias").value;
                        var mantenerProt = document.getElementById("mantenerProt").value;
                        var mantenerCarb = document.getElementById("mantenerCarb").value;
                        var mantenerFat = document.getElementById("mantenerFat").value;
                        var mantenerPrecio = document.getElementById("mantenerPrecio").value;

                        var mantener = {
                            mantenerFp: mantenerFp,
                            mantenerFc: mantenerFc,
                            mantenerEnsalada: mantenerEnsalada,
                            mantenerCalorias: mantenerCalorias,
                            mantenerProt: mantenerProt,
                            mantenerCarb: mantenerCarb,
                            mantenerFat: mantenerFat,
                            mantenerPrecio: mantenerPrecio
                        }

                        var lFp = document.getElementById("lFp").value;
                        var lFc = document.getElementById("lFc").value;
                        var lEnsalada = document.getElementById("lEnsalada").value;
                        var lCalorias = document.getElementById("lCalorias").value;
                        var lProt = document.getElementById("lProt").value;
                        var lCarb = document.getElementById("lCarb").value;
                        var lFat = document.getElementById("lFat").value;
                        var lPrecio = document.getElementById("lPrecio").value;

                        var l = {
                            lFp: lFp,
                            lFc: lFc,
                            lEnsalada: lEnsalada,
                            lCalorias: lCalorias,
                            lProt: lProt,
                            lCarb: lCarb,
                            lFat: lFat,
                            lPrecio: lPrecio
                        }

                        var xlFp = document.getElementById("xlFp").value;
                        var xlFc = document.getElementById("xlFc").value;
                        var xlEnsalada = document.getElementById("xlEnsalada").value;
                        var xlCalorias = document.getElementById("xlCalorias").value;
                        var xlProt = document.getElementById("xlProt").value;
                        var xlCarb = document.getElementById("xlCarb").value;
                        var xlFat = document.getElementById("xlFat").value;
                        var xlPrecio = document.getElementById("xlPrecio").value;

                        var xl = {
                            xlFp: xlFp,
                            xlFc: xlFc,
                            xlEnsalada: xlEnsalada,
                            xlCalorias: xlCalorias,
                            xlProt: xlProt,
                            xlCarb: xlCarb,
                            xlFat: xlFat,
                            xlPrecio: xlPrecio
                        }

                        var xxlFp = document.getElementById("xxlFp").value;
                        var xxlFc = document.getElementById("xxlFc").value;
                        var xxlEnsalada = document.getElementById("xxlEnsalada").value;
                        var xxlCalorias = document.getElementById("xxlCalorias").value;
                        var xxlProt = document.getElementById("xxlProt").value;
                        var xxlCarb = document.getElementById("xxlCarb").value;
                        var xxlFat = document.getElementById("xxlFat").value;
                        var xxlPrecio = document.getElementById("xxlPrecio").value;

                        var xxl = {
                            xxlFp: xxlFp,
                            xxlFc: xxlFc,
                            xxlEnsalada: xxlEnsalada,
                            xxlCalorias: xxlCalorias,
                            xxlProt: xxlProt,
                            xxlCarb: xxlCarb,
                            xxlFat: xxlFat,
                            xxlPrecio: xxlPrecio
                        }

                        var filas = document.getElementById("adicion").childElementCount;
                        var fila = document.getElementById("adicion");
                        var adicionalesCount = [];
                        var nombreAdicional = "";
                        var precio = 0;
                        var attr = $('#adicionales').hasClass('in'); 
                        if(attr){
                            if(filas == 1){
                            nombreAdicional = fila.children[0].children[0].children[1].value;
                            precio = fila.children[0].children[1].children[1].value;
                                if(nombreAdicional == '' || precio == ''){
                                    var precioNombre = 'false';
                                }
                                if(!nombreAdicional || !precio){
                                    adicionalesCount = 'false';
                                }else{
                                    adicionalesCount.push({
                                        nombreAdicional: nombreAdicional,
                                        precio: precio
                                    });
                                }
                            }else{
                                for(i=0; i<filas; i++){
                                    nombreAdicional = fila.children[i].children[0].children[1].value;
                                    precio = fila.children[i].children[1].children[1].value;
                                    if(nombreAdicional == '' || precio == ''){
                                        var precioNombre = 'false';
                                    }
                                    adicionalesCount.push({
                                        nombreAdicional: nombreAdicional,
                                        precio: precio
                                    });
                                
                                }
                            }
                        }else{
                            var adicionalesCount = 'false';
                        }
                        

                        var datos = [{
                            nombre: nombre,
                            categoria: categoria,
                            descripcion: descripcion,
                            habilitado: habilitado,
                            menuDiaAlmuerzo: menuDiaAlmuerzo,
                            menuDiaCena: menuDiaCena,
                            fuentes: fuentes,
                            carbs: carbs,
                            qGrasa: qGrasa,
                            mantener: mantener,
                            l: l,
                            xl: xl,
                            xxl, xxl,
                            adicionales: adicionalesCount,
                            id: id
                        }];
                        if(precioNombre == 'false'){
                                alertify.error("Nombre o precio de Adicional invalido.");
                        }else{
                            datos = JSON.stringify(datos);
                            var url = "includes/productos/editProduct.php";
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: {data : datos, from: "objetivo"},
                                success: function(data){
                                    if(data == 1){
                                        window.location.href ="productos";
                                    }else{
                                        alertify.error("Datos invalidos, por favor revise.");
                                    }
                                }
                            });
                        }

                    }
                    if(sizeInput){
                        var sizeSmall = document.getElementById("sizeSmall").checked;
                        var sizeMediano = document.getElementById("sizeMediano").checked;
                        var sizeGrande = document.getElementById("sizeGrande").checked;

                        if(!sizeSmall && !sizeMediano && !sizeGrande){
                            alertify.error("Por favor seleccione un tamaño.");
                        }else{
                            var datosSmall = false;
                            var datosMediano = false;
                            var datosGrande = false;
                            if(sizeSmall){
                                var caloriasSmall = document.getElementById("caloriasSmall").value;
                                var proteinasSmall = document.getElementById("proteinasSmall").value;
                                var carbohidratoSmall = document.getElementById("carbohidratoSmall").value;
                                var fatSmall = document.getElementById("fatSmall").value;
                                var precioSmall = document.getElementById("precioSmall").value;

                                datosSmall = {
                                    caloriasSmall: caloriasSmall,
                                    proteinasSmall: proteinasSmall,
                                    carbohidratoSmall: carbohidratoSmall,
                                    fatSmall: fatSmall,
                                    precioSmall: precioSmall
                                }
                            }
                            if(sizeMediano){
                                var caloriasMediano = document.getElementById("caloriasMediano").value;
                                var proteinasMediano = document.getElementById("proteinasMediano").value;
                                var carbohidratoMediano = document.getElementById("carbohidratoMediano").value;
                                var fatMediano = document.getElementById("fatMediano").value;
                                var precioMediano = document.getElementById("precioMediano").value;

                                datosMediano = {
                                    caloriasMediano: caloriasMediano,
                                    proteinasMediano: proteinasMediano,
                                    carbohidratoMediano: carbohidratoMediano,
                                    fatMediano: fatMediano,
                                    precioMediano: precioMediano
                                }
                            }
                            if(sizeGrande){
                                var caloriasGrande = document.getElementById("caloriasGrande").value;
                                var proteinasGrande = document.getElementById("proteinasGrande").value;
                                var carbohidratoGrande = document.getElementById("carbohidratoGrande").value;
                                var fatGrande = document.getElementById("fatGrande").value;
                                var precioGrande = document.getElementById("precioGrande").value;

                                datosGrande = {
                                    caloriasGrande: caloriasGrande,
                                    proteinasGrande: proteinasGrande,
                                    carbohidratoGrande: carbohidratoGrande,
                                    fatGrande: fatGrande,
                                    precioGrande: precioGrande
                                }
                            }

                            var filas = document.getElementById("adicion").childElementCount;
                            var fila = document.getElementById("adicion");
                            var adicionalesCount = [];
                            var nombreAdicional = "";
                            var precio = 0;
                            var attr = $('#adicionales').hasClass('in'); 
                            if(attr){
                                if(filas == 1){
                                    nombreAdicional = fila.children[0].children[0].children[1].value;
                                    precio = fila.children[0].children[1].children[1].value;
                                    if(nombreAdicional == '' || precio == ''){
                                        var precioNombre = 'false';
                                    }
                                    if(!nombreAdicional || !precio){
                                        adicionalesCount = 'false';
                                    }else{
                                        adicionalesCount.push({
                                            nombreAdicional: nombreAdicional,
                                            precio: precio
                                        });
                                    }
                                }else{
                                    for(i=0; i<filas; i++){
                                        nombreAdicional = fila.children[i].children[0].children[1].value;
                                        precio = fila.children[i].children[1].children[1].value;
                                        if(nombreAdicional == '' || precio == ''){
                                            var precioNombre = 'false';
                                        }
                                        adicionalesCount.push({
                                            nombreAdicional: nombreAdicional,
                                            precio: precio
                                        });
                                    
                                    }
                                }
                            }else{
                                var adicionalesCount = 'false';
                            }

                            var datos = [{
                                nombre: nombre,
                                categoria: categoria,
                                descripcion: descripcion,
                                habilitado: habilitado,
                                menuDiaAlmuerzo: menuDiaAlmuerzo,
                                menuDiaCena: menuDiaCena,
                                datosSmall: datosSmall,
                                datosMediano: datosMediano,
                                datosGrande: datosGrande,
                                adicionales: adicionalesCount,
                                id: id
                            }];
                            if(precioNombre == 'false'){
                                alertify.error("Nombre o precio de Adicional invalido.");
                            }else{
                                datos = JSON.stringify(datos);
                                var url = "includes/productos/editProduct.php";
                                $.ajax({
                                    type: "POST",
                                    url: url,
                                    data: {data : datos, from: "size"},
                                    success: function(data){
                                        if(data == 1){
                                            window.location.href ="productos";
                                        }else{
                                            alertify.error("Datos invalidos, por favor revise.");
                                        }
                                    }
                                });
                            }
                        }
                    }
                }
            }
        }
    }
    //Ver producto
    $('#ver-producto').on('show.bs.modal', function(e) {
        var url = "includes/productos/verProducto.php";
        var idProducto = e.relatedTarget.id;

        $.ajax({
            type: "POST",
            url: url,
            data: {data : idProducto},
            success: function(data){
                var datos = JSON.parse(data);
                $('#idProducto').html('#' + datos[0].id);
                $('#nombreProducto').html(datos[0].nombre);
                $('#descripcion').html(datos[0].descripcion);
                $('#categoria').html(datos[0].categoria);
                $('#fecha').html(datos[0].fecha);
                var tipo = datos[0].tipo;

                if(tipo == 'size'){
                    var nAtributos = datos[0].atributos.length;
                    
                    var medio = "";
                    var precio = 0;
                    for(var i = 0; i < nAtributos; i++) {
                        precio = datos[0].atributos[i].precio;

                        precio = precio.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
                        precio = precio.split('').reverse().join('').replace(/^[\.]/,'');
                        medio = medio + `
                            <div class="col-md-12">
                                <h4>`+ datos[0].atributos[i].nombre +`</h4>
                                <div class="col-md-2 center">
                                    <h5>Calorías</h5>`+ datos[0].atributos[i].calorias +`
                                </div>
                                <div class="col-md-2 center">
                                    <h5>Proteína</h5>`+ datos[0].atributos[i].proteinas +`
                                </div>
                                <div class="col-md-2 center">
                                    <h5>Carbohidrato</h5>`+ datos[0].atributos[i].carbohidratos +`
                                </div>
                                <div class="col-md-2 center">
                                    <h5>Fat</h5>`+ datos[0].atributos[i].fat +`
                                </div>
                                <div class="col-md-2 center">
                                    <h5>Precio</h5>`+ precio +`
                                </div>
                            </div>`;
                    }
                    if(datos[0].additions.length != 0){
                        var additional = `
                            <div class="row">
                                <h3>Adicionales</h3>
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th class="center">Precio</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        `;
                        var contentAdditional = "";
                        for (var i = 0; i<datos[0].additions.length; i++) {
                            console.log(i);
                            precio = datos[0].additions[i].price;
                            precio = precio.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
                            precio = precio.split('').reverse().join('').replace(/^[\.]/,'');
                            contentAdditional =  contentAdditional + `
                                <tr>
                                    <td>`+ datos[0].additions[i].nombre +`</td>
                                    <td class="center">`+ precio +`</td>
                                </tr>
                            `;
                        }
                        var footerAdditional = `
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                        `;
                        additional = additional + contentAdditional + footerAdditional;
                    }else{
                        var additional = "";
                    }
                    var content = `
                        <div class="col-md-12">
                            <h3>Tamaño</h3>
                            `+ medio +`
                            `+ additional +`
                        </div>
                    `;

                    $('#tipo').html(content);
                }else if(tipo == 'target'){
                    var nAtributos = datos[0].atributos.length;
                    var fuenteProteina = datos[0].fuenteProteina;
                    var fuenteCarbohidrato = datos[0].fuenteCarbohidrato;
                    if(!fuenteProteina){
                        fuenteProteina = "";
                    }
                    if(!fuenteCarbohidrato){
                        fuenteCarbohidrato = "";
                    }
                    var table = `
                        <table class="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="center">F.Prot.</th>
                                    <th class="center">F.Carb.</th>
                                    <th class="center">Ensalada</th>
                                    <th class="center">Calorías</th>
                                    <th class="center">Prot.</th>
                                    <th class="center">Carb.</th>
                                    <th class="center">Fat</th>
                                    <th class="center">Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    var fila = "";
                    var precio = 0;
                    for (var i = 0; i<nAtributos; i++) {
                        precio = datos[0].atributos[i].precio;
                        precio = precio.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
                        precio = precio.split('').reverse().join('').replace(/^[\.]/,'');
                        fila = fila + `
                            <tr>
                                <td>`+ datos[0].atributos[i].nombre +`</td>
                                <td class="center">`+ datos[0].atributos[i].protein_source +`</td>
                                <td class="center">`+ datos[0].atributos[i].carb_source +`</td>
                                <td class="center">`+ datos[0].atributos[i].salad +`</td>
                                <td class="center">`+ datos[0].atributos[i].calories +`</td>
                                <td class="center">`+ datos[0].atributos[i].protein +`</td>
                                <td class="center">`+ datos[0].atributos[i].carb +`</td>
                                <td class="center">`+ datos[0].atributos[i].fat +`</td>
                                <td class="center">`+ precio +`</td>
                            </tr>
                        `;
                    }

                    var contenido = table + fila + "</tbody></table>";
                    if(datos[0].additions.length != 0){
                        var additional = `
                            <div class="row">
                                <h3>Adicionales</h3>
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th class="center">Precio</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        `;
                        var contentAdditional = "";
                        for (var i = 0; i<datos[0].additions.length; i++) {
                            console.log(i);
                            precio = datos[0].additions[i].price;
                            precio = precio.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
                            precio = precio.split('').reverse().join('').replace(/^[\.]/,'');
                            contentAdditional =  contentAdditional + `
                                <tr>
                                    <td>`+ datos[0].additions[i].nombre +`</td>
                                    <td class="center">`+ precio +`</td>
                                </tr>
                            `;
                        }
                        var footerAdditional = `
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                        `;
                        additional = additional + contentAdditional + footerAdditional;
                    }else{
                        var additional = "";
                    }
                    var content = `
                        <div class="col-md-12">
                            <h3>Objetivos</h3>
                            <p><strong>Fuente de Proteína: `+ fuenteProteina +`</strong><br><strong>Fuente de Carbohidrato: `+ fuenteCarbohidrato +`</strong></p>
                            `+ contenido +`
                            `+ additional +`
                        </div>
                    `;
                    

                    $('#tipo').html(content);
                }else if(tipo == 'simple'){
                    var precio = datos[0].precio;
                    precio = precio.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
                    precio = precio.split('').reverse().join('').replace(/^[\.]/,'');
                    if(datos[0].additions.length != 0){
                        var additional = `
                            <div class="row">
                                <h3>Adicionales</h3>
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th class="center">Precio</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        `;
                        var contentAdditional = "";
                        for (var i = 0; i<datos[0].additions.length; i++) {
                            console.log(i);
                            precio = datos[0].additions[i].price;
                            precio = precio.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
                            precio = precio.split('').reverse().join('').replace(/^[\.]/,'');
                            contentAdditional =  contentAdditional + `
                                <tr>
                                    <td>`+ datos[0].additions[i].nombre +`</td>
                                    <td class="center">`+ precio +`</td>
                                </tr>
                            `;
                        }
                        var footerAdditional = `
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                        `;
                        additional = additional + contentAdditional + footerAdditional;
                    }else{
                        var additional = "";
                    }
                    var content = `
                        <div class="col-md-12">
                            <h3>Producto simple</h3>
                            <p><strong>Precio: </strong>$ `+ precio +`</p>
                            `+ additional +`
                        </div>
                    `;

                    $('#tipo').html(content);
                }
            }
        });
    });
    //Eliminar
    $('#confirm-delete').on('show.bs.modal', function(e) {
     $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));

     $('.debug-url').html('Delete URL: <strong>' + $(this).find(
         '.btn-ok').attr('href') + '</strong>');
    });
</script>
<?php
//REQUIERO EL ARCHIVO JS PARA LAS OPERACIONES
include('includes/footer/footer.php');
include('includes/productos/listarProductos.php');
    } else { 
        header("Location: index"); 
    } 
?>