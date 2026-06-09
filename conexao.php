<?php

$host = "db";
$usuario = "root";
$senha = "root";
$database = "ingresso";

$mysqli = new mysqli($host, $usuario, $senha, $database);

if ($mysqli->connect_errno) {
    die("Erro ao conectar ao banco: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");