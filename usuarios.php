<?php
session_start();

	if(isset($_SESSION["usuario"])){

require 'includes/conexion.php';
require 'includes/fun.php';

include('includes/header/header.php');
include('includes/menu/menu.php');
?>
<div class="content">Usuarios</div>
<?php
include('includes/footer/footer.php');
    } else { 
        header("Location: index"); 
    } 
?>