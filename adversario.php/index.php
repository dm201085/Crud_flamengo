<?php
require_once __DIR__ . '/../includes/config.php';
requireLogin();

$db = getDB();
$pageTitle = 'Adversários';

$busca = sanitize($_GET['busca'] ?? '');
if ($busca) {
    $stmt = $db->prepare("SELECT * FROM adversarios WHERE nome_time ILIKE ? OR campeonato ILIKE ? ORDER BY data_jogo ASC");
    $stmt->execute(["%$busca%", "%$busca%"]);
} else {
    $stmt = $db->query("SELECT * FROM adversarios ORDER BY data_jogo ASC");
}
$adversarios = $stmt->fetchAll();

$flash = getFlash();
include __DIR__ . '/../includes/header.php';
?>

<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
<?php endif; ?>

<div class="page-header">
    <div>
        <h1>Adversários</h1>
        <p>Times que o Flamengo vai enfrentar</p>
    </div>
    <a href="<?= BASE_PATH ?>/adversarios/create.php" class="btn btn-primary">+ Novo Adversário</a>
</div>

<!-- Busca -->
<div class="card" style="margin-bottom:1.5rem">
    <div class="card-body" style="padding:1rem 1.5rem">
        <form method="GET" action="">
            <div style="display:flex;gap:0.75rem;align-items:center">
                <input type="text" name="busca" class="form-control" placeholder="Buscar por time ou campeonato..." value="<?= $busca ?>" style="max-width:400px">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <?php if ($busca): ?>
                    <a href="<?= BASE_PATH ?>/adversarios/index.php" class="btn btn-secondary">Limpar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding:0">
        <?php if (empty($adversarios)): ?>
            <div class="empty-state">
                <div class="empty-icon">🏟️</div>
                <h3><?= $busca ? 'Nenhum resultado encontrado' : 'Nenhum adversário cadastrado' ?></h3>
                <?php if (!$busca): ?>
                    <a href="<?= BASE_PATH ?>/adversarios/create.php" class="btn btn-primary" style="margin-top:1rem">Cadastrar primeiro adversário</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Time Adversário</th>
                        <th>Estádio</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Campeonato</th>
                        <th>Local</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($adversarios as $a): ?>
                    <tr>
                        <td style="color:var(--gray-400)"><?= $a['id'] ?></td>
                        <td><strong><?= sanitize($a['nome_time']) ?></strong></td>
                        <td><?= sanitize($a['estadio'] ?? '—') ?></td>
                        <td><?= date('d/m/Y', strtotime($a['data_jogo'])) ?></td>
                        <td><?= substr($a['hora_jogo'], 0, 5) ?></td>
                        <td><?= sanitize($a['campeonato']) ?></td>
                        <td>
                            <span class="badge <?= $a['local'] === 'casa' ? 'badge-success' : 'badge-info' ?>">
                                <?= $a['local'] === 'casa' ? 'Casa' : 'Fora' ?>
                            </span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="<?= BASE_PATH ?>/adversarios/edit.php?id=<?= $a['id'] ?>" class="btn btn-outline btn-sm">Editar</a>
                                <button onclick="confirmDelete(<?= $a['id'] ?>, '/adversarios/delete.php')" class="btn btn-danger btn-sm">Excluir</button>
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

<!-- Modal de Confirmação -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <h3>Confirmar Exclusão</h3>
        <p>Tem certeza que deseja excluir este adversário? Esta ação não pode ser desfeita.</p>
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
