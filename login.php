<?php

    include("php/bd.php"); // incluyo el fichero con las funciones de la base de datos
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $usu = comprobarUsuario($_POST["usuario"], $_POST["clave"]);
        if ($usu == FALSE){
            $err = TRUE;
            $usuario = $_POST["usuario"];
        } else {
            session_start();
            $_SESSION["usuario"] = $usu;
            header("Location: php/principal.php");
        }
    }   
?>

<html>
    <head>
        <title>Formulario de login</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
    <h1>ROCKET  FACTORY</h1>
    <div class="login-box">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="user-box">
            <input value="<?php if(isset($usuario)) echo $usuario;?>" id="usuario" name="usuario" type="text">
            <label for="user">EMAIL</label>
        </div>
            
        <div class="user-box">
            <input id="clave" name="clave" type="password">
            <label for="pass">CONTRASEÑA</label>    
        </div>

            <input id="submit" type="submit">
        </form>
    </div>
    </body>
    <?php
            if(isset($_GET["redirigido"])){
                echo "<p>Haga login para continuar</p>";
            }
        ?>
        <?php
            if(isset($err) and $err == true){
                echo "<p>Revise email y contraseña</p>";
            }
        ?>
</html>