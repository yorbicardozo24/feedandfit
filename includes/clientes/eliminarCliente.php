<?php 
    session_start();
    if(isset($_SESSION["usuario"])){
        require '../conexion.php';

        $id = $_GET['id'];

        $sql = "DELETE FROM addresses WHERE customer_id = '$id'";
        $query = pg_query($sql);

        $sql = "DELETE FROM customers WHERE id = '$id'";
        $query = pg_query($sql);
        

        if($query){ 
            header("Location: ../../clientes");
        }else{ 
            header("Location: ../../clientes");
        }

    } else { header("Location: ../../index"); }

?>