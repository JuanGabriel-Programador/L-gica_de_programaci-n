<?php
$host="localhost";
$bd="kineobd";
$usuario="root";
$contrasenia="";

try {
    $conexion= new PDO("mysql:host=$host;dbname=$bd",$usuario,$contrasenia);
    echo ("Conexion existosa");
} catch (Exception $ex) {
    echo $ex->getMessage();
}
?>