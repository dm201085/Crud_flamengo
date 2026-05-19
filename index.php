<?php
// ============================================
// index.php
// Página Principal / Dashboard — Flamengo System
// ============================================

require_once __DIR__ . '/includes/config.php';
requireLogin();

$db        = getDB();
$pageTitle = 'Dashboard';

// ── CONTADORES ───────────────────────────────
$total_adversarios = $db->query("SELECT COUNT(*) FROM adversarios")->fetchColumn();
$total_ingressos   = $db->query("SELECT COUNT(*) FROM ingressos")->fetchColumn();
$total_pagamentos  = $db->query("SELECT COUNT(*) FROM pagamentos")->fetchColumn();
$total_usuarios    = $db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();

// ── PRÓXIMOS JOGOS ───────────────────────────
$proximos = $db->query("
    SELECT *
    FROM adversarios
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

// ── ÚLTIMAS FORMAS DE PAGAMENTO ──────────────
$ultimos_pagamentos = $db->query("
    SELECT *
    FROM pagamentos
    ORDER BY created_at DESC
    LIMIT 4
")->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<!-- ══════════════════════════════════════════
     BOAS-VINDAS
══════════════════════════════════════════ -->
<div class="welcome-bar">
    <div class="welcome-text">
        <h1>Bem-vindo, <span><?= htmlspecialchars($_SESSION['user_nome'] ?? 'Admin') ?></span> 👋</h1>
        <p>Painel de controle — <?= date('l, d \d\e F \d\e Y') ?></p>
    </div>
    <div class="welcome-badge">
        <span>🏆</span>
        <span>Mengão Campeão da Libertadores</span>
    </div>
</div>

<!-- ══════════════════════════════════════════
     MENSAGEM FLASH
══════════════════════════════════════════ -->
<?php if ($flash = getFlash()): ?>
    <div class="alert alert-<?= $flash['type'] ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>

<!-- ══════════════════════════════════════════
     CARDS DE ESTATÍSTICAS
══════════════════════════════════════════ -->
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

<!-- ══════════════════════════════════════════
     GRID PRINCIPAL
══════════════════════════════════════════ -->
<div class="dashboard-grid">

    <!-- ── PRÓXIMOS JOGOS ── -->
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
                    <a href="<?= BASE_PATH ?>/adversarios/create.php" class="btn btn-primary btn-sm">
                        Cadastrar jogo
                    </a>
                </div>
            <?php else: ?>
                <div class="jogos-list">
                    <?php foreach ($proximos as $jogo): ?>
                        <div class="jogo-item">
                            <div class="jogo-escudo">🔴</div>
                            <div class="jogo-info">
                                <strong>
                                    Flamengo vs <?= htmlspecialchars($jogo['nome_time']) ?>
                                </strong>
                                <span>
                                    <?= date('d/m/Y', strtotime($jogo['data_jogo'])) ?>
                                    às <?= substr($jogo['hora_jogo'], 0, 5) ?>
                                </span>
                                <span class="jogo-local <?= $jogo['local'] === 'casa' ? 'local-casa' : 'local-fora' ?>">
                                    <?= $jogo['local'] === 'casa' ? '🏠 Casa' : '✈️ Fora' ?>
                                </span>
                            </div>
                            <div class="jogo-campeonato">
                                <span><?= htmlspecialchars($jogo['campeonato']) ?></span>
                            </div>
                            <a href="<?= BASE_PATH ?>/adversarios/edit.php?id=<?= $jogo['id'] ?>"
                               class="btn btn-secondary btn-sm">Editar</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ── ÚLTIMOS INGRESSOS ── -->
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
                    <a href="<?= BASE_PATH ?>/ingressos/create.php" class="btn btn-primary btn-sm">
                        Cadastrar ingresso
                    </a>
                </div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Jogo</th>
                            <th>Setor</th>
                            <th>Preço</th>
                            <th>Qtd</th>
                            <th>Ação</th>
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
                                <td>
                                    <a href="<?= BASE_PATH ?>/ingressos/edit.php?id=<?= $ingresso['id'] ?>"
                                       class="btn btn-secondary btn-sm">Editar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- ══════════════════════════════════════════
     FORMAS DE PAGAMENTO
══════════════════════════════════════════ -->
<div class="card mt-4">
    <div class="card-header">
        <h2>💳 Formas de Pagamento Cadastradas</h2>
        <a href="<?= BASE_PATH ?>/pagamentos/create.php" class="btn btn-primary btn-sm">+ Nova</a>
    </div>
    <div class="card-body">
        <?php if (empty($ultimos_pagamentos)): ?>
            <div class="empty-state">
                <span>💳</span>
                <p>Nenhuma forma de pagamento cadastrada.</p>
                <a href="<?= BASE_PATH ?>/pagamentos/create.php" class="btn btn-primary btn-sm">
                    Cadastrar agora
                </a>
            </div>
        <?php else: ?>
            <div class="pagamentos-grid">
                <?php foreach ($ultimos_pagamentos as $pag): ?>
                    <div class="pagamento-item">
                        <div class="pagamento-icon">
                            <?php
                                $icones = [
                                    'credito' => '💳',
                                    'debito'  => '🏧',
                                    'pix'     => '⚡',
                                    'boleto'  => '📄',
                                ];
                                echo $icones[$pag['tipo']] ?? '💰';
                            ?>
                        </div>
                        <div class="pagamento-info">
                            <strong><?= htmlspecialchars($pag['nome']) ?></strong>
                            <span><?= ucfirst($pag['tipo']) ?></span>
                            <?php if ($pag['parcelas_max'] > 1): ?>
                                <small>até <?= $pag['parcelas_max'] ?>x</small>
                            <?php endif; ?>
                        </div>
                        <span class="pagamento-status <?= $pag['ativo'] ? 'status-ativo' : 'status-inativo' ?>">
                            <?= $pag['ativo'] ? 'Ativo' : 'Inativo' ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ══════════════════════════════════════════
     AÇÕES RÁPIDAS
══════════════════════════════════════════ -->
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

            <a href="<?= BASE_PATH ?>/adversarios/index.php" class="quick-btn">
                <span>📋</span>
                <strong>Ver Jogos</strong>
                <small>Lista completa</small>
            </a>

            <a href="<?= BASE_PATH ?>/ingressos/create.php" class="quick-btn">
                <span>🎫</span>
                <strong>Novo Ingresso</strong>
                <small>Cadastrar ingresso</small>
            </a>

            <a href="<?= BASE_PATH ?>/ingressos/index.php" class="quick-btn">
                <span>📊</span>
                <strong>Ver Ingressos</strong>
                <small>Lista completa</small>
            </a>

            <a href="<?= BASE_PATH ?>/pagamentos/create.php" class="quick-btn">
                <span>💳</span>
                <strong>Novo Pagamento</strong>
                <small>Forma de pagamento</small>
            </a>

            <a href="<?= BASE_PATH ?>/pagamentos/index.php" class="quick-btn">
                <span>💰</span>
                <strong>Ver Pagamentos</strong>
                <small>Lista completa</small>
            </a>

            <a href="<?= BASE_PATH ?>/auth/logout.php" class="quick-btn quick-btn-danger"
               onclick="return confirm('Deseja realmente sair?')">
                <span>🚪</span>
                <strong>Sair</strong>
                <small>Encerrar sessão</small>
            </a>

        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>