<?php
require_once __DIR__ . '/../includes/config.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/ingressos/index.php');
    exit;
}

$db = getDB();

// Extrair ID da URL do form action
$id = (int)($_POST['id'] ?? 0);
if (!$id && isset($_SERVER['HTTP_REFERER'])) {
    // tentar extrair da ação do form via query string
}

// Método alternativo: pegar via query string
if (!$id) {
    parse_str($_SERVER['QUERY_STRING'] ?? '', $qs);
    $id = (int)($qs['id'] ?? 0);
}

if (!$id) {
    setFlash('danger', 'Ingresso não encontrado.');
    redirect('/ingressos/index.php');
    exit;
}

$stmt = $db->prepare("SELECT tipo_ingresso, setor FROM ingressos WHERE id = ?");
$stmt->execute([$id]);
$ingresso = $stmt->fetch();

if ($ingresso) {
    $db->prepare("DELETE FROM ingressos WHERE id = ?")->execute([$id]);
    setFlash('success', "Ingresso '{$ingresso['tipo_ingresso']} - {$ingresso['setor']}' excluído com sucesso.");
} else {
    setFlash('danger', 'Ingresso não encontrado.');
}

redirect('/ingressos/index.php');
exit;
