<?php
    session_start();
    include("bd.php");

    //Confirmo el pedido para que le llegue un correo de confirmación al usuario
    confirmarPedido("mena121997@gmail.com","Tu pedido se acaba de confirmar", "Resumen de Pedido",$_SESSION["carrito"]);

    //VACIO EL CARRITO
    $_SESSION["carrito"] = array();
    unset($_SESSION["carrito"]);

    header("Location:principal.php");
?>