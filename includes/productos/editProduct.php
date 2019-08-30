<?php 
	session_start();
	if(isset($_SESSION["usuario"])){

	$from = $_POST['from'];
	
		if($from == 'objetivo'){
			require '../conexion.php';
			$datos = json_decode($_POST['data']);
			
			foreach ($datos as $clave=>$valor){
				$id = $valor->id;
				$nombre = $valor->nombre;
				$categoria = $valor->categoria;
				$descripcion = $valor->descripcion;
				$habilitado = $valor->habilitado;
				$menuDiaAlmuerzo = $valor->menuDiaAlmuerzo;
				$menuDiaCena = $valor->menuDiaCena;
				$fuenteCarbohidrato = $valor->fuentes->{'fuenteCarbohidrato'};
				$fuenteProteina = $valor->fuentes->{'fuenteProteina'};

				if($habilitado){
					$habilitado = 'true';
				}else{
					$habilitado = 'false';
				}

				if($menuDiaAlmuerzo){
					$menuDiaAlmuerzo = 'true';
				}else{
					$menuDiaAlmuerzo = 'false';
				}

				if($menuDiaCena){
					$menuDiaCena = 'true';
				}else{
					$menuDiaCena = 'false';
				}

				$adicionalesText = $valor->adicionales;
				if($adicionalesText != 'false'){
					$adicionalesText = 'true';
				}

				$fecha = getdate();
		
				$year = $fecha['year'];
		        $dia = $fecha['mday'];
		        $mes = $fecha['mon'];

		        $hora = $fecha['hours'];
		        $minutos = $fecha['minutes'];
		        $segundos = $fecha['seconds'];

        		$fecha = $year . "-" . $mes . "-" .  $dia . " " . $hora . ":" . $minutos . ":" . $segundos;

				$sql = "UPDATE products SET category_id = '$categoria', name = '$nombre', description = '$descripcion', menu_of_the_day_lunch = '$menuDiaAlmuerzo', target_selected = 'true', size_selected = 'false', simple_product = 'false', addition_selected = '$adicionalesText', protein_source = '$fuenteProteina', carb_source = '$fuenteCarbohidrato', active = '$habilitado', menu_of_the_day_dinner = '$menuDiaCena' WHERE id = '$id'";
				$query = pg_query($sql);

				if($query){
					$sql = "DELETE FROM targets WHERE product_id = '$id'";
					$query = pg_query($sql);
					$sql = "DELETE FROM sizes WHERE product_id = '$id'";
					$query = pg_query($sql);

		
					$sql = "DELETE FROM additions WHERE product_id = '$id'";
					$query = pg_query($sql);

					$adicionales = $valor->adicionales;
					if($adicionales != 'false'){
						foreach ($adicionales as $key => $value) {
							$nombreAdicional = $value->nombreAdicional;
							$precioAdicional = $value->precio;

							$sql = "INSERT INTO additions (price, name, product_id, created_at) VALUES ('$precioAdicional', '$nombreAdicional', '$id', '$fecha')";
							$query = pg_query($sql);
						}
					
					}

					$protein_source = $valor->carbs->{'carbsFp'};
					$carb_source = $valor->carbs->{'carbsFc'};
					$salad = $valor->carbs->{'carbsEnsalada'};
					$calories = $valor->carbs->{'carbsCalorias'};
					$protein = $valor->carbs->{'carbsProt'};
					$carb = $valor->carbs->{'carbsCarb'};
					$fat = $valor->carbs->{'carbsFat'};
					$price = $valor->carbs->{'carbsPrecio'};


					$sql = "INSERT INTO targets (name, protein_source, carb_source, salad, calories, protein, carb, fat, price, product_id, created_at) VALUES ('0 Carbs', '$protein_source', '$carb_source', '$salad', '$calories', '$protein', '$carb', '$fat', '$price', '$id', '$fecha')";
					$query = pg_query($sql);

					$protein_source = $valor->qGrasa->{'qGrasaFp'};
					$carb_source = $valor->qGrasa->{'qGrasaFc'};
					$salad = $valor->qGrasa->{'qGrasaEnsalada'};
					$calories = $valor->qGrasa->{'qGrasaCalorias'};
					$protein = $valor->qGrasa->{'qGrasaProt'};
					$carb = $valor->qGrasa->{'qGrasaCarb'};
					$fat = $valor->qGrasa->{'qGrasaFat'};
					$price = $valor->qGrasa->{'qGrasaPrecio'};


					$sql = "INSERT INTO targets (name, protein_source, carb_source, salad, calories, protein, carb, fat, price, product_id, created_at) VALUES ('Quemar grasa', '$protein_source', '$carb_source', '$salad', '$calories', '$protein', '$carb', '$fat', '$price', '$id', '$fecha')";
					$query = pg_query($sql);

					$protein_source = $valor->mantener->{'mantenerFp'};
					$carb_source = $valor->mantener->{'mantenerFc'};
					$salad = $valor->mantener->{'mantenerEnsalada'};
					$calories = $valor->mantener->{'mantenerCalorias'};
					$protein = $valor->mantener->{'mantenerProt'};
					$carb = $valor->mantener->{'mantenerCarb'};
					$fat = $valor->mantener->{'mantenerFat'};
					$price = $valor->mantener->{'mantenerPrecio'};

					$sql = "INSERT INTO targets (name, protein_source, carb_source, salad, calories, protein, carb, fat, price, product_id, created_at) VALUES ('Mantener', '$protein_source', '$carb_source', '$salad', '$calories', '$protein', '$carb', '$fat', '$price', '$id', '$fecha')";
					$query = pg_query($sql);

					$protein_source = $valor->l->{'lFp'};
					$carb_source = $valor->l->{'lFc'};
					$salad = $valor->l->{'lEnsalada'};
					$calories = $valor->l->{'lCalorias'};
					$protein = $valor->l->{'lProt'};
					$carb = $valor->l->{'lCarb'};
					$fat = $valor->l->{'lFat'};
					$price = $valor->l->{'lPrecio'};

					$sql = "INSERT INTO targets (name, protein_source, carb_source, salad, calories, protein, carb, fat, price, product_id, created_at) VALUES ('L', '$protein_source', '$carb_source', '$salad', '$calories', '$protein', '$carb', '$fat', '$price', '$id', '$fecha')";
					$query = pg_query($sql);

					$protein_source = $valor->xl->{'xlFp'};
					$carb_source = $valor->xl->{'xlFc'};
					$salad = $valor->xl->{'xlEnsalada'};
					$calories = $valor->xl->{'xlCalorias'};
					$protein = $valor->xl->{'xlProt'};
					$carb = $valor->xl->{'xlCarb'};
					$fat = $valor->xl->{'xlFat'};
					$price = $valor->xl->{'xlPrecio'};

					$sql = "INSERT INTO targets (name, protein_source, carb_source, salad, calories, protein, carb, fat, price, product_id, created_at) VALUES ('XL', '$protein_source', '$carb_source', '$salad', '$calories', '$protein', '$carb', '$fat', '$price', '$id', '$fecha')";
					$query = pg_query($sql);

					$protein_source = $valor->xxl->{'xxlFp'};
					$carb_source = $valor->xxl->{'xxlFc'};
					$salad = $valor->xxl->{'xxlEnsalada'};
					$calories = $valor->xxl->{'xxlCalorias'};
					$protein = $valor->xxl->{'xxlProt'};
					$carb = $valor->xxl->{'xxlCarb'};
					$fat = $valor->xxl->{'xxlFat'};
					$price = $valor->xxl->{'xxlPrecio'};


					$sql = "INSERT INTO targets (name, protein_source, carb_source, salad, calories, protein, carb, fat, price, product_id, created_at) VALUES ('XXL', '$protein_source', '$carb_source', '$salad', '$calories', '$protein', '$carb', '$fat', '$price', '$id', '$fecha')";
					$query = pg_query($sql);
					
					echo "1";
				}else{
					echo "2";
				}
			}
		}
		if($from == 'size'){
			$datos = json_decode($_POST['data']);
			require '../conexion.php';
			foreach ($datos as $clave=>$valor){
				$id = $valor->id;
				$nombre = $valor->nombre;
				$categoria = $valor->categoria;
				$descripcion = $valor->descripcion;
				$habilitado = $valor->habilitado;
				$menuDiaAlmuerzo = $valor->menuDiaAlmuerzo;
				$menuDiaCena = $valor->menuDiaCena;

				if($habilitado){
					$habilitado = 'true';
				}else{
					$habilitado = 'false';
				}

				if($menuDiaAlmuerzo){
					$menuDiaAlmuerzo = 'true';
				}else{
					$menuDiaAlmuerzo = 'false';
				}

				if($menuDiaCena){
					$menuDiaCena = 'true';
				}else{
					$menuDiaCena = 'false';
				}

				$adicionalesText = $valor->adicionales;
				if($adicionalesText != 'false'){
					$adicionalesText = 'true';
				}

				$fecha = getdate();
		
				$year = $fecha['year'];
		        $dia = $fecha['mday'];
		        $mes = $fecha['mon'];

		        $hora = $fecha['hours'];
		        $minutos = $fecha['minutes'];
		        $segundos = $fecha['seconds'];

        		$fecha = $year . "-" . $mes . "-" .  $dia . " " . $hora . ":" . $minutos . ":" . $segundos;
        		$datosGrande = $valor->datosGrande;
        		$datosSmall = $valor->datosSmall;
        		$datosMediano = $valor->datosMediano;

				$sql = "UPDATE products SET category_id = '$categoria', name = '$nombre', description = '$descripcion', menu_of_the_day_lunch = '$menuDiaAlmuerzo', size_selected = 'true', target_selected = 'false', simple_product = 'false', addition_selected = '$adicionalesText', active = '$habilitado', menu_of_the_day_dinner = '$menuDiaCena' WHERE id = '$id'";
				$query = pg_query($sql);

				if($query){
					$sql = "DELETE FROM targets WHERE product_id = '$id'";
					$query = pg_query($sql);
					$sql = "DELETE FROM sizes WHERE product_id = '$id'";
					$query = pg_query($sql);

					$sql = "DELETE FROM additions WHERE product_id = '$id'";
					$query = pg_query($sql);

					$adicionales = $valor->adicionales;
					if($adicionales != 'false'){
						foreach ($adicionales as $key => $value) {
							$nombreAdicional = $value->nombreAdicional;
							$precioAdicional = $value->precio;

							$sql = "INSERT INTO additions (price, name, product_id, created_at) VALUES ('$precioAdicional', '$nombreAdicional', '$id', '$fecha')";
							$query = pg_query($sql);
						}	
					}

					if($datosSmall){
						$caloriasSmall = $valor->datosSmall->{'caloriasSmall'};
						$carbohidratoSmall = $valor->datosSmall->{'carbohidratoSmall'};
						$fatSmall = $valor->datosSmall->{'fatSmall'};
						$precioSmall = $valor->datosSmall->{'precioSmall'};
						$proteinasSmall = $valor->datosSmall->{'proteinasSmall'};

						
						$sql = "INSERT INTO sizes (name, calories, protein, carbs, price, selected, product_id, created_at) VALUES ('pequeño', '$caloriasSmall', '$proteinasSmall', '$carbohidratoSmall', '$precioSmall', 'true', '$id', '$fecha')";
						$query = pg_query($sql);

	        		}else{
	        			$sql = "INSERT INTO sizes (name, calories, protein, carbs, price, selected, product_id, created_at) VALUES ('pequeño', '0', '0', '0', '0', 'false', '$id', '$fecha')";
						$query = pg_query($sql);
	        		}
	        		if($datosMediano){
						$caloriasMediano = $valor->datosMediano->{'caloriasMediano'};
						$carbohidratoMediano = $valor->datosMediano->{'carbohidratoMediano'};
						$fatMediano = $valor->datosMediano->{'fatMediano'};
						$precioMediano = $valor->datosMediano->{'precioMediano'};
						$proteinasMediano = $valor->datosMediano->{'proteinasMediano'};
		
	        			$sql = "INSERT INTO sizes (name, calories, protein, carbs, price, selected, product_id, created_at) VALUES ('mediano', '$caloriasMediano', '$proteinasMediano', '$carbohidratoMediano', '$precioMediano', 'true', '$id', '$fecha')";
						$query = pg_query($sql);

	        		}else{
	        			$sql = "INSERT INTO sizes (name, calories, protein, carbs, price, selected, product_id, created_at) VALUES ('mediano', '0', '0', '0', '0', 'false', '$id', '$fecha')";
						$query = pg_query($sql);
					
	        		}
	        		if($datosGrande){
						$caloriasGrande = $valor->datosGrande->{'caloriasGrande'};
						$carbohidratoGrande = $valor->datosGrande->{'carbohidratoGrande'};
						$fatGrande = $valor->datosGrande->{'fatGrande'};
						$precioGrande = $valor->datosGrande->{'precioGrande'};
						$proteinasGrande = $valor->datosGrande->{'proteinasGrande'};

						$sql = "INSERT INTO sizes (name, calories, protein, carbs, price, selected, product_id, created_at) VALUES ('grande', '$caloriasGrande', '$proteinasGrande', '$carbohidratoGrande', '$precioGrande', 'true', '$id', '$fecha')";
						$query = pg_query($sql);
	        		}else{		
						$sql = "INSERT INTO sizes (name, calories, protein, carbs, price, selected, product_id, created_at) VALUES ('grande', '0', '0', '0', '0', 'false', '$id', '$fecha')";
						$query = pg_query($sql);
				
	      			}

					echo "1";
				}else{
					echo "2";
				}
			}		
		}
		if($from == 'simple'){
			$datos = json_decode($_POST['data']);
			require '../conexion.php';
			foreach ($datos as $clave=>$valor){
				$id = $valor->id;
				$categoria = $valor->categoria;
				$nombre = $valor->nombre;
				$descripcion = $valor->descripcion;
				$habilitado = $valor->habilitado;
				$menuDiaAlmuerzo = $valor->menuDiaAlmuerzo;
				$menuDiaCena = $valor->menuDiaCena;
				$precio = $valor->precio;

				if($habilitado){
					$habilitado = 'true';
				}else{
					$habilitado = 'false';
				}

				if($menuDiaAlmuerzo){
					$menuDiaAlmuerzo = 'true';
				}else{
					$menuDiaAlmuerzo = 'false';
				}

				if($menuDiaCena){
					$menuDiaCena = 'true';
				}else{
					$menuDiaCena = 'false';
				}

				$adicionalesText = $valor->adicionales;
				if($adicionalesText != 'false'){
					$adicionalesText = 'true';
				}

				$fecha = getdate();
		
				$year = $fecha['year'];
		        $dia = $fecha['mday'];
		        $mes = $fecha['mon'];

		        $hora = $fecha['hours'];
		        $minutos = $fecha['minutes'];
		        $segundos = $fecha['seconds'];

        		$fecha = $year . "-" . $mes . "-" .  $dia . " " . $hora . ":" . $minutos . ":" . $segundos;

				$sql = "UPDATE products SET category_id = '$categoria', name = '$nombre', description = '$descripcion', price = '$precio', menu_of_the_day_lunch = '$menuDiaAlmuerzo', simple_product = 'true', target_selected = 'false', size_selected = 'false', addition_selected = '$adicionalesText', active = '$habilitado', menu_of_the_day_dinner = '$menuDiaCena' WHERE id = '$id'";
				$query = pg_query($sql);

				if($query){
					$sql = "DELETE FROM targets WHERE product_id = '$id'";
					$query = pg_query($sql);
					$sql = "DELETE FROM sizes WHERE product_id = '$id'";
					$query = pg_query($sql);
					$sql = "DELETE FROM additions WHERE product_id = '$id'";
					$query = pg_query($sql);

					$adicionales = $valor->adicionales;
					if($adicionales != 'false'){
						foreach ($adicionales as $key => $value) {
							$nombreAdicional = $value->nombreAdicional;
							$precioAdicional = $value->precio;

							$sql = "INSERT INTO additions (price, name, product_id, created_at) VALUES ('$precioAdicional', '$nombreAdicional', '$id', '$fecha')";
							$query = pg_query($sql);
						}
					}
					echo "1";
				}else{
					echo "2";
				}
			}
		}
	
	} else { header("Location: ../../index"); }
?>