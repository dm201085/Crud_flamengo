<?php
require_once __DIR__ . '/../includes/config.php';
requireLogin();

$db = getDB();
$pageTitle = 'Formas de Pagamento';

$busca = sanitize($_GET['busca'] ?? '');
if ($busca) {
    $stmt = $db->prepare("SELECT * FROM formas_pagamento WHERE nome ILIKE ? OR descricao ILIKE ? ORDER BY id DESC");
    $stmt->execute(["%$busca%", "%$busca%"]);
} else {
    $stmt = $db->query("SELECT * FROM formas_pagamento ORDER BY id DESC");
}
$pagamentos = $stmt->fetchAll();

$flash = getFlash();
include __DIR__ . '/../includes/header.php';
?>

<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
<?php endif; ?>

<div class="page-header">
    <div>
        <h1>Formas de Pagamento</h1>
        <p>Gerenciar métodos de pagamento aceitos</p>
    </div>
    <a href="<?= BASE_PATH ?>/pagamentos/create.php" class="btn btn-primary">+ Nova Forma</a>
</div>

<div class="card" style="margin-bottom:1.5rem">
    <div class="card-body" style="padding:1rem 1.5rem">
        <form method="GET" action="">
            <div style="display:flex;gap:0.75rem;align-items:center">
                <input type="text" name="busca" class="form-control" placeholder="Buscar por nome ou descrição..." value="<?= $busca ?>" style="max-width:400px">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <?php if ($busca): ?>
                    <a href="<?= BASE_PATH ?>/pagamentos/index.php" class="btn btn-secondary">Limpar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding:0">
        <?php if (empty($pagamentos)): ?>
            <div class="empty-state">
                <div class="empty-icon">💳</div>
                <h3><?= $busca ? 'Nenhum resultado encontrado' : 'Nenhuma forma de pagamento cadastrada' ?></h3>
                <?php if (!$busca): ?>
                    <a href="<?= BASE_PATH ?>/pagamentos/create.php" class="btn btn-primary" style="margin-top:1rem">Cadastrar forma de pagamento</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Status</th>
                        <th>Cadastrado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pagamentos as $p): ?>
                    <tr>
                        <td style="color:var(--gray-400)"><?= $p['id'] ?></td>
                        <td><strong><?= sanitize($p['nome']) ?></strong></td>
                        <td style="color:var(--gray-400);max-width:300px"><?= sanitize($p['descricao'] ?? '—') ?></td>
                        <td>
                            <span class="badge <?= $p['ativo'] ? 'badge-success' : 'badge-danger' ?>">
                                <?= $p['ativo'] ? 'Ativo' : 'Inativo' ?>
                            </span>
                        </td>
                        <td style="color:var(--gray-400)"><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                        <td>
                            <div class="table-actions">
                                <a href="<?= BASE_PATH ?>/pagamentos/edit.php?id=<?= $p['id'] ?>" class="btn btn-outline btn-sm">Editar</a>
                                <button onclick="confirmDelete(<?= $p['id'] ?>, '/pagamentos/delete.php')" class="btn btn-danger btn-sm">Excluir</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <h3>Confirmar Exclusão</h3>
        <p>Tem certeza que deseja excluir esta forma de pagamento?</p>
        <div class="modal-actions">
            <button onclick="closeModal()" class="btn btn-secondary">Cancelar</button>
            <form id="deleteForm" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <button type="submit" class="btn btn-danger">Sim, excluir</button>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
