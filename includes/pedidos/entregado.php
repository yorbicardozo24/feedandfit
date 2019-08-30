<?php
session_start();
if(isset($_SESSION["usuario"])){
    require '../conexion.php';

    $id = $_GET['id'];
    
    $sql = "SELECT * FROM orders WHERE id = '$id'";
    $resultado = pg_query($sql);
    $fila = pg_fetch_array($resultado, null, PGSQL_ASSOC);
    
    $id_factura = $fila['legal_invoice_id'];
    $paidPlan = $fila['paid_plan'];

    if($id_factura === NULL){
      if($paidPlan == 't'){
        $fecha = getdate();
        $year = $fecha['year'];
        $dia = $fecha['mday'];
        $mes = $fecha['mon'];

        $hora = $fecha['hours'];
        $minutos = $fecha['minutes'];
        $segundos = $fecha['seconds'];

        $fecha_pagada = $year . "-" . $mes . "-" .  $dia . " " . $hora . ":" . $minutos . ":" . $segundos;

        $set = "UPDATE orders SET factura_estado = 'plan', moved_to_sale_history_at = '$fecha_pagada' WHERE id = '$id'";
        $set_query = pg_query($set);
        
        $sqlC = "SELECT * FROM orders WHERE id = '$id'";
        $queryC = pg_query($sqlC);
        $filaC = pg_fetch_array($queryC, null, PGSQL_ASSOC);

        $cliente = $filaC['customer_id'];
        
        $sqlNc = "SELECT * FROM customers WHERE id = '$cliente'";
        $queryNc = pg_query($sqlNc); 
        $filaNc = pg_fetch_array($queryNc, null, PGSQL_ASSOC);

        $name = $filaNc['name'];
        $last_name = $filaNc['last_name'];
        $email = $filaNc['email'];
        $phone = $filaNc['phone'];
        $observations = $filaNc['observations'];
        $delivery_time = $filaNc['delivery_time'];
        $created_at = $filaNc['created_at'];
        $selected_time = $filaNc['selected_time'];
        $document_number = $filaNc['document_number'];
        $document_type = $filaNc['document_type'];

        $sqlInc = "INSERT INTO history_customers (customer_id, name, last_name, email, phone, observations, delivery_time, created_at, selected_time, document_number, document_type, order_id) VALUES ('$cliente', '$name', '$last_name', '$email', '$phone', '$observations', '$delivery_time', '$created_at', '$selected_time', '$document_number', '$document_type', '$id')";
        $queryInc = pg_query($sqlInc);

        $sql = "SELECT * FROM order_products WHERE order_id = '$id'";
        $query = pg_query($sql);
        while ($line = pg_fetch_array($query, null, PGSQL_ASSOC)) {
          $sizeId = $line['size_id'];
          $targetId = $line['target_id'];
          $product_id = $line['product_id'];
          $adicionesId = $line['additions_id'];

          $adicionesId = explode(",", $adicionesId);
          $nAdicionesId = count($adicionesId);
          
          for ($i=0; $i <$nAdicionesId ; $i++) {
              $idAdditions = $adicionesId[$i];
              if($idAdditions){
                  $sql_i = "SELECT * FROM additions WHERE id = '$idAdditions'";
                  $query_i = pg_query($sql_i);
                  $row_i = pg_fetch_array($query_i, null, PGSQL_ASSOC);

                  $nombreA = $row_i['name'];
                  $precioA = $row_i['price'];
                  $product_idA = $row_i['product_id'];
                  $created_atA = $row_i['created_at'];
                  
                  $sqlA = "INSERT INTO history_additions (additions_id, price, name, product_id, created_at, order_id) VALUES ('$idAdditions', '$precioA', '$nombreA', '$product_idA', '$created_atA', '$id')";
                  $queryA = pg_query($sqlA);
              }
          }

          $product = "SELECT * FROM products WHERE id = '$product_id'";
          $product_query = pg_query($product);
          $filaP = pg_fetch_array($product_query, null, PGSQL_ASSOC);

          $idP = $filaP['id'];
          $category_id = $filaP['category_id'];
          $name = $filaP['name'];
          $description = $filaP['description'];
          $price = $filaP['price'];
          $menu_of_the_day_lunch = $filaP['menu_of_the_day_lunch'];
          $combo_of_the_day = $filaP['combo_of_the_day'];
          $created_at = $filaP['created_at'];
          $target_selected = $filaP['target_selected'];
          $size_selected = $filaP['size_selected'];
          $additions_selected = $filaP['addition_selected'];
          $protein_source = $filaP['protein_source'];
          $carb_source = $filaP['carb_source'];
          $active = $filaP['active'];
          $menu_of_the_day_dinner = $filaP['menu_of_the_day_dinner'];
          $simple_product = $filaP['simple_product'];
          if($simple_product == ''){
            $simple_product = 'f';
          }

          $sqlP = "INSERT INTO history_product (product_id, category_id, name, description, price, menu_of_the_day_lunch, combo_of_the_day, created_at, target_selected, size_selected, additions_selected, protein_source, carb_source, active,  menu_of_the_day_dinner, simple_product, order_id) VALUES ('$idP', '$category_id', '$name', '$description', '$price', '$menu_of_the_day_lunch', '$combo_of_the_day', '$created_at', '$target_selected', '$size_selected', '$additions_selected', '$protein_source', '$carb_source', '$active', '$menu_of_the_day_dinner', '$simple_product', '$id')";
          $queryP = pg_query($sqlP);
          if($queryP){
            if($sizeId){
              $sqlS = "SELECT * FROM sizes WHERE id = '$sizeId'";
              $queryS = pg_query($sqlS);
              $filaS = pg_fetch_array($queryS, null, PGSQL_ASSOC);

              $nombreS = $filaS['name'];
              $caloriesS = $filaS['calories'];
              $proteinS = $filaS['protein'];
              $carbsS = $filaS['carbs'];
              $priceS = $filaS['price'];
              $fatS = $filaS['fat'];
              $product_idS = $filaS['product_id'];

              $sql_s = "INSERT INTO history_sizes (name, calories, protein, carbs, price, fat, product_id, id_size, order_id) VALUES ('$nombreS', '$caloriesS', '$proteinS', '$carbsS', '$priceS', '$fatS', '$product_idS', '$sizeId', '$id')";
              $query_s = pg_query($sql_s);
            }else{
              $sqlT = "SELECT * FROM targets WHERE id = '$targetId'";
              $queryT = pg_query($sqlT);
              $filaT = pg_fetch_array($queryT, null, PGSQL_ASSOC);

              $nombreT = $filaT['name'];
              $protein_sourceT = $filaT['protein_source'];
              $carb_sourceT = $filaT['carb_source'];
              $saladT = $filaT['salad'];
              $caloriesT = $filaT['calories'];
              $proteinT = $filaT['protein'];
              $carbT = $filaT['carb'];
              $priceT = $filaT['price'];
              $fatT = $filaT['fat'];
              $product_idT = $filaT['product_id'];

              $sql_t = "INSERT INTO history_targets (target_id, name, carb_source, protein_source, product_id, salad, calories, protein, carb, fat, price, order_id) VALUES ('$targetId', '$nombreT', '$carb_sourceT', '$protein_sourceT', '$product_idT', '$saladT', '$caloriesT', '$proteinT', '$carbT', '$fatT', '$priceT', '$id')";
              $query_t = pg_query($sql_t);
            }
          }
        }
        $sqli = "SELECT * FROM order_addresses WHERE order_id = '$id'";
        $query_i = pg_query($sqli);
        while ($row = pg_fetch_array($query_i, null, PGSQL_ASSOC)) {
          $addressId = $row['address_id'];

          $sqlA = "SELECT * FROM addresses WHERE id = '$addressId'";
          $queryA = pg_query($sqlA);
          $filaA = pg_fetch_array($queryA, null, PGSQL_ASSOC);

          $customer_id = $filaA['customer_id'];
          $delivery_zone_id = $filaA['delivery_zone_id'];
          $name = $filaA['name'];
          $created_at = $filaA['created_at'];

          $sqlH = "INSERT INTO history_addresses (address_id, customer_id, delivery_zone_id, name, created_at, order_id) VALUES ('$addressId', '$customer_id', '$delivery_zone_id', '$name', '$created_at', '$id')";
          $queryH = pg_query($sqlH);

          if($queryH){
            $sql = "SELECT * FROM delivery_zones WHERE id = '$delivery_zone_id'";
            $query = pg_query($sql);
            $fila = pg_fetch_array($query, null, PGSQL_ASSOC);

            $name = $fila['name'];
            $price = $fila['price'];
            $active = $fila['active'];
            $created_at = $fila['created_at'];

            $sqlD = "INSERT INTO history_delivery_zones (delivery_zone_id, name, price, active, created_at) VALUES ('$delivery_zone_id', '$name', '$price', '$active', '$created_at')";
            $queryD = pg_query($sqlD);
          }
        }
        if($set_query){
          header("Location: ../../pedidos");
        }
      }else{
        header("Location: ../../pedidos");
      }
    }else{
      $fecha = getdate();

      $year = $fecha['year'];
      $dia = $fecha['mday'];
      $mes = $fecha['mon'];

      $hora = $fecha['hours'];
      $minutos = $fecha['minutes'];
      $segundos = $fecha['seconds'];

      $fecha_pagada = $year . "-" . $mes . "-" .  $dia . " " . $hora . ":" . $minutos . ":" . $segundos;

      $set = "UPDATE orders SET factura_estado = 'fpagada', moved_to_sale_history_at = '$fecha_pagada' WHERE id = '$id'";
      $set_query = pg_query($set);

      $sqlC = "SELECT * FROM orders WHERE id = '$id'";
      $queryC = pg_query($sqlC);
      $filaC = pg_fetch_array($queryC, null, PGSQL_ASSOC);

      $cliente = $filaC['customer_id'];
      
      $sqlNc = "SELECT * FROM customers WHERE id = '$cliente'";
      $queryNc = pg_query($sqlNc); 
      $filaNc = pg_fetch_array($queryNc, null, PGSQL_ASSOC);

      $name = $filaNc['name'];
      $last_name = $filaNc['last_name'];
      $email = $filaNc['email'];
      $phone = $filaNc['phone'];
      $observations = $filaNc['observations'];
      $delivery_time = $filaNc['delivery_time'];
      $created_at = $filaNc['created_at'];
      $selected_time = $filaNc['selected_time'];
      $document_number = $filaNc['document_number'];
      $document_type = $filaNc['document_type'];

      $sqlInc = "INSERT INTO history_customers (customer_id, name, last_name, email, phone, observations, delivery_time, created_at, selected_time, document_number, document_type, order_id) VALUES ('$cliente', '$name', '$last_name', '$email', '$phone', '$observations', '$delivery_time', '$created_at', '$selected_time', '$document_number', '$document_type', '$id')";
      $queryInc = pg_query($sqlInc);

      $sql = "SELECT * FROM order_products WHERE order_id = '$id'";
      $query = pg_query($sql);
      while ($line = pg_fetch_array($query, null, PGSQL_ASSOC)) {
        $sizeId = $line['size_id'];
        $targetId = $line['target_id'];
        $product_id = $line['product_id'];
        $adicionesId = $line['additions_id'];

        $adicionesId = explode(",", $adicionesId);
        $nAdicionesId = count($adicionesId);
        
        for ($i=0; $i <$nAdicionesId ; $i++) {
            $idAdditions = $adicionesId[$i];
            if($idAdditions){
                $sql_i = "SELECT * FROM additions WHERE id = '$idAdditions'";
                $query_i = pg_query($sql_i);
                $row_i = pg_fetch_array($query_i, null, PGSQL_ASSOC);

                $nombreA = $row_i['name'];
                $precioA = $row_i['price'];
                $product_idA = $row_i['product_id'];
                $created_atA = $row_i['created_at'];
                
                $sqlA = "INSERT INTO history_additions (additions_id, price, name, product_id, created_at, order_id) VALUES ('$idAdditions', '$precioA', '$nombreA', '$product_idA', '$created_atA', '$id')";
                $queryA = pg_query($sqlA);
            }
        }

        $product = "SELECT * FROM products WHERE id = '$product_id'";
        $product_query = pg_query($product);
        $filaP = pg_fetch_array($product_query, null, PGSQL_ASSOC);

        $idP = $filaP['id'];
        $category_id = $filaP['category_id'];
        $name = $filaP['name'];
        $description = $filaP['description'];
        $price = $filaP['price'];
        $menu_of_the_day_lunch = $filaP['menu_of_the_day_lunch'];
        $combo_of_the_day = $filaP['combo_of_the_day'];
        $created_at = $filaP['created_at'];
        $target_selected = $filaP['target_selected'];
        $size_selected = $filaP['size_selected'];
        $additions_selected = $filaP['addition_selected'];
        $protein_source = $filaP['protein_source'];
        $carb_source = $filaP['carb_source'];
        $active = $filaP['active'];
        $menu_of_the_day_dinner = $filaP['menu_of_the_day_dinner'];
        $simple_product = $filaP['simple_product'];
        if($simple_product == ''){
          $simple_product = 'f';
        }

        $sqlP = "INSERT INTO history_product (product_id, category_id, name, description, price, menu_of_the_day_lunch, combo_of_the_day, created_at, target_selected, size_selected, additions_selected, protein_source, carb_source, active,  menu_of_the_day_dinner, simple_product, order_id) VALUES ('$idP', '$category_id', '$name', '$description', '$price', '$menu_of_the_day_lunch', '$combo_of_the_day', '$created_at', '$target_selected', '$size_selected', '$additions_selected', '$protein_source', '$carb_source', '$active', '$menu_of_the_day_dinner', '$simple_product', '$id')";
        $queryP = pg_query($sqlP);
        if($queryP){
          if($sizeId){
            $sqlS = "SELECT * FROM sizes WHERE id = '$sizeId'";
            $queryS = pg_query($sqlS);
            $filaS = pg_fetch_array($queryS, null, PGSQL_ASSOC);

            $nombreS = $filaS['name'];
            $caloriesS = $filaS['calories'];
            $proteinS = $filaS['protein'];
            $carbsS = $filaS['carbs'];
            $priceS = $filaS['price'];
            $fatS = $filaS['fat'];
            $product_idS = $filaS['product_id'];

            $sql_s = "INSERT INTO history_sizes (name, calories, protein, carbs, price, fat, product_id, id_size, order_id) VALUES ('$nombreS', '$caloriesS', '$proteinS', '$carbsS', '$priceS', '$fatS', '$product_idS', '$sizeId', '$id')";
            $query_s = pg_query($sql_s);
          }else{
            $sqlT = "SELECT * FROM targets WHERE id = '$targetId'";
            $queryT = pg_query($sqlT);
            $filaT = pg_fetch_array($queryT, null, PGSQL_ASSOC);

            $nombreT = $filaT['name'];
            $protein_sourceT = $filaT['protein_source'];
            $carb_sourceT = $filaT['carb_source'];
            $saladT = $filaT['salad'];
            $caloriesT = $filaT['calories'];
            $proteinT = $filaT['protein'];
            $carbT = $filaT['carb'];
            $priceT = $filaT['price'];
            $fatT = $filaT['fat'];
            $product_idT = $filaT['product_id'];

            $sql_t = "INSERT INTO history_targets (target_id, name, carb_source, protein_source, product_id, salad, calories, protein, carb, fat, price, order_id) VALUES ('$targetId', '$nombreT', '$carb_sourceT', '$protein_sourceT', '$product_idT', '$saladT', '$caloriesT', '$proteinT', '$carbT', '$fatT', '$priceT', '$id')";
            $query_t = pg_query($sql_t);
          }
        }
      }
      $sqli = "SELECT * FROM order_addresses WHERE order_id = '$id'";
      $query_i = pg_query($sqli);
      while ($row = pg_fetch_array($query_i, null, PGSQL_ASSOC)) {
        $addressId = $row['address_id'];

        $sqlA = "SELECT * FROM addresses WHERE id = '$addressId'";
        $queryA = pg_query($sqlA);
        $filaA = pg_fetch_array($queryA, null, PGSQL_ASSOC);

        $customer_id = $filaA['customer_id'];
        $delivery_zone_id = $filaA['delivery_zone_id'];
        $name = $filaA['name'];
        $created_at = $filaA['created_at'];

        $sqlH = "INSERT INTO history_addresses (address_id, customer_id, delivery_zone_id, name, created_at, order_id) VALUES ('$addressId', '$customer_id', '$delivery_zone_id', '$name', '$created_at', '$id')";
        $queryH = pg_query($sqlH);

        if($queryH){
          $sql = "SELECT * FROM delivery_zones WHERE id = '$delivery_zone_id'";
          $query = pg_query($sql);
          $fila = pg_fetch_array($query, null, PGSQL_ASSOC);

          $name = $fila['name'];
          $price = $fila['price'];
          $active = $fila['active'];
          $created_at = $fila['created_at'];

          $sqlD = "INSERT INTO history_delivery_zones (delivery_zone_id, name, price, active, created_at) VALUES ('$delivery_zone_id', '$name', '$price', '$active', '$created_at')";
          $queryD = pg_query($sqlD);
        }
      }
      if($set_query){
        header("Location: ../../pedidos");
      }
    }
}else{ header("Location: ../../index");}

?>