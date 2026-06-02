<?php

$usuario= "root";
$senha= "";
$database = "login";
$host = "localhost";


$mysqli = new mysqli($usuario, $senha, $database, $host);

if ($mysqli->connect_error) {
    die("Falha ao conectar ao banco de dados: " . $mysqli->connect_error);
}


$mysqli->set_charset("utf8mb4");