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
    <link rel="stylesheet" href="../css/tablaCarrito.css">
    <link rel="stylesheet" href="../css/formularioPedido.css">
    <title>Rocket Factory</title>
</head>
<body>
    <div id="contenedor">
    <header>
        <p>Carro de la compra</p>
    </header>
    <section id="principal">
        <nav>
            <span>Carro de la compra</span>
            <a href="logout.php" id="logout"><img id="desconectar" src="../img/logout"></a>
            <a href="carrito.php" id="carrito"><img src="../img/carrito-compra.png"></a>
            <a href="productos.php" id="atras"><img id="b" src="../img/back.png"></a>
        </nav>
        <span id="mensaje">
            <?php
                //Compruebo si la variable carrito está inicializada para mostrar los productos escogidos o si ocurrio algun error en el proceso de pedido muestro mensaje
                if(isset($_GET["limite"])){
                    //borro el carrito de la compra
                    $_SESSION["carrito"] = array();
                    unset($_SESSION["carrito"]);
                    //muestro mensaje de advertencia
                    echo "Pedido no realizado, compruebe el carrito antes de comprar.";
                }else if(!isset($_SESSION["carrito"])){
                    echo "Carrito de la compra vacío añada productos para realizar el pedido.";
                }
            ?>
        </span>
        <?php
            //Importamos el fichero de funciones de la base de datos
            include("bd.php");
            if(isset($_SESSION["carrito"])){
                //Cargamos en una variable los productos del carrito
                $productosCarrito = cargarProductos(array_keys($_SESSION["carrito"]));

                $precioTotal = 0;

                echo "<span>RESUMEN DE COMPRA</span>";
                //muestro los pedidos en una tabla
                echo "<table>";
                echo "<tr>";
                echo "<th>NOMBRE</th>";
                echo "<th>CANTIDAD</th>"; 
                echo "<th>PRECIO</th>";
                echo "</tr>";

                foreach ($productosCarrito as $carritoPedido) {
                //Guardo en variables los datos de la consulta
                $codigo = $carritoPedido["codprod"];
                $nombre = $carritoPedido["nombre"];
                $descripcion = $carritoPedido["descripcion"];
                $peso = $carritoPedido["peso"];
                $precio = $carritoPedido["precio"];
                $cantidad = $_SESSION["carrito"][$codigo];
                $categoria = $carritoPedido["categoria"];
                $totalProd = $cantidad * $precio;
                $precioTotal += $totalProd;
    
                echo "<tr>";
                echo "<td>" . $nombre . "</td>";
                echo "<td>" . $cantidad . " unidades</td>";
                echo "<td>" . $totalProd . "€</td>";
                echo "</tr>";
                }

                echo "<tr>";
                echo "<td>Precio Total</td>";
                echo "<td colspan='2'>". $precioTotal . "€</td>";
                echo "</tr>";
                echo "</table>";
                echo "<form action='procesarPedido.php' method='post' id='formularioPedido'>";
                echo "<input type='submit' value='Confirmar Pedido' name='enviar' id='enviar'>";
                echo "</form>";
                //Segundo formulario para borrar el carrito de la compra
                echo "</table>";
                echo "<form action='borrar.php' method='post' id='formularioBorrar'>";
                echo "<input type='submit' value='Borrar Pedido' name='borrar' id='borrar'>";
                echo "</form>";
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