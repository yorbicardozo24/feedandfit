<?php
	$host = getenv('host');
    $user = getenv('user');
    $password = getenv('password');
    $dbname = getenv('dbname');

    $conexion = pg_connect("host=$host dbname=$dbname user=$user password=$password");

	if(!$conexion){
	    echo "<p><i>Error en la conexion</i></p>";
	    header("Location: error.php");
	}

?>
