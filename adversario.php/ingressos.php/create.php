<?php
require_once __DIR__ . '/../includes/config.php';
requireLogin();

$db = getDB();
$pageTitle = 'Novo Ingresso';
$errors = [];

$adversarios = $db->query("SELECT id, nome_time, data_jogo FROM adversarios ORDER BY data_jogo ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adversario_id        = (int)($_POST['adversario_id'] ?? 0);
    $tipo_ingresso        = sanitize($_POST['tipo_ingresso'] ?? '');
    $setor                = sanitize($_POST['setor'] ?? '');
    $preco                = $_POST['preco'] ?? '';
    $quantidade_disponivel = (int)($_POST['quantidade_disponivel'] ?? 0);

    if (!$adversario_id)        $errors['adversario_id']        = 'Selecione o jogo.';
    if (empty($tipo_ingresso))  $errors['tipo_ingresso']        = 'Tipo de ingresso é obrigatório.';
    if (empty($setor))          $errors['setor']                = 'Setor é obrigatório.';
    if (!is_numeric($preco) || $preco <= 0) $errors['preco']   = 'Informe um preço válido.';
    if ($quantidade_disponivel < 0)         $errors['quantidade_disponivel'] = 'Quantidade não pode ser negativa.';

    if (empty($errors)) {
        $stmt = $db->prepare("INSERT INTO ingressos (adversario_id, tipo_ingresso, setor, preco, quantidade_disponivel) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$adversario_id, $tipo_ingresso, $setor, (float)$preco, $quantidade_disponivel]);
        setFlash('success', "Ingresso '$tipo_ingresso - $setor' cadastrado com sucesso!");
        redirect('/ingressos/index.php');
        exit;
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
    <div>
        <h1>Novo Ingresso</h1>
        <p>Cadastrar tipo de ingresso para um jogo</p>
    </div>
    <a href="<?= BASE_PATH ?>/ingressos/index.php" class="btn btn-secondary">← Voltar</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="" data-validate>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? bin2hex(random_bytes(16)) ?>">

            <div class="form-row">
                <div class="form-group" style="grid-column: 1 / -1">
                    <label for="adversario_id">Jogo (Adversário) *</label>
                    <select id="adversario_id" name="adversario_id" class="form-control <?= isset($errors['adversario_id']) ? 'is-invalid' : '' ?>" required>
                        <option value="">Selecione o jogo...</option>
                        <?php foreach ($adversarios as $a): ?>
                            <option value="<?= $a['id'] ?>" <?= (int)($_POST['adversario_id'] ?? 0) === $a['id'] ? 'selected' : '' ?>>
                                Flamengo x <?= sanitize($a['nome_time']) ?> — <?= date('d/m/Y', strtotime($a['data_jogo'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['adversario_id'])): ?>
                        <span class="invalid-feedback"><?= $errors['adversario_id'] ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tipo_ingresso">Tipo de Ingresso *</label>
                    <select id="tipo_ingresso" name="tipo_ingresso" class="form-control <?= isset($errors['tipo_ingresso']) ? 'is-invalid' : '' ?>" required>
                        <option value="">Selecione...</option>
                        <?php foreach (['Inteira', 'Meia-entrada', 'Social', 'VIP', 'Visitante'] as $tipo): ?>
                            <option value="<?= $tipo ?>" <?= ($_POST['tipo_ingresso'] ?? '') === $tipo ? 'selected' : '' ?>><?= $tipo ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['tipo_ingresso'])): ?>
                        <span class="invalid-feedback"><?= $errors['tipo_ingresso'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="setor">Setor *</label>
                    <select id="setor" name="setor" class="form-control <?= isset($errors['setor']) ? 'is-invalid' : '' ?>" required>
                        <option value="">Selecione...</option>
                        <?php foreach (['Norte', 'Sul', 'Leste', 'Oeste', 'Camarote', 'Cadeira Especial'] as $setor): ?>
                            <option value="<?= $setor ?>" <?= ($_POST['setor'] ?? '') === $setor ? 'selected' : '' ?>><?= $setor ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['setor'])): ?>
                        <span class="invalid-feedback"><?= $errors['setor'] ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="preco">Preço (R$) *</label>
                    <input type="number" id="preco" name="preco" class="form-control <?= isset($errors['preco']) ? 'is-invalid' : '' ?>"
                           placeholder="0.00" step="0.01" min="0.01"
                           value="<?= htmlspecialchars($_POST['preco'] ?? '') ?>" required>
                    <?php if (isset($errors['preco'])): ?>
                        <span class="invalid-feedback"><?= $errors['preco'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="quantidade_disponivel">Quantidade Disponível *</label>
                    <input type="number" id="quantidade_disponivel" name="quantidade_disponivel" class="form-control <?= isset($errors['quantidade_disponivel']) ? 'is-invalid' : '' ?>"
                           placeholder="0" min="0"
                           value="<?= (int)($_POST['quantidade_disponivel'] ?? '') ?>" required>
                    <?php if (isset($errors['quantidade_disponivel'])): ?>
                        <span class="invalid-feedback"><?= $errors['quantidade_disponivel'] ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">Salvar Ingresso</button>
                <a href="<?= BASE_PATH ?>/ingressos/index.php" class="btn btn-secondary btn-lg">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
