<?php
    session_start(); //Me uno a la sesion
    $_SESSION = array(); 
    session_destroy(); //elimiino la sesion
    //Destruyo la cookie
    setcookie(session_name(), 123, time() - 1);
    header("Location: ../login.php"); //vuevlo a la pagina de login
?>