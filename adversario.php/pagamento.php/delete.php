<?php
require_once __DIR__ . '/../includes/config.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/pagamentos/index.php');
    exit;
}

$db = getDB();

parse_str($_SERVER['QUERY_STRING'] ?? '', $qs);
$id = (int)($_POST['id'] ?? $qs['id'] ?? 0);

if (!$id) {
    setFlash('danger', 'Registro não encontrado.');
    redirect('/pagamentos/index.php');
    exit;
}

$stmt = $db->prepare("SELECT nome FROM formas_pagamento WHERE id = ?");
$stmt->execute([$id]);
$pagamento = $stmt->fetch();

if ($pagamento) {
    $db->prepare("DELETE FROM formas_pagamento WHERE id = ?")->execute([$id]);
    setFlash('success', "Forma de pagamento '{$pagamento['nome']}' excluída com sucesso.");
} else {
    setFlash('danger', 'Forma de pagamento não encontrada.');
}

redirect('/pagamentos/index.php');
exit;
