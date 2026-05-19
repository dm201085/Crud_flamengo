<?php
require_once __DIR__ . '/../includes/config.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/adversarios/index.php');
    exit;
}

$id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

// Pegar id do referer ou do form action
if (!$id) {
    parse_str(parse_url($_SERVER['HTTP_REFERER'] ?? '', PHP_URL_QUERY) ?? '', $qs);
    $id = (int)($qs['id'] ?? 0);
}

// Tentar pegar id da URL atual
if (!$id && isset($_SERVER['QUERY_STRING'])) {
    parse_str($_SERVER['QUERY_STRING'], $qs);
    $id = (int)($qs['id'] ?? 0);
}

if (!$id) {
    setFlash('danger', 'Registro não encontrado.');
    redirect('/adversarios/index.php');
    exit;
}

$db = getDB();
$stmt = $db->prepare("SELECT nome_time FROM adversarios WHERE id = ?");
$stmt->execute([$id]);
$adversario = $stmt->fetch();

if ($adversario) {
    $db->prepare("DELETE FROM adversarios WHERE id = ?")->execute([$id]);
    setFlash('success', "Adversário '{$adversario['nome_time']}' excluído com sucesso.");
} else {
    setFlash('danger', 'Adversário não encontrado.');
}

redirect('/adversarios/index.php');
exit;
