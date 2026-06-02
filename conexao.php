<?php

<<<<<<< HEAD
$usuario= "root";
$senha= "";
$database = "login";
=======
$usuario = "root";
$senha = "";
$database = "login.pw";
>>>>>>> fa98dce01801a73cdf21e3a5eca02b8f6fc5a24d
$host = "localhost";


$mysqli = new mysqli($host, $usuario, $senha, $database);

if ($mysqli->connect_error) {
    die("Falha ao conectar ao banco de dados: " . $mysqli->connect_error);
}


$mysqli->set_charset("utf8mb4");