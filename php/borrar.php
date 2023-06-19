<?php
    //me uno a la sesión
    session_start();
    //vacio el carrito de la compra
    $_SESSION["carrito"] = array();
    //elimino la variable de sesion carrito
    unset($_SESSION["carrito"]);
    //redirijo a la pagina de carrito
    header("Location: carrito.php");
?>