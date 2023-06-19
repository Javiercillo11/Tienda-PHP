<?php
    //Me uno a la sesion creada
    session_start();

    //Si he intentado acceder a la pagina sin hacer login me envia al login automaticamente
    if(!isset($_SESSION["usuario"])){
        header("Location: ../login?redirigido=true");
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/comun.css">
    <link rel="stylesheet" href="../css/productos.css">
    <link rel="stylesheet" href="../css/tablaEnvios.css">
    <title>Petardos</title>
</head>
<body>
    <div id="contenedor">
    <header>
        <p>ROCKET FACTORY</p>
    </header>
    <section id="principal">
        <nav>
            <span>Historial de Pedidos</span>
            <a href="logout.php" id="logout"><img id="desconectar" src="../img/logout"></a>
            <a href="carrito.php" id="carrito"><img src="../img/carrito-compra.png"></a>
            <a href="productos.php" id="atras"><img id="b" src="../img/back.png"></a>
        </nav>
        <?php
            //Me conecto con la base de datos para cargar los productos en la pagina
            try {
                $db = new PDO("mysql:dbname=Pirotecnia; host:localhost", "root","");

                $consulta = "select fecha, count(unidades) as 'PRODUCTOS VENDIDOS' from PEDIDOS PE, PEDIDOSPRODUCTOS PR where PR.pedido = PE.codped group by fecha;";
                $sql = $db->query($consulta);
                echo "<table>";
                echo "<tr>";
                echo "<th>FECHA DE PEDIDO</th>";
                echo "<th>PRODUCTOS VENDIDOS</th>";
                echo "</tr>";
                foreach ($sql as $datos) {
                    echo "<tr>";
                    echo "<td>" . $datos["fecha"] . "</td>";
                    echo "<td>" . $datos["PRODUCTOS VENDIDOS"] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";

            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        ?>
    </section>
    <footer>
        <div id="logo"></div>
        <div id="twitter"></div>
        <div id="facebook"></div>
        <div id="instagram"></div>
    </footer>
    </div>
</body>
</html>