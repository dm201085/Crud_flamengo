<?php
require_once __DIR__ . '/../includes/config.php';
requireLogin();

$db = getDB();
$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    setFlash('danger', 'Registro não encontrado.');
    redirect('/pagamentos/index.php');
    exit;
}

$stmt = $db->prepare("SELECT * FROM formas_pagamento WHERE id = ?");
$stmt->execute([$id]);
$pagamento = $stmt->fetch();

if (!$pagamento) {
    setFlash('danger', 'Forma de pagamento não encontrada.');
    redirect('/pagamentos/index.php');
    exit;
}

$pageTitle = 'Editar Forma de Pagamento';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome      = sanitize($_POST['nome'] ?? '');
    $descricao = sanitize($_POST['descricao'] ?? '');
    $ativo     = isset($_POST['ativo']) ? true : false;

    if (empty($nome)) $errors['nome'] = 'Nome é obrigatório.';

    if (empty($errors)) {
        $stmt = $db->prepare("UPDATE formas_pagamento SET nome=?, descricao=?, ativo=? WHERE id=?");
        $stmt->execute([$nome, $descricao ?: null, $ativo, $id]);
        setFlash('success', "Forma de pagamento atualizada com sucesso!");
        redirect('/pagamentos/index.php');
        exit;
    }

    $pagamento = array_merge($pagamento, $_POST);
}

include __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
    <div>
        <h1>Editar Forma de Pagamento</h1>
        <p>Atualizar dados do método de pagamento</p>
    </div>
    <a href="<?= BASE_PATH ?>/pagamentos/index.php" class="btn btn-secondary">← Voltar</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="" data-validate>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? bin2hex(random_bytes(16)) ?>">

            <div class="form-group">
                <label for="nome">Nome da Forma de Pagamento *</label>
                <input type="text" id="nome" name="nome" class="form-control <?= isset($errors['nome']) ? 'is-invalid' : '' ?>"
                       value="<?= sanitize($pagamento['nome']) ?>" required>
                <?php if (isset($errors['nome'])): ?>
                    <span class="invalid-feedback"><?= $errors['nome'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" class="form-control"><?= sanitize($pagamento['descricao'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:0.6rem;cursor:pointer;font-weight:500">
                    <input type="checkbox" name="ativo" value="1" <?= $pagamento['ativo'] ? 'checked' : '' ?> style="width:18px;height:18px;accent-color:var(--red)">
                    Forma de pagamento ativa
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">Atualizar</button>
                <a href="<?= BASE_PATH ?>/pagamentos/index.php" class="btn btn-secondary btn-lg">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
