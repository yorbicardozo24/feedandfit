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
    $sql = "SELECT orders.legal_invoice_id, orders.id, customers.name, customers.last_name, orders.total, orders.payment_method, addresses.name AS direccion, orders.status, orders.delivery_time, orders.paid_plan FROM orders INNER JOIN customers ON orders.customer_id = customers.id INNER JOIN order_addresses ON order_addresses.order_id = orders.id INNER JOIN addresses ON order_addresses.address_id = addresses.id WHERE moved_to_sale_history_at IS NULL AND factura_estado IS NULL ORDER BY moved_to_sale_history_at DESC";
    $resultado = pg_query($sql);

?>
<div class="content">
    <section class="content-header">
        <div class="row">
            <h1>
                Pedidos
                <small>
                    <a href="nuevo_pedido" class="btn btn-primary">Nuevo pedido</a>
                    <a href="nueva_orden" class="btn btn-primary">Nuevo orden de pedido</a>
                </small>
            </h1>
        </div>
        <div class="row">
            <div class="box" style="padding-top: 15px; padding-bottom: 15px;">
            <table id="example" class="table table-striped table-bordered" style="width:100%; font-size: 12px;">
        <thead>
            <tr>
                <th>Factura No.</th>
                <th>Cliente</th>
                <th>Precio</th>
                <th>Dirección</th>
                <th>Forma de Pago</th>
                <th>Estado</th>
                <th>Entregar</th>
                <th class="hide"></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody id="ordenes">
            <?php
                //Ciclo para mostrar los pedidos
                while ($line = pg_fetch_array($resultado, null, PGSQL_ASSOC)) {
                    //Metodo de pago elegido por el cliente
                    $payment_method = $line['payment_method'];
                    //Estado del pedido
                    $status = $line['status'];
                    $estado = $line['status'];               
                    $subtotal = number_format($line['total'], 0, '', '.');
                    //Para mostrar el metodo de pago
                    switch($payment_method){
                        case 1:
                            $payment_method = "Consignación";
                            break;
                        case 2:
                            $payment_method = "Efectivo";
                            break;
                        case 3:
                            $payment_method = "iFood";
                            break;
                        case 4:
                            $payment_method = "UberEats";
                            break;
                        case 5:
                            $payment_method = "Sin Cobro";
                            break;
                    }
                    //Para mostrar el estado del pedido
                    switch($status){
                        case 0;
                            $status = "Nueva";
                            break;
                        case 1;
                            $status = "En Cocina";
                            break;
                        case 2;
                            $status = "Despachada";
                            break;
                        case 3;
                            $status = "Dom. Solicitado";
                            break;
                    }
                    //ID FACTURA
                    $idFactura = $line['legal_invoice_id'];
                    //PARA PLAN
                    $paidPlan = $line['paid_plan'];
            ?>
                <tr id="<?php echo $line['id'];?>">
                    <!-- FACTURA No. -->
                    <td><?php echo $idFactura; ?></td>
                    <!-- CLIENTE -->
                    <td><?php echo $line['name'] . " " . $line['last_name']; ?></td>
                    <!-- PRECIO -->
                    <!-- SI NO ES PLAN ENTONCES MUESTRA PRECIO -->
                    <?php if($paidPlan == 'f'){ ?>
                    <td><?php echo ("$ $subtotal"); ?></td>
                    <!-- SI ES PLAN ENTONCES MUESTRA PRECIO -->
                    <?php }else{ ?>
                    <td></td>
                    <?php } ?>
                    <!-- DIRECCIÓN -->
                    <td><?php echo $line['direccion']; ?></td>
                    <!-- FORMA DE PAGO -->
                    <td><?php echo $payment_method; ?></td>
                    <!-- ESTADO -->
                    <td><?php echo $status; ?></td>
                    <!-- HORA DE ENTREGA -->
                    <td><?php echo date('h:i A', strtotime($line['delivery_time'])); ?></td>
                    <td class="hide"><?php echo $line['delivery_time']; ?></td>
                    <!-- SI EL CLIENTE NO TIENE PLAN -->
                    <?php if($paidPlan == 'f'){ ?>
                    <td class="see" id="see">
                        <a href="#" id="<?php echo $line['id'];?>" data-href="impFactura(<?php echo $line['id'];?>)" data-toggle="modal" data-target="#ver-pedido" title="Ver Pedido">
                            <i class="far fa-eye"></i>
                        </a>
                    </td>
                    <td class="edit">
                        <a href="editar_pedido.php?id=<?php echo $line['id'];?>" title="Editar Pedido">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td class="eraser">
                        <a href="#" data-href="includes/pedidos/eliminar_pedido.php?id=<?php echo $line['id'];?>" data-toggle="modal" data-target="#confirm-delete" title="Eliminar Pedido">
                            <i class="fa fa-eraser" style="color: #7214;"></i>
                        </a>
                    </td>
                    <td class="print">
                        <a href="#" onClick="impFactura(id)" id="<?php echo $line['id'];?>" data-href="imprimir_pedido.php?id=<?php echo $line['id'];?>" data-toggle="modal" data-target="#imprimir-pedido" title="Imprimir factura">
                            <i class="fa fa-print"></i>
                        </a>
                    </td>
                    <?php 
                    // COCINA
                        if($estado == 1){
                            $text = "*Dirección:* " . $line['direccion'] . " *Cobro:* $ " . $subtotal;
                            $text = str_replace('#','%23',$text);
                    ?>
                    <td class="edit">
                        <a  title="Domicilio" onclick="domicilio(<?php echo $line['id']; ?>, '<?php echo $text; ?>')">
                            <i class="fab fa-whatsapp" style="color: rgba(37,211,102,0.4);"></i>
                        </a>
                    </td>
                    <td></td>
                    <!-- DOMICILIO SOLICITADO -->
                    <?php
                        }else if($estado == 3){
                            $text = "*Dirección:* " . $line['direccion'] . " *Cobro:* $ " . $subtotal;
                            $text = str_replace('#','%23',$text);
                    ?>
                    <td class="edit">
                        <a  title="Domicilio" onclick="domicilio(<?php echo $line['id']; ?>, '<?php echo $text; ?>')">
                            <i class="fab fa-whatsapp" style="color: rgba(37,211,102,0.4);"></i>
                        </a>
                    </td>
                    <td class="edit">
                        <a href="includes/pedidos/entregado.php?id=<?php echo $line['id'];?>" title="Entregado">
                            <i class="fas fa-check"></i>
                        </a>
                    </td>
                    <?php }else{ ?>
                    <td></td>
                    <td></td>
                    <?php } ?>
                    <?php }else{ ?>
                    <!-- SI EL CLIENTE TIENE PLAN -->
                    <td class="see" id="see">
                        <a href="#" id="<?php echo $line['id'];?>" data-href="impFacturaOrdenPedido(<?php echo $line['id'];?>)" data-toggle="modal" data-target="#ver-pedido" title="Ver Orden de Pedido">
                            <i class="far fa-eye"></i>
                        </a>
                    </td>
                    <td class="edit">
                        <a href="editar_orderPedido.php?id=<?php echo $line['id'];?>" title="Editar Orden de Pedido">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td class="eraser">
                        <a href="#" data-href="includes/pedidos/eliminar_pedido.php?id=<?php echo $line['id'];?>" data-toggle="modal" data-target="#confirm-delete" title="Eliminar Pedido">
                            <i class="fa fa-eraser" style="color: #7214;"></i>
                        </a>
                    </td>
                    <td class="print">
                        <a href="#" onClick="impFacturaOrdenPedido(id)" id="<?php echo $line['id'];?>" data-href="imprimir_pedido.php?id=<?php echo $line['id'];?>" data-toggle="modal" data-target="#imprimir-pedido" title="Imprimir factura">
                            <i class="fa fa-print"></i>
                        </a>
                    </td>
                    <?php 
                    // COCINA
                        if($estado == 1){
                            $text = "*Dirección:* " . $line['direccion'] . " *Cobro:* 0";
                            $text = str_replace('#','%23',$text);
                    ?>
                    <td class="edit">
                        <a  title="Domicilio" onclick="domicilio(<?php echo $line['id']; ?>, '<?php echo $text; ?>')">
                            <i class="fab fa-whatsapp" style="color: rgba(37,211,102,0.4);"></i>
                        </a>
                    </td>
                    <td></td>
                    <!-- DOMICILIO SOLICITADO -->
                    <?php 
                        }else if($estado == 3){ 
                            $text = "*Dirección:* " . $line['direccion'] . " *Cobro:* 0";
                            $text = str_replace('#','%23',$text);
                    ?>
                    <td class="edit">
                        <a  title="Domicilio" onclick="domicilio(<?php echo $line['id']; ?>, '<?php echo $text; ?>')">
                            <i class="fab fa-whatsapp" style="color: rgba(37,211,102,0.4);"></i>
                        </a>
                    </td>
                    <td class="edit">
                        <a href="includes/pedidos/entregado.php?id=<?php echo $line['id'];?>" title="Entregado">
                            <i class="fas fa-check"></i>
                        </a>
                    </td>
                    <?php }else{ ?>
                        <td></td>
                        <td></td>
                    <?php } ?>
                    <?php } ?>
                </tr>
                <?php } ?>
        </tbody>
    </table>
            </div>
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
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4" id="adicionales"></div>
                    <div class="col-md-4"></div>
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
    <!-- MODAL ELIMINAR-->
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Eliminar Pedido</h4>
				</div>
				<div class="modal-body">
					<p>¿Deseas eliminar este pedido?</p>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<a class="btn btn-danger btn-ok">Eliminar</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
include('includes/pedidos/pedidosJs.php');
include('includes/footer/footer.php');
    } else {
        header("Location: index");
    }
?>
