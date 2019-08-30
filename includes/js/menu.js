var url = window.location.pathname;

if(url === "/pedidos" || url === "/nuevo_pedido" || url === "/nueva_orden"){
    var element = document.getElementsByClassName("pedidos")[0].classList.add("selected");
}
if(url === "/historial"){
    var element = document.getElementsByClassName("historial")[0].classList.add("selected");
}
if(url === "/clientes" || url === "/nuevoCliente"){
    var element = document.getElementsByClassName("clientes")[0].classList.add("selected");
}
if(url === "/productos" || url === "/categorias" || url === "/nuevoProducto"){
    var element = document.getElementsByClassName("productos")[0].classList.add("selected");
}
if(url === "/zonas"){
    var element = document.getElementsByClassName("zonas")[0].classList.add("selected");
}
if(url === "/usuarios"){
    var element = document.getElementsByClassName("usuarios")[0].classList.add("selected");
}
if(url === "/dian"){
    var element = document.getElementsByClassName("dian")[0].classList.add("selected");
}