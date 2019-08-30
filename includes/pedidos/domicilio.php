<?php
    //Recibo ID del select de direccion
    $id_direccion = $_POST['data'];

    require '../conexion.php';

    $sql = "SELECT * FROM addresses WHERE id = '$id_direccion'";
    $resultado = pg_query($sql);
    $fila = pg_fetch_array($resultado, null, PGSQL_ASSOC);

    $id_zona = $fila['delivery_zone_id'];


    $query = "SELECT * FROM delivery_zones WHERE id = '$id_zona'";
    $result = pg_query($query);
    $row = pg_fetch_array($result, null, PGSQL_ASSOC);

    $domicilio = number_format($row['price'], 0, '', '.');
    
    echo $domicilio;

?>