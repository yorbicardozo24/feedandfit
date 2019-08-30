<?php

  $id_customer = $_POST['data'];

  require '../conexion.php';

  $sql = "SELECT * FROM addresses WHERE customer_id = '$id_customer'";
  $resultado = pg_query($sql);

  $query = "SELECT * FROM customers WHERE id = '$id_customer'";
  $result = pg_query($query);
  $fila = pg_fetch_array($result, null, PGSQL_ASSOC);
  $time_customer = $fila['delivery_time'];
  $selected_time = $fila['selected_time'];
  if($selected_time == 't'){
    $time_customer = $time_customer;
  }else{
    $time_customer = "";
  }
  $observacion_customer = $fila['observations'];

  $direccion = array();
  while ($line = pg_fetch_array($resultado, null, PGSQL_ASSOC)) {
    array_push($direccion, ["direccion" => $line['name'], "id" => $line['id'], "time" => $time_customer, "observacion" => $observacion_customer]);
  }

  echo json_encode($direccion);

?>
