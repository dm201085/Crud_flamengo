<?php

$host = "localhost";
$usuario = "root";
$senha = "root";
$database = "ingresso";

// Instancia a classe mysqli para criar a conexão (nota: aqui está passando uma senha vazia "" ao invés da variável $senha)
$mysqli = new mysqli($host, $usuario, "", $database);

// Verifica se o objeto retornou algum código de erro na tentativa de conexão
if ($mysqli->connect_errno) {
    die("Erro ao conectar ao banco: " . $mysqli->connect_error);
}

if (!$mysqli) {
    die("Erro na conexão: " . mysqli_connect_error());
}

// Configura o padrão de caracteres da conexão para evitar problemas com acentuação e emojis no banco de dados
$mysqli->set_charset("utf8mb4");