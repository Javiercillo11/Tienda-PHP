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
    <link rel="stylesheet" href="../css/tablaProductos.css">
    <title>Petardos</title>
</head>
<body>
    <div id="contenedor">
    <header>
        <p>ROCKET FACTORY</p>
    </header>
    <section id="principal">
        <nav>
            <span><?php  
                try{
                    $db = new PDO("mysql:dbname=Pirotecnia; host:localhost", "root","");

                    $prepare = $db->prepare("select nombre from CATEGORIAS where codcat = ? ;");

                    $prepare->execute(array($_GET["categoria"]));

                    foreach($prepare as $nombre){
                        echo $nombre["nombre"];
                    }

                }catch(PDOException $e){
                    echo "Error en la base de datos " . $e->getMessage();
                }
            ?></span>
            <a href="logout.php" id="logout"><img id="desconectar" src="../img/logout"></a>
            <a href="carrito.php" id="carrito"><img src="../img/carrito-compra.png"></a>
            <a href="productos.php" id="atras"><img id="b" src="../img/back.png"></a>
        </nav>
        <?php
            //Me conecto con la base de datos para cargar los productos en la pagina
            try {
                $db = new PDO("mysql:dbname=Pirotecnia; host:localhost", "root","");

                $consulta = "select * from PRODUCTOS where CATEGORIA = ".$_GET["categoria"].";";
                $sql = $db->query($consulta);
                echo "<table>";
                echo "<tr>";
                echo "<th>NOMBRE</th>";
                echo "<th>DESCRIPCION</th>";
                echo "<th>PESO</th>";
                echo "<th>STOCK</th>";
                echo "<th>PRECIO</th>";
                echo "<th>CANTIDAD</th>";
                echo "</tr>";
                foreach ($sql as $datos) {
                    echo "<tr>";
                    echo "<td>" . $datos["nombre"] . "</td>";
                    echo "<td>" . $datos["descripcion"] . "</td>";
                    echo "<td>" . $datos["peso"] . "</td>";
                    echo "<td>" . $datos["stock"] . " unidades</td>";
                    echo "<td align='center'>" . $datos["precio"] . "€</td>";
                    echo "<td>";
                    echo "<form action='aniadir.php' method=post>";
                    echo "<input type='number' name='cantidad' id='cantidad' min='0' max='". $datos["stock"] ."'>&nbsp; ";
                    echo "<input type='submit' value='Añadir Carrito' id='enviar'> <input type='hidden' name='codProd' id=codProd value=" . $datos["codprod"] . ">";
                    echo "</form>";
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