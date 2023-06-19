<?php
    session_start();
    //Incluyo el fichero de bases de datos
    include("bd.php");

    //COMPROBACIONES DE POSIBLES ERRORES ANTES DE CONFIRMAR LA COMPRAs
    try {
        $db = new PDO("mysql:dbname=Pirotecnia; host:localhost","root","");

        //variable que contendrá el precio total del pedido
        $precioTotal = 0;

        //VARIABLE DE ERROR PARA CONTROLAR LOS POSIBLES FALLOS
        $limite = false;

        //realizo una transaccion
        $db->beginTransaction();

        //compruebo que la cantidad de unidades de un producto no supere a la cantidad de stock y tambien compruebo que el precio del pedido no supere al saldo del usuario
        foreach ($_SESSION["carrito"] as $codigo => $unidades) {

            //hago una consulta para obtener la cantidad de stock del producto que se vaya a comprar
            $consulta = $db->prepare("select * from PRODUCTOS where codprod = ?;");
            $consulta->execute(array($codigo));

            //Almaceno en variables los datos que voy a necesitar
            foreach ($consulta as $datos) {
                //guardo en una variable la cantidad de stock del producto
                $stockProd = $datos["stock"];
                //Almaceno en otra variable el total del precio del producto
                $totalProd = $unidades * $datos["precio"];
            }

            $precioTotal += $totalProd; //guardo en una variable el precio total del pedido

            //COMPRUEBO QUE EL PRECIO DEL PEDIDO NO SUPERE AL SALDO DEL USUARIO NI LAS UNIDADES AL STOCK
            if($precioTotal > $_SESSION["usuario"]["saldo"] ||($unidades > $stockProd)){
                $db->rollBack();
                $limite = true;
                break;
            }
        }

        //Si se supera el límite del pedido se muestra un mensaje de error y se cancela el pedido
        if($limite == true){
           header("Location: carrito.php?limite=true");
        }else{
            //Si todo va bien el pedido se confirma
            insertarPedido($_SESSION["carrito"],$_SESSION["usuario"]["codtie"]);
            header("Location: confirmarPedido.php");
        }

    } catch (PDOException $e) {
        echo "Error en la base de datos: " . $e->getMessage();
    }
?>