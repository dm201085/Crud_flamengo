<?php
include('protect.php');
include('conexao.php');

if (isset($_GET['id'])) {
    
    $id = intval($_GET['id']);
    
    $sql_code = "DELETE FROM ingressos WHERE id = '$id'";
    
    $mysqli->query($sql_code) or die("Falha ao deletar: " . $mysqli->error);
}

header("Location: ingressos.php");
exit();
?>