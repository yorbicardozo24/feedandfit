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

    $hoy = getdate();
    $dia = $hoy['mday'];
    $mes = $hoy['mon'];
    $mesInicial = $mes - 1;
    if($mes < 10){
        $mes = "0" . $mes;
        $mesInicial = "0" . $mesInicial;
    }

    $year = $hoy['year'];

    $month = $year . "-" . $mesInicial;
    $aux = date('d-m-Y', strtotime("{$month} + 1 month"));
    $last_day = date('d-m-Y', strtotime("{$aux} - 1 day"));

    $fechaFinal = $mes . "/" . $dia . "/" . $year;
    $fechaInicial = $mesInicial . "/" . "{$last_day}" . "/" . $year;
?>
<div class="content" style="min-height: 842px;">
    <section class="content-header">
        <div class="row">
            <h1>Historial de Ventas</h1>
        </div>
        <div class="row">
			<div class="col-md-4">
				<div class="panel panel-box clearfix">
					<div class="panel-icon pull-left bg-blue">
						<i class="far fa-money-bill-alt"></i>
					</div>
					<div class="panel-value pull-right">
						<h2 id="vendido"></h2>
						<p>Vendido</p>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="panel panel-box clearfix">
					<div class="panel-icon pull-left bg-yellow">
						<i class="fas fa-shopping-cart"></i>
					</div>
					<div class="panel-value pull-right">
						<h2 id="productosVendidos"></h2>
						<p>Productos Vendidos</p>
					</div>
				</div>
			</div>
            <div class="col-md-4">
                <div class="panel panel-box clearfix">
                    <div class="panel-icon pull-left bg-green">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="panel-value pull-right">
                        <h2 id="mejorZona"></h2>
                        <p>Mejor Zona</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-box clearfix">
                    <div class="panel-icon pull-right bg-green">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <div class="panel-value pull-left">
                        <h2 id="mejorProducto"></h2>
                        <p>Producto más vendido<span id="cantidadMejorProducto"></span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <label>Fecha inicial</label>
                        <input type="text" class="form-control" id="fechaInicial" name="datepicker" value="<?php echo $fechaInicial; ?>" />
                    </div>
                    <div class="col-md-3">
                        <label>Fecha final</label>
                        <input type="text" class="form-control" id="fechaFinal" name="datepicker" value="<?php echo $fechaFinal; ?>" />        
                    </div>
                    <div class="col-md-3">
                        <label>&nbsp;</label>
                        <a href="" onClick="consultarXfechas(event)" class="btn btn-primary form-control">Consultar</a>
                    </div>
                    <div class="col-md-3" id="descargar">
                    </div>
                </div>
            </div>
        </div>
        <div id="contenedor_carga" style="height: 50%; background-color: transparent;">
            <div id="carga"></div>
        </div>
        <div class="row" id="contenidoHistorial">     
		</div>
	</section>
    <!-- MODAL VER PEDIDO -->
    <div class="modal fade" id="ver-pedido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="row">
                    <div class="col-xs-12" id="cabecera-see">
                        <h2>
                            <span id="idOrdenPedido"></span>
                            <br/>
                            <small class="pull-right" id="fecha"></small>
                            <small id="nombreOrdenPedido"></small>
                        </h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4"><p id="observacionCliente"></p></div>
                    <div class="col-sm-4"><span id="direccionCliente"></span></div>
                    <div class="col-sm-4">
                        <p id="correoCliente"></p>
                        <p id="telefonoCliente"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Cantidad</th>
                                    <th>Nombre</th>
                                    <th id="valorUnitario">Valor Unitario</th>
                                    <th id="valorTotal">Valor Total</th>
                                </tr>
                            </thead>
                            <tbody id="contentProduct">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row" id="totales">
                    <div class="col-xs-6"></div>
                    <div class="col-xs-6">
                        <p class="lead">Totales</p>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>Domicilio:</th>
                                        <td id="domicilio"></td>
                                    </tr>
                                    <tr>
                                        <th>Descuento:</th>
                                        <td id="descuento"></td>
                                    </tr>
                                    <tr>
                                        <th>Subtotal:</th>
                                        <td id="subtotal"></td>
                                    </tr>
                                    <tr>
                                        <th>Total:</th>
                                        <td id="total"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-danger btn-ok">Imprimir</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="includes/js/historial.js"></script>
<?php
include('includes/footer/footer.php');
    } else { 
        header("Location: index"); 
    } 
?>