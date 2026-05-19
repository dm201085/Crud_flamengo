<?php
// ============================================
// index.php (Dashboard/Painel)
// Painel Principal — Flamengo System
// ============================================

require_once __DIR__ . '/includes/config.php';
requireLogin();

$db = getDB();
$pageTitle = 'Painel';

// ── CONTADORES ───────────────────────────────
$total_adversarios = $db->query("SELECT COUNT(*) FROM adversarios")->fetchColumn();
$total_ingressos   = $db->query("SELECT COUNT(*) FROM ingressos")->fetchColumn();
$total_pagamentos  = $db->query("SELECT COUNT(*) FROM pagamentos")->fetchColumn();
$total_usuarios    = $db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();

// ── PRÓXIMOS JOGOS ───────────────────────────
$proximos = $db->query("
    SELECT * FROM adversarios
    WHERE data_jogo >= CURDATE()
    ORDER BY data_jogo ASC
    LIMIT 3
")->fetchAll();

// ── ÚLTIMOS INGRESSOS ────────────────────────
$ultimos_ingressos = $db->query("
    SELECT i.*, a.nome_time, a.data_jogo
    FROM ingressos i
    JOIN adversarios a ON i.partida_id = a.id
    ORDER BY i.created_at DESC
    LIMIT 5
")->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<!-- ── BOAS-VINDAS ── -->
<div class="welcome-bar">
    <div class="welcome-text">
        <h1>Bem-vindo, <span><?= htmlspecialchars($_SESSION['user_nome'] ?? 'Admin') ?></span> 👋</h1>
        <p>Aqui está um resumo do sistema hoje, <?= date('d/m/Y') ?></p>
    </div>
    <div class="welcome-badge">
        <span>🏆</span>
        <span>Mengão Campeão</span>
    </div>
</div>

<!-- ── CARDS DE ESTATÍSTICAS ── -->
<div class="stats-grid">

    <div class="stat-card stat-red">
        <div class="stat-icon">⚽</div>
        <div class="stat-info">
            <span class="stat-number"><?= $total_adversarios ?></span>
            <span class="stat-label">Adversários</span>
        </div>
        <a href="<?= BASE_PATH ?>/adversarios/index.php" class="stat-link">Ver todos →</a>
    </div>

    <div class="stat-card stat-black">
        <div class="stat-icon">🎫</div>
        <div class="stat-info">
            <span class="stat-number"><?= $total_ingressos ?></span>
            <span class="stat-label">Ingressos</span>
        </div>
        <a href="<?= BASE_PATH ?>/ingressos/index.php" class="stat-link">Ver todos →</a>
    </div>

    <div class="stat-card stat-red">
        <div class="stat-icon">💳</div>
        <div class="stat-info">
            <span class="stat-number"><?= $total_pagamentos ?></span>
            <span class="stat-label">Formas de Pagamento</span>
        </div>
        <a href="<?= BASE_PATH ?>/pagamentos/index.php" class="stat-link">Ver todos →</a>
    </div>

    <div class="stat-card stat-black">
        <div class="stat-icon">👤</div>
        <div class="stat-info">
            <span class="stat-number"><?= $total_usuarios ?></span>
            <span class="stat-label">Usuários</span>
        </div>
        <a href="#" class="stat-link">Ver todos →</a>
    </div>

</div>

<!-- ── GRID INFERIOR ── -->
<div class="dashboard-grid">

    <!-- PRÓXIMOS JOGOS -->
    <div class="card">
        <div class="card-header">
            <h2>⚽ Próximos Jogos</h2>
            <a href="<?= BASE_PATH ?>/adversarios/create.php" class="btn btn-primary btn-sm">+ Novo</a>
        </div>
        <div class="card-body">
            <?php if (empty($proximos)): ?>
                <div class="empty-state">
                    <span>📅</span>
                    <p>Nenhum jogo agendado.</p>
                    <a href="<?= BASE_PATH ?>/adversarios/create.php" class="btn btn-primary btn-sm">Cadastrar jogo</a>
                </div>
            <?php else: ?>
                <div class="jogos-list">
                    <?php foreach ($proximos as $jogo): ?>
                        <div class="jogo-item">
                            <div class="jogo-escudo">🔴</div>
                            <div class="jogo-info">
                                <strong>Flamengo vs <?= htmlspecialchars($jogo['nome_time']) ?></strong>
                                <span><?= date('d/m/Y', strtotime($jogo['data_jogo'])) ?> às <?= substr($jogo['hora_jogo'], 0, 5) ?></span>
                                <span class="jogo-local <?= $jogo['local'] === 'casa' ? 'local-casa' : 'local-fora' ?>">
                                    <?= $jogo['local'] === 'casa' ? '🏠 Casa' : '✈️ Fora' ?>
                                </span>
                            </div>
                            <div class="jogo-campeonato">
                                <span><?= htmlspecialchars($jogo['campeonato']) ?></span>
                            </div>
                            <a href="<?= BASE_PATH ?>/adversarios/edit.php?id=<?= $jogo['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ÚLTIMOS INGRESSOS -->
    <div class="card">
        <div class="card-header">
            <h2>🎫 Últimos Ingressos</h2>
            <a href="<?= BASE_PATH ?>/ingressos/create.php" class="btn btn-primary btn-sm">+ Novo</a>
        </div>
        <div class="card-body">
            <?php if (empty($ultimos_ingressos)): ?>
                <div class="empty-state">
                    <span>🎫</span>
                    <p>Nenhum ingresso cadastrado.</p>
                    <a href="<?= BASE_PATH ?>/ingressos/create.php" class="btn btn-primary btn-sm">Cadastrar ingresso</a>
                </div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Jogo</th>
                            <th>Setor</th>
                            <th>Preço</th>
                            <th>Qtd</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimos_ingressos as $ingresso): ?>
                            <tr>
                                <td>
                                    <strong>vs <?= htmlspecialchars($ingresso['nome_time']) ?></strong><br>
                                    <small><?= date('d/m/Y', strtotime($ingresso['data_jogo'])) ?></small>
                                </td>
                                <td><?= htmlspecialchars($ingresso['setor']) ?></td>
                                <td>R$ <?= number_format($ingresso['preco'], 2, ',', '.') ?></td>
                                <td><?= $ingresso['quantidade'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- ── ATALHOS RÁPIDOS ── -->
<div class="card mt-4">
    <div class="card-header">
        <h2>⚡ Ações Rápidas</h2>
    </div>
    <div class="card-body">
        <div class="quick-actions">
            <a href="<?= BASE_PATH ?>/adversarios/create.php" class="quick-btn">
                <span>⚽</span>
                <strong>Novo Adversário</strong>
                <small>Cadastrar jogo</small>
            </a>
            <a href="<?= BASE_PATH ?>/ingressos/create.php" class="quick-btn">
                <span>🎫</span>
                <strong>Novo Ingresso</strong>
                <small>Vender ingresso</small>
            </a>
            <a href="<?= BASE_PATH ?>/pagamentos/create.php" class="quick-btn">
                <span>💳</span>
                <strong>Novo Pagamento</strong>
                <small>Forma de pagamento</small>
            </a>
            <a href="<?= BASE_PATH ?>/adversarios/index.php" class="quick-btn">
                <span>📋</span>
                <strong>Ver Jogos</strong>
                <small>Lista completa</small>
            </a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>