<?php
require_once __DIR__ . '/../includes/config.php';
requireLogin();

$db = getDB();
$pageTitle = 'Novo Adversário';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_time  = sanitize($_POST['nome_time'] ?? '');
    $estadio    = sanitize($_POST['estadio'] ?? '');
    $data_jogo  = $_POST['data_jogo'] ?? '';
    $hora_jogo  = $_POST['hora_jogo'] ?? '';
    $campeonato = sanitize($_POST['campeonato'] ?? '');
    $local      = $_POST['local'] ?? 'casa';

    // Validação
    if (empty($nome_time))  $errors['nome_time']  = 'Nome do time é obrigatório.';
    if (empty($data_jogo))  $errors['data_jogo']  = 'Data do jogo é obrigatória.';
    if (empty($hora_jogo))  $errors['hora_jogo']  = 'Hora do jogo é obrigatória.';
    if (empty($campeonato)) $errors['campeonato'] = 'Campeonato é obrigatório.';
    if (!in_array($local, ['casa', 'fora'])) $errors['local'] = 'Local inválido.';

    if (empty($errors)) {
        $stmt = $db->prepare("INSERT INTO adversarios (nome_time, estadio, data_jogo, hora_jogo, campeonato, local) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nome_time, $estadio ?: null, $data_jogo, $hora_jogo, $campeonato, $local]);
        setFlash('success', "Adversário '$nome_time' cadastrado com sucesso!");
        redirect('/adversarios/index.php');
        exit;
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
    <div>
        <h1>Novo Adversário</h1>
        <p>Cadastrar time a enfrentar</p>
    </div>
    <a href="<?= BASE_PATH ?>/adversarios/index.php" class="btn btn-secondary">← Voltar</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="" data-validate>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? bin2hex(random_bytes(16)) ?>">

            <!-- Linha 1: Nome do Time + Estádio -->
            <div class="form-row">
                <div class="form-group">
                    <label for="nome_time">Nome do Time *</label>
                    <input type="text" id="nome_time" name="nome_time"
                           class="form-control <?= isset($errors['nome_time']) ? 'is-invalid' : '' ?>"
                           placeholder="Ex: Vasco da Gama"
                           value="<?= sanitize($_POST['nome_time'] ?? '') ?>" required>
                    <?php if (isset($errors['nome_time'])): ?>
                        <span class="invalid-feedback"><?= $errors['nome_time'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="estadio">Estádio</label>
                    <input type="text" id="estadio" name="estadio"
                           class="form-control"
                           placeholder="Ex: Maracanã"
                           value="<?= sanitize($_POST['estadio'] ?? '') ?>">
                </div>
            </div>

            <!-- Linha 2: Data + Hora -->
            <div class="form-row">
                <div class="form-group">
                    <label for="data_jogo">Data do Jogo *</label>
                    <input type="date" id="data_jogo" name="data_jogo"
                           class="form-control <?= isset($errors['data_jogo']) ? 'is-invalid' : '' ?>"
                           value="<?= $_POST['data_jogo'] ?? '' ?>" required>
                    <?php if (isset($errors['data_jogo'])): ?>
                        <span class="invalid-feedback"><?= $errors['data_jogo'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="hora_jogo">Hora do Jogo *</label>
                    <input type="time" id="hora_jogo" name="hora_jogo"
                           class="form-control <?= isset($errors['hora_jogo']) ? 'is-invalid' : '' ?>"
                           value="<?= $_POST['hora_jogo'] ?? '' ?>" required>
                    <?php if (isset($errors['hora_jogo'])): ?>
                        <span class="invalid-feedback"><?= $errors['hora_jogo'] ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Linha 3: Campeonato + Local -->
            <div class="form-row">
                <div class="form-group">
                    <label for="campeonato">Campeonato *</label>
                    <input type="text" id="campeonato" name="campeonato"
                           class="form-control <?= isset($errors['campeonato']) ? 'is-invalid' : '' ?>"
                           placeholder="Ex: Brasileirão Série A"
                           value="<?= sanitize($_POST['campeonato'] ?? '') ?>" required>
                    <?php if (isset($errors['campeonato'])): ?>
                        <span class="invalid-feedback"><?= $errors['campeonato'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="local">Local do Jogo *</label>
                    <select id="local" name="local" class="form-control">
                        <option value="casa" <?= ($_POST['local'] ?? 'casa') === 'casa' ? 'selected' : '' ?>>
                            Casa (Maracanã)
                        </option>
                        <option value="fora" <?= ($_POST['local'] ?? '') === 'fora' ? 'selected' : '' ?>>
                            Fora
                        </option>
                    </select>
                </div>
            </div>

            <!-- Botões -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">Salvar Adversário</button>
                <a href="<?= BASE_PATH ?>/adversarios/index.php" class="btn btn-secondary btn-lg">Cancelar</a>
            </div>

        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>