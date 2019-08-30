<?php
session_start();
    if(isset($_SESSION["usuario"])){
        //ID DEL CLIENTE
        $id = $_POST['data'];
        //CONEXION
        require '../conexion.php';
        $from = $_POST['from'];
        if($from == 'ver'){

            $sql = "SELECT * FROM delivery_zones WHERE id = '$id'";
            $resultado = pg_query($sql);
            $fila = pg_fetch_array($resultado, null, PGSQL_ASSOC);
            
            $nombre = $fila['name'];
            $precio = $fila['price'];
            $activo = $fila['active'];

            $datos = array("nombre" => $nombre, "precio" => $precio, "activo" => $activo);
            echo json_encode($datos);
        }else if($from == 'editar'){
            //Recibo el array
            $datos = json_decode($_POST['data']);
            //SI EXISTE EL ARRAY
            if($datos){
                //ALMACENO LOS DATOS
                $idZona = $datos->{'id'};
                $checked = $datos->{'checked'};
                $nombre = $datos->{'nombre'};
                $precio = $datos->{'precio'};

                $sql = "UPDATE delivery_zones SET name = '$nombre', price = '$precio', active = '$checked' WHERE id = '$idZona'";
                $query = pg_query($sql);

                if($query){
                    echo "1";
                }
            }
        }
    } else { header("Location: ../../index"); }
?>