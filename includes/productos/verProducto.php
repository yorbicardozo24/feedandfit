<?php 
	$id = $_POST['data'];
	//CONEXION
	require '../conexion.php';

	$datos = array();

	if($id){
		$sql = "SELECT products.id, products.name as producto, products.description, categories.name as categoria, categories.id as idcategoria, TO_CHAR(products.created_at :: DATE, 'dd/mm/yyyy') AS fecha, products.target_selected, products.size_selected, products.protein_source, products.carb_source, products.simple_product, products.price, products.active, products.menu_of_the_day_lunch, products.menu_of_the_day_dinner FROM products INNER JOIN categories ON products.category_id = categories.id WHERE products.id = '$id'";
		$query = pg_query($sql);
		$row = pg_fetch_array($query, null, PGSQL_ASSOC);

		$nombre = $row['producto'];
		$descripcion = $row['description'];
		$categoria = $row['categoria'];
		$idCategoria = $row['idcategoria'];
		$fecha = $row['fecha'];
		$size_selected = $row['size_selected'];
		$target_selected = $row['target_selected'];
		$simpleProduct = $row['simple_product'];
		$fuenteProteina = $row['protein_source'];
		$fuenteCarbohidrato = $row['carb_source'];
		$precio = $row['price'];
		$active = $row['active'];
		$menu_of_the_day_lunch = $row['menu_of_the_day_lunch'];
		$menu_of_the_day_dinner = $row['menu_of_the_day_dinner'];
		$atributos = array();

		if($size_selected == 't' && $target_selected == 'f'){
			$tipo = "size";

			$sql = "SELECT * FROM sizes WHERE product_id = '$id'";
			$query = pg_query($sql);

			while ($line = pg_fetch_array($query, null, PGSQL_ASSOC)) {
				$name = $line['name'];
				$calorias = $line['calories'];
				$proteinas = $line['protein'];
				$carbohidratos = $line['carbs'];
				$fat = $line['fat'];
				$precio = $line['price'];

				array_push($atributos, ["nombre" => $name, "calorias" => $calorias, "proteinas" => $proteinas, "carbohidratos" => $carbohidratos, "fat" => $fat, "precio" => $precio]);
			}
		}else if($size_selected == 'f' && $target_selected == 't'){
			$tipo = "target";

			$sql = "SELECT * FROM targets WHERE product_id = '$id' ORDER BY id ASC";
			$query = pg_query($sql);

			while ($line = pg_fetch_array($query, null, PGSQL_ASSOC)) {
				$name = $line['name'];
				$protein_source = $line['protein_source'];
				$carb_source = $line['carb_source'];
				$salad = $line['salad'];
				$calories = $line['calories'];
				$protein = $line['protein'];
				$carb = $line['carb'];
				$fat = $line['fat'];
				$precio = $line['price'];

				array_push($atributos, ["nombre" => $name, "protein_source" => $protein_source, "carb_source" => $carb_source, "salad" => $salad, "calories" => $calories, "protein" => $protein, "carb" => $carb, "fat" => $fat, "precio" => $precio]);
			}
		}else if($size_selected == 'f' && $target_selected == 'f' && $simpleProduct == 't'){
			$tipo = "simple";
			$atributos = "";
		}else{
			$tipo = "";
		}

		$sql = "SELECT * FROM additions WHERE product_id = '$id'";
		$query = pg_query($sql);
		$additions = array();
		while ($line = pg_fetch_array($query, null, PGSQL_ASSOC)) {
			$name = $line['name'];
			$price = $line['price'];

			array_push($additions, ["nombre" => $name, "price" => $price]);
		}

		array_push($datos, ["id" => $id, "nombre" => $nombre, "descripcion" => $descripcion, "categoria" => $categoria, "idCategoria" => $idCategoria, "active" => $active, "menu_of_the_day_lunch" => $menu_of_the_day_lunch, "menu_of_the_day_dinner" => $menu_of_the_day_dinner, "fecha" => $fecha, "tipo" => $tipo, "precio" => $precio, "atributos" => $atributos, "fuenteProteina" => $fuenteProteina, "fuenteCarbohidrato" => $fuenteCarbohidrato, "additions" => $additions]);

		echo json_encode($datos);

	}
?>