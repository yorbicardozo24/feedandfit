<?php

  session_start();

  if(isset($_SESSION["usuario"])){

    header("Location: pedidos.php");

  } else {

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="shortcut icon" href="includes/img/favicon.png" type="image/vnd.microsoft.icon">
  <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
  <link rel="stylesheet" href="includes/css/login.css"/>
  <link rel="stylesheet" href="includes/css/component.css"/>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <title>Login</title>
  <script type="text/javascript">
    function check(e) {
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla == 8) {
        return true;
    }
    //Tecla enter
    if (tecla == 13) {
        return true;
    }
    if (tecla == 64) {
        return true;
    }
    if (tecla == 46) {
        return true;
    }

    // Patron de entrada, en este caso solo acepta numeros y letras
    patron = /[A-Za-z0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}
  </script>
</head>
<body>
  <div class="container-form">
    <h1 class="title">Login</h1>
    <form class="form" action="includes/login/login.php" method="post" autocomplete="off">
      <div class="form__item">
        <label class="form__label" for="usuario">Username</label>
        <input class="form__input" type="text" name="usuario" id="usuario" minlength="5" onkeypress="return check(event)" required="required" autofocus/>
        <label class="form__label" for="password">Clave</label>
        <input class="form__input" type="password" name="password" id="password" minlength="6" required="required"/>
      </div>
      <input class="button-login" type="submit" value="Entrar"/>
    </form>
    <p class="p3"><a href="registrar.php" class="p3">Regístrar</a></p>
  </div>
</body>
</html>
<?php } ?>