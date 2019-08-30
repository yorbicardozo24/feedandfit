function addDireccion() {
  var fila = parseInt(document.getElementById("adicion").lastElementChild.id);
  var idFila = fila + 1;
  var url = "includes/clientes/buscarZonas.php";
    $.ajax({
      type: "POST",
      url: url,
      data: {data: url},
      success: function(data){
        var datos = JSON.parse(data);
        var textoHtml = `
        <div class="row" id="`+ idFila +`">
            <div class="col-md-4">
                <label for="adicionalNombre`+idFila+`">Dirección</label>
                <input id="adicionalNombre`+idFila+`" type="text" class="form-control" placeholder="Dirección" />
            </div>
            <div class="col-md-4">
                <label for="adicionalZona`+idFila+`" style="margin-right: 53px;">Zona Domiciliaría</label>
                <select id="adicionalZona`+idFila+`" class="form-control" id="zona">
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
                <a class="borrarProducto" id="borrarProducto" onClick="eliminarFila(`+idFila+`)"><i class="fas fa-trash-alt"></i></a>
                </div>
        </div>
    `;
    var content = textoHtml + opciones + footer;
    $('#adicion').append(content);
    $('#adicionalZona'+idFila).select2();
      }
    });
}
function eliminarFila(id){
    $('#adicion #' + id).remove();
}

function guardarCliente(from){
  var nombres = document.getElementById("nombres").value;
  var apellidos = document.getElementById("apellidos").value;
  var tipo = document.getElementById("tipo").value;
  var documento = document.getElementById("documento").value;
  var email = document.getElementById("email").value;
  var phone = document.getElementById("phone").value;
  var hora = document.getElementById("hora").value;
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
        var filas = document.getElementById("adicion").childElementCount;
        var fila = document.getElementById("adicion");
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
            data: {data : datos, from: 'add'},
            success: function(data){
              console.log(data);
                if(from == 'nuevoCliente'){
                  if(data == 1){
                    window.location.href ="clientes";
                  }else{
                    alertify.error("Datos invalidos, intenta cambiando el correo ;)");
                  }
                }else if(from == 'nuevoPedido'){
                  if(data == 1){
                    window.location.href ="nuevo_pedido";
                  }else{
                    alertify.error("Datos invalidos, intenta cambiando el correo ;)");
                  }
                }else if(from == 'nuevaOrden'){
                  if(data == 1){
                    window.location.href ="nueva_orden";
                  }else{
                    alertify.error("Datos invalidos, intenta cambiando el correo ;)");
                  }
                }
            }
          });
        }
    }
  }
}