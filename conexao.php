<?php

$usuario = "root";
$senha = "";
$database = "login.pw";
$host = "localhost";


$mysqli = new mysqli($host, $usuario, $senha, $database);

if ($mysqli->connect_error) {
    die("Falha ao conectar ao banco de dados: " . $mysqli->connect_error);
}


$mysqli->set_charset("utf8mb4");