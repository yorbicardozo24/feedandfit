<?php
session_start();
	if(isset($_SESSION["usuario"])){

  require 'includes/conexion.php';
  require 'includes/fun.php';
  include('includes/header/header.php');
  include('includes/menu/menu.php');

    $query = "SELECT * FROM delivery_zones ORDER BY name ASC";
    $result = pg_query($query);
?>
<div class="content">
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
                                  <select id="zona" class="form-control">
                                    <option value="Escoge">Escoge</option>
                                    <?php while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) { ?>
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
                                    <a onclick="guardarCliente('nuevoCliente')" class="btn btn-primary">Guardar</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $('#zona').select2();
});
</script>
<?php
include('includes/footer/footer.php');
include('includes/clientes/nuevoClienteJs.php');
    } else { 
        header("Location: index"); 
    } 
?>