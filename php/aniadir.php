<?php
//creo una sesion para almacenar los datos enviados por el formulario de productos
session_start();

//Compruebo si está creado el carrito
if(isset($_SESSION["carrito"]) && isset($_SESSION["carrito"][$_POST["codProd"]])){
    //añado los productos al carrito de la compra
    $_SESSION["carrito"][$_POST["codProd"]] += (int) $_POST["cantidad"];
}else{
    //creo la variable carrito
    $_SESSION["carrito"][$_POST["codProd"]] = (int) $_POST["cantidad"];
}

//Me conecto a la base de datos para redirigir al usuario a la pagina que se encuentre en ese momento
try {
    $db = new PDO("mysql:dbname=Pirotecnia; host:localhost","root","");

    //Hago una consulta a la base de datos para obtener la categoria del producto que se haya añadido
    $select =$db->prepare("select * from productos where codprod = ?;");
    $select->execute(array($_POST["codProd"]));

    foreach ($select as $datos) {
        $categoria = $datos["categoria"];
    }

    //Cierro la conexion con la base de datos
    $db = null;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

//Redirijo al usuario a través de la variable obtenida que contiene el nombre de la categoria
header("Location: categoria.php?categoria=".$categoria);
?>