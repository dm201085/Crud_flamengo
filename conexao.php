<?php

$host = "localhost";
$usuario = "root";
$senha = "";
$database = "ingresso";

try {
    $mysqli = new mysqli($host, $usuario, $senha, $database);

    if ($mysqli->connect_errno) {
        die("Erro ao conectar ao banco: " . $mysqli->connect_error);
    }

    $mysqli->set_charset("utf8mb4");

} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}