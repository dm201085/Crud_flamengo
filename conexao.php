<?php

$usuario= "root";
$senha= "";
<<<<<<< HEAD
$database = "login";
=======
$database = "login.pw";
>>>>>>> 73c59a33c97dec2c77f0171986718a1dec948c03
$host = "localhost";

$mysqli = new mysqli($host, $usuario, $senha, $database);

if ($mysqli->error) {
    die("Falha ao conectar ao banco de dados: " . $mysqli->error);
}