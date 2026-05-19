<?php
require_once __DIR__ . '/../includes/config.php';
requireLogin();

$db = getDB();
$pageTitle = 'Ingressos';

$busca = sanitize($_GET['busca'] ?? '');
if ($busca) {
    $stmt = $db->prepare("SELECT i.*, a.nome_time FROM ingressos i LEFT JOIN adversarios a ON i.adversario_id = a.id WHERE i.tipo_ingresso ILIKE ? OR i.setor ILIKE ? OR a.nome_time ILIKE ? ORDER BY i.id DESC");
    $stmt->execute(["%$busca%", "%$busca%", "%$busca%"]);
} else {
    $stmt = $db->query("SELECT i.*, a.nome_time FROM ingressos i LEFT JOIN adversarios a ON i.adversario_id = a.id ORDER BY i.id DESC");
}
$ingressos = $stmt->fetchAll();

$flash = getFlash();
include __DIR__ . '/../includes/header.php';
?>

<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
<?php endif; ?>

<div class="page-header">
    <div>
        <h1>Ingressos</h1>
        <p>Gerenciar tipos de ingresso por jogo</p>
    </div>
    <a href="<?= BASE_PATH ?>/ingressos/create.php" class="btn btn-primary">+ Novo Ingresso</a>
</div>

<div class="card" style="margin-bottom:1.5rem">
    <div class="card-body" style="padding:1rem 1.5rem">
        <form method="GET" action="">
            <div style="display:flex;gap:0.75rem;align-items:center">
                <input type="text" name="busca" class="form-control" placeholder="Buscar por tipo, setor ou adversário..." value="<?= $busca ?>" style="max-width:400px">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <?php if ($busca): ?>
                    <a href="<?= BASE_PATH ?>/ingressos/index.php" class="btn btn-secondary">Limpar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding:0">
        <?php if (empty($ingressos)): ?>
            <div class="empty-state">
                <div class="empty-icon">🎫</div>
                <h3><?= $busca ? 'Nenhum resultado encontrado' : 'Nenhum ingresso cadastrado' ?></h3>
                <?php if (!$busca): ?>
                    <a href="<?= BASE_PATH ?>/ingressos/create.php" class="btn btn-primary" style="margin-top:1rem">Cadastrar ingresso</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Jogo</th>
                        <th>Tipo</th>
                        <th>Setor</th>
                        <th>Preço</th>
                        <th>Qtd. Disponível</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ingressos as $i): ?>
                    <tr>
                        <td style="color:var(--gray-400)"><?= $i['id'] ?></td>
                        <td><strong><?= sanitize($i['nome_time'] ?? 'Sem jogo') ?></strong></td>
                        <td>
                            <span class="badge badge-dark"><?= sanitize($i['tipo_ingresso']) ?></span>
                        </td>
                        <td><?= sanitize($i['setor']) ?></td>
                        <td><strong>R$ <?= number_format($i['preco'], 2, ',', '.') ?></strong></td>
                        <td>
                            <span class="badge <?= $i['quantidade_disponivel'] > 0 ? 'badge-success' : 'badge-danger' ?>">
                                <?= $i['quantidade_disponivel'] ?> ingressos
                            </span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="<?= BASE_PATH ?>/ingressos/edit.php?id=<?= $i['id'] ?>" class="btn btn-outline btn-sm">Editar</a>
                                <button onclick="confirmDelete(<?= $i['id'] ?>, '/ingressos/delete.php')" class="btn btn-danger btn-sm">Excluir</button>
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
        <p>Tem certeza que deseja excluir este ingresso? Esta ação não pode ser desfeita.</p>
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
