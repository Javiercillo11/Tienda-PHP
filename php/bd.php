<?php

    //Uso la librería de PHPMailer para enviar correos
    use PHPMailer\PHPMailer\PHPMailer;

    function cargarCategorias(){
        try {
            $db = new PDO("mysql:dbname=Pirotecnia; host:localhost", "root","");
            $datos = "select codcat,nombre from CATEGORIAS;";

            $sql = $db->query($datos);

            if(!$sql){
                return false;
            }

            //compruebo si hay datos en la tabla
            if($sql->rowCount() == 0){
                return false;
            }
            return $sql;
            $db = null; // Cierro la conexion con la base de datos

        } catch (PDOException $e) {
            echo "Error con la base de datos: ".$e->getMessage();
        }
    }

    function cargarProdutosCategoria($codCat){
        try {
            
            $db = new PDO("mysql:dbname=Pirotecnia; host:localhost","root","");
            $prepare = $db->prepare("select * from PRODUCTOS where categoria = ?;");
            $prepare->execute(array($codCat));

            if(!$prepare){
                return false;
            }

            if($prepare->rowCount() == 0){
                return false;
            }

            return $prepare;
            $db = null; // Cierro la conexion con la base de datos

        } catch (PDOException $e) {
            echo "Error con la base de datos: " . $e->getMessage();
        }
    }

    function cargarCategoria($codCat){
        try {
            $db = new PDO("mysql:dbname=Pirotecnia; host:locahost", "root", "");

            $prepare = $db->prepare("select * from CATEGORIAS where codcat = ?;");
            $prepare->execute(array($codCat));

            if(!$prepare){
                return false;
            }

            if($prepare->rowCount() == 0){
                return false;
            }

            return $prepare;
            $db = null; // Cierro la conexion con la base de datos

        } catch (PDOException $e) {
            echo "Error con la base de datos: " . $e->getMessage();
        }
    }

    function comprobarUsuario($nombre, $clave){
        try {
            $db = new PDO("mysql:dbname=Pirotecnia; host:localhost","root","");

            $prepare = $db->prepare("select * from TIENDA where correo = ? and clave = ?;");
            $prepare->execute(array($nombre, $clave));

            if(!$prepare){
                return false;
            }

            if($prepare->rowCount() == 0){
                return false;
            }

            foreach ($prepare as $datos) {
                $user = $datos;
            }
            return $user;
            $db = null;

        } catch (PDOException $e) {
            echo "Error en la base de datos " . $e->getMessage();
        }
    }

    function cargarProductos($codigosProductos){
        try {
            $db = new PDO("mysql:dbname=Pirotecnia; host:locahost","root","");

            $queryText = implode(",", $codigosProductos);

            
            $consulta = ("select * from PRODUCTOS where CODPROD in($queryText);");
            $resultado = $db->query($consulta);

            if(!$resultado){
                return false;
            }

            if($resultado->rowCount() == 0){
                return false;
            }
            return $resultado;
            $db = null;

        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
        }
    }

    function insertarPedido($carrito,$codTenda){
        try {

            $db = new PDO("mysql:dbname=Pirotecnia; host:localhost","root","");
            //realizo una transaccion
            $db->beginTransaction();

            //obtengo la fecha en formato 
            $hora = date("Y-m-d H:i:s", time());

            $insert =$db->prepare( "insert into PEDIDOS values (null, ?,?,?);");

            $insert->execute(array($hora, 'PENDIENTE',$codTenda));

            if(!$insert){
                return false;
            }

            //cojo el último id del pedido
            $pedido = $db->lastInsertId();

            //inserto los datos en la tabla PRODUCTOSPEDIDOS
            foreach ($carrito as $producto => $cantidad) {
                $insertPedidoProd = $db->prepare("insert into PEDIDOSPRODUCTOS values (null,?,?,?);");
                $insertPedidoProd->execute(array($pedido, $producto,$cantidad));

                if(!$insertPedidoProd){
                    $db->rollBack();
                    return false;
                }
            }

            $db->commit();
            
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
        } 
    }

    //Funcion para confirmar el pedido y enviar un correo de confirmacion
    function confirmarPedido($direccionEnvio, $contenido, $asunto, $carrito){
        require "C:/wamp64/Composer/vendor/autoload.php";

        $mail = new PHPMailer();
        $mail->isSMTP();

        $mail->SMTPDebug = 0; //Lo pongo a 0 para que no me aparezcan errores.
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Host = "smtp.gmail.com";
        $mail->Ports = 587;

        //Introduzco mi correo de cuenta y contraseña 
        $mail->Username = "daniel.andres1998@gmail.com";
        $mail->Password = "ermfiqjclqktmioh"; //Contraseña de aplicación generada en configuracion

        $mail->SetFrom("daniel.andres1998@gmail.com","noreply@rocket_factory.pum");

        $mail->Subject = $asunto; //Aunto del correo
        $mail->MSgHTML($contenido); //contenido del correo

        $address = $direccionEnvio; //Correo al que le voy a enviar el email
        $mail->AddAddress($address,"Email destinatario");

        $enviado = $mail->Send();

        if(!$enviado){ //compruebo si el correo se ha enviado correctamente
            echo "<br><br><br>Error" . $mail->ErrorInfo . "<br><br><br>";
        }else{
            try{
                //Me conecto con la base de datos
                $db = new PDO("mysql:dbname=Pirotecnia; host:localhost", "root", "");
                //Realizo una transcacción
                $db->beginTransaction();
                $precioTotal = 0;

                foreach ($carrito as $producto => $unidades) {

                    //Realizo una consulta para obtener el precio del producto añadido al carrito
                    $consultaPrecio = $db->prepare("select precio from PRODUCTOS where codprod = ?;");
                    $consultaPrecio->execute(array($producto));

                    //Recorro el array para calcular el precio obtenido por la cantidad solicitada
                    foreach ($consultaPrecio as $precio) {  
                        $totalProd = $unidades * $precio["precio"];
                    }
                    //Almaceno en una variable las cantidades totales de los productos
                    $precioTotal += $totalProd;
                }

                //Actualizo el precio del usuario de la tienda
                $updatePrecio = $db->prepare("update TIENDA set saldo = saldo - ?;");
                $updatePrecio->execute(array($precioTotal));

                //Actualizo el estado de pedido de PENDIENTE a ENVIADO
                $update = "update PEDIDOS set enviado ='ENVIADO' where enviado = 'PENDIENTE'; ";
                $db->query($update);

                //Actualizo el stock de los productos una vez hecho el pedido
                foreach ($carrito as $producto => $unidades) {

                    $updateProd = $db->prepare("update PRODUCTOS set stock = stock - ? where codprod = ?;");
                    $updateProd->execute(array($unidades, $producto));
                   
                    //Si ocurre un fallo paro el bucle y cancelo el update
                    if(!$updateProd){
                        $db->rollBack();
                        break;
                    }  
                }

                //Si todo va bien confirmo los cambios
                $db->commit();

            }catch(PDOException $e){
                echo "Error con la base de datos: " . $e->getMessage();
            }
        }
    }
?> 