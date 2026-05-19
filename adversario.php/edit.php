<?php
require_once __DIR__ . '/../includes/config.php';
requireLogin();

$db = getDB();
$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    setFlash('danger', 'Adversário não encontrado.');
    redirect('/adversarios/index.php');
    exit;
}

$stmt = $db->prepare("SELECT * FROM adversarios WHERE id = ?");
$stmt->execute([$id]);
$adversario = $stmt->fetch();

if (!$adversario) {
    setFlash('danger', 'Adversário não encontrado.');
    redirect('/adversarios/index.php');
    exit;
}

$pageTitle = 'Editar Adversário';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_time  = sanitize($_POST['nome_time'] ?? '');
    $estadio    = sanitize($_POST['estadio'] ?? '');
    $data_jogo  = $_POST['data_jogo'] ?? '';
    $hora_jogo  = $_POST['hora_jogo'] ?? '';
    $campeonato = sanitize($_POST['campeonato'] ?? '');
    $local      = $_POST['local'] ?? 'casa';

    if (empty($nome_time))  $errors['nome_time']  = 'Nome do time é obrigatório.';
    if (empty($data_jogo))  $errors['data_jogo']  = 'Data do jogo é obrigatória.';
    if (empty($hora_jogo))  $errors['hora_jogo']  = 'Hora do jogo é obrigatória.';
    if (empty($campeonato)) $errors['campeonato'] = 'Campeonato é obrigatório.';

    if (empty($errors)) {
        $stmt = $db->prepare("UPDATE adversarios SET nome_time=?, estadio=?, data_jogo=?, hora_jogo=?, campeonato=?, local=? WHERE id=?");
        $stmt->execute([$nome_time, $estadio ?: null, $data_jogo, $hora_jogo, $campeonato, $local, $id]);
        setFlash('success', "Adversário atualizado com sucesso!");
        redirect('/adversarios/index.php');
        exit;
    }

    // Manter valores do POST em caso de erro
    $adversario = array_merge($adversario, $_POST);
}

include __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
    <div>
        <h1>Editar Adversário</h1>
        <p>Atualizar dados do time</p>
    </div>
    <a href="<?= BASE_PATH ?>/adversarios/index.php" class="btn btn-secondary">← Voltar</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="" data-validate>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? bin2hex(random_bytes(16)) ?>">

            <div class="form-row">
                <div class="form-group">
                    <label for="nome_time">Nome do Time *</label>
                    <input type="text" id="nome_time" name="nome_time" class="form-control <?= isset($errors['nome_time']) ? 'is-invalid' : '' ?>"
                           value="<?= sanitize($adversario['nome_time']) ?>" required>
                    <?php if (isset($errors['nome_time'])): ?>
                        <span class="invalid-feedback"><?= $errors['nome_time'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="estadio">Estádio</label>
                    <input type="text" id="estadio" name="estadio" class="form-control"
                           value="<?= sanitize($adversario['estadio'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="data_jogo">Data do Jogo *</label>
                    <input type="date" id="data_jogo" name="data_jogo" class="form-control <?= isset($errors['data_jogo']) ? 'is-invalid' : '' ?>"
                           value="<?= is_string($adversario['data_jogo']) ? (strlen($adversario['data_jogo']) > 10 ? date('Y-m-d', strtotime($adversario['data_jogo'])) : $adversario['data_jogo']) : '' ?>" required>
                    <?php if (isset($errors['data_jogo'])): ?>
                        <span class="invalid-feedback"><?= $errors['data_jogo'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="hora_jogo">Hora do Jogo *</label>
                    <input type="time" id="hora_jogo" name="hora_jogo" class="form-control"
                           value="<?= substr($adversario['hora_jogo'], 0, 5) ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="campeonato">Campeonato *</label>
                    <input type="text" id="campeonato" name="campeonato" class="form-control <?= isset($errors['campeonato']) ? 'is-invalid' : '' ?>"
                           value="<?= sanitize($adversario['campeonato']) ?>" required>
                    <?php if (isset($errors['campeonato'])): ?>
                        <span class="invalid-feedback"><?= $errors['campeonato'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="local">Local do Jogo *</label>
                    <select id="local" name="local" class="form-control">
                        <option value="casa" <?= $adversario['local'] === 'casa' ? 'selected' : '' ?>>Casa (Maracanã)</option>
                        <option value="fora" <?= $adversario['local'] === 'fora' ? 'selected' : '' ?>>Fora</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">Atualizar</button>
                <a href="<?= BASE_PATH ?>/adversarios/index.php" class="btn btn-secondary btn-lg">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
