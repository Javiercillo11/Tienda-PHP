<?php
    //Me uno a la sesion creada
    session_start();

    //Si he intentado acceder a la pagina sin hacer login me envia al login automaticamente
    if(!isset($_SESSION["usuario"])){
        header("Location: ../login?redirigido=true");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/comun.css">
    <link rel="stylesheet" href="../css/principal.css">
    <link rel="stylesheet" href="../css/cursor.css">
    <title>Rocket Factory</title>
</head>
<body>
    <div id="contenedor">
    <header>
        <p>ROCKET FACTORY</p>
    </header>
    <section id="principal">
        <nav>
            <span>Bienvenido <?php echo $_SESSION["usuario"]["correo"];?></span>
            <span>
            <?php
                $saldo = 0;
                try{
                    $db = new PDO("mysql:dbname=Pirotecnia; host:localhost", "root", "");

                    $consulta = "select saldo from tienda;";

                    $saldo = $db->query($consulta);

                    foreach ($saldo as $datos){
                        $saldo = $datos["saldo"];
                    }
                    echo "Saldo actual: " . $saldo . "€";
                }catch(PDOException $e){
                    echo "Error con la base de datos: " . $e->getMessage();
                }
            ?>
            </span>
            <a href="logout.php" id="logout"><img id="desconectar" src="../img/logout"></a>
            <a href="carrito.php" id="carrito"><img src="../img/carrito-compra.png"></a>
        </nav>
        <a href="productos.php" id="productos">Productos</a>
        <a href="envios.php" id="enviar">Lista Envios</a>
    </section>
    <footer>
        <div id="logo"></div>
        <div id="twitter"></div>
        <div id="facebook"></div>
        <div id="instagram"></div>
    </footer>
    </div>
    <script src="../js/index.js"></script>
</body>
</html>