<?php 
    //Recibo id del producto seleccionado
    $id_producto = $_POST['data'];

    require '../conexion.php';

    $sql = "SELECT * FROM products WHERE id = '$id_producto'";
    $result = pg_query($sql);
    $fila = pg_fetch_array($result, null, PGSQL_ASSOC);

    //target = objetivo del producto
    //size = tamaño del producto
    $target = $fila['target_selected'];
    $size = $fila['size_selected'];
    $simple = $fila['simple_product'];
    $precio = $fila['price'];

    //si el producto tiene objetivo en verdadero y el tamaño en falso
    if($target === 't' && $size === 'f'){
        $query = "SELECT * FROM targets WHERE product_id = '$id_producto' ORDER BY id ASC";
        $resultado = pg_query($query);

        $targets = array();
        while ($line = pg_fetch_array($resultado, null, PGSQL_ASSOC)) {
            $price = number_format($line['price'], 0, '', '.');
            array_push($targets, ["target" => $line['name'], "price" => $price, "id" => $line['id']]);
        }
        $sql = "SELECT * FROM additions WHERE product_id = '$id_producto'";
        $query = pg_query($sql);

        $adicion = array();
        while ($line = pg_fetch_array($query, null, PGSQL_ASSOC)) {
            $price = number_format($line['price'], 0, '', '.');
            array_push($adicion, ["nombre" => $line['name'], "price" => $price, "id" => $line['id']]);
        }
        array_push($targets, ["adicion" => $adicion]);
        echo json_encode($targets);
        //si el tamaño tiene objetivo en verdadero y el objetivo en falso
    }else if($size === 't' && $target === 'f'){
        $query = "SELECT * FROM sizes WHERE product_id = '$id_producto'";
        $resultado = pg_query($query);

        $sizes = array();
        while ($line = pg_fetch_array($resultado, null, PGSQL_ASSOC)) {
            $price = number_format($line['price'], 0, '', '.');
            array_push($sizes, ["size" => $line['name'], "price" => $price, "id" => $line['id']]);
        }
        $sql = "SELECT * FROM additions WHERE product_id = '$id_producto'";
        $query = pg_query($sql);

        $adicion = array();
        while ($line = pg_fetch_array($query, null, PGSQL_ASSOC)) {
            $price = number_format($line['price'], 0, '', '.');
            array_push($adicion, ["nombre" => $line['name'], "price" => $price, "id" => $line['id']]);
        }
        array_push($sizes, ["adicion" => $adicion]);

        echo json_encode($sizes);
    }else if($size == 'f' && $target == 'f' && $simple == 't'){
        $simple = array();
        $precio = number_format($precio, 0, '', '.');
        $sql = "SELECT * FROM additions WHERE product_id = '$id_producto'";
        $query = pg_query($sql);

        $adicion = array();
        while ($line = pg_fetch_array($query, null, PGSQL_ASSOC)) {
            $price = number_format($line['price'], 0, '', '.');
            array_push($adicion, ["nombre" => $line['name'], "price" => $price, "id" => $line['id']]);
        }
        
        array_push($simple, ["precio" => $precio, "simple" => true, "adicion" => $adicion]);
        echo json_encode($simple);
    }else{
        $datos = array();
        array_push($datos, ["datos" => "Por favor actualice el producto"]);
        echo json_encode($datos);
    }
    
?>