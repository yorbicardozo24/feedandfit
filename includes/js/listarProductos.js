$(document).ready(function() {
	//CAPTURO EL NUMERO DE FILAS
	var rows = parseInt(document.getElementById('rows').value);
	var url = "includes/productos/verProductos.php";
	//ENVIO EL NUMERO DE FILAS A VER PRODUCTOS
	$.ajax({
		type: "POST",
        url: url,
        data: {data : rows},
        success: function(data){
        	var datos = JSON.parse(data);

        	for(var i = 0; i < rows; i++){

        		var filas = datos[i].productos.length;
        		var menu1 = `<div id="menu`+ datos[i].id +`" class="tab-pane fade in active">`;
        		var menu = `<div id="menu`+ datos[i].id +`" class="tab-pane fade">`;
        		var encabezado = `
        				<div class="row">
        					<div class="box" style="padding-top: 15px; padding-bottom: 15px;">
        						<table id="table`+ i +`" class="table table-striped table-bordered" style="width:100%; font-size: 12px;">
        							<thead>
							            <tr>
							                <th>ID</th>
							                <th>Nombre</th>
							                <th>Descripción</th>
							                <th>¿Habilitado?</th>
							                <th>¿Menú del día: Almuerzo?</th>
							                <th>¿Menú del día: Cena?</th>
							                <th></th>
							                <th></th>
							                <th></th>
							            </tr>
							        </thead>
							        <tbody>
							    `;
				var body = "";
				var habilitado = "";
				var deshabilitado = "";
				var falso = "";
				var verdadero = "";
				var botonProductoHabilitado = "";
				var botonAlmuerzoDia = "";
				var botonCenaDia = "";
				var productoHabilitado = "";
				var almuerzoDia = "";
				var diaAlmuerzoHabilitado = "";
				var diaAlmuerzoDeshabilitado = "";
				var diaCenaHabilitado = "";
				var diaCenaDeshabilitado = "";
				var cenaDia = "";
				for(var a = 0; a<filas; a++){
					habilitado = `
						<label class="switch">
							<input id="`+ datos[i].productos[a].id +`-`+ datos[i].productos[a].habilitadoText +`" type="checkbox" onClick="habilitado(id)" checked>
							<span class="slider round"></span>
						</label>`;
					deshabilitado = `
						<label class="switch">
							<input id="`+ datos[i].productos[a].id +`-`+ datos[i].productos[a].habilitadoText +`" type="checkbox" onClick="habilitado(id)">
							<span class="slider round"></span>
						</label>`;

					diaAlmuerzoHabilitado = `
						<label class="switch">
							<input id="`+ datos[i].productos[a].id +`-`+ datos[i].productos[a].almuerzoText +`" type="checkbox" onClick="habilitado(id)" checked>
							<span class="slider round"></span>
						</label>`;
					diaAlmuerzoDeshabilitado = `
						<label class="switch">
							<input id="`+ datos[i].productos[a].id +`-`+ datos[i].productos[a].almuerzoText +`" type="checkbox" onClick="habilitado(id)">
							<span class="slider round"></span>
						</label>`;

					diaCenaHabilitado = `
						<label class="switch">
							<input id="`+ datos[i].productos[a].id +`-`+ datos[i].productos[a].cenaText +`" type="checkbox" onClick="habilitado(id)" checked>
							<span class="slider round"></span>
						</label>`;
					diaCenaDeshabilitado = `
						<label class="switch">
							<input id="`+ datos[i].productos[a].id +`-`+ datos[i].productos[a].cenaText +`" type="checkbox" onClick="habilitado(id)">
							<span class="slider round"></span>
						</label>`;
					productoHabilitado = datos[i].productos[a].habilitado;
					almuerzoDia = datos[i].productos[a].almuerzo;
					cenaDia = datos[i].productos[a].cena;


					if(productoHabilitado == 't'){
						botonProductoHabilitado = habilitado;
					}else{
						botonProductoHabilitado = deshabilitado;
					}
					if(almuerzoDia == 't'){
						botonAlmuerzoDia = diaAlmuerzoHabilitado;
					}else{
						botonAlmuerzoDia = diaAlmuerzoDeshabilitado;
					}
					if(cenaDia == 't'){
						botonCenaDia = diaCenaHabilitado;
					}else{
						botonCenaDia = diaCenaDeshabilitado;
					}

					body = body + `<tr>
							<td>`+ datos[i].productos[a].id +`</td>
							<td>`+ datos[i].productos[a].nombre +`</td>
							<td>`+ datos[i].productos[a].descripcion +`</td>
							<td style="text-align: center;">`+ botonProductoHabilitado +`</td>
							<td style="text-align: center;">`+ botonAlmuerzoDia +`</td>
							<td style="text-align: center;">`+ botonCenaDia +`</td>
							<td class="see" id="see">
								<a id="`+ datos[i].productos[a].id +`" data-toggle="modal" data-target="#ver-producto" title="Ver Producto">
									<i class="far fa-eye"></i>
								</a>
							</td>
							<td class="edit">
								<a id="`+ datos[i].productos[a].id +`" data-toggle="modal" data-target="#editProducto" title="Editar Producto">
                                    <i class="fa fa-edit"></i>
                                </a>
							</td>
							<td class="eraser">
								<a href="#" id="`+ datos[i].productos[a].id +`" data-href="includes/productos/eliminarProducto.php?id=`+ datos[i].productos[a].id +`" data-toggle="modal" data-target="#confirm-delete" title="Eliminar Producto">
                            		<i class="fa fa-times"></i>
                        		</a>
							</td>
						</tr>`;
				}
				var footer = "</tbody></table></div></div></div>";
				var content = menu1 + encabezado + body + footer;	
				var contenido =	menu + encabezado + body + footer;
                
				if(i != 0){
					$('#listadoCategorias').append(contenido);
				}else{
					$('#listadoCategorias').append(content);
				}
				$('#table' + i).DataTable();
        	}

    		var load = document.getElementById("contenedor_carga");
			load.style.visibility = 'hidden';
			load.style.opacity = '0';


			setTimeout("$('#carga').remove()",3000);
        }
	});
});

function habilitado(id){
	var elemento = document.getElementById(id).checked;

	var cadena = id,
    	separador = "-",
    	arregloDeSubCadenas = cadena.split(separador);

    var idProducto = arregloDeSubCadenas[0];
    var seccionProducto = arregloDeSubCadenas[1];

    var datos = {
    	checked: elemento,
    	id: idProducto,
    	seccionProducto: seccionProducto
    }
    var datos = JSON.stringify(datos);

    var url = "includes/productos/actualizarProductos.php";
    $.ajax({
		type: "POST",
        url: url,
        data: {data : datos},
        success: function(data){
        }
    });
}