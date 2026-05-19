<?php
// ============================================
// login.php
// Página de Login — Flamengo System
// ============================================

require_once __DIR__ . '/includes/config.php';

// Se já estiver logado, redireciona para o dashboard
if (isset($_SESSION['user_id'])) {
    redirect('/index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Validação dos campos
    if (empty($email)) $errors['email'] = 'E-mail é obrigatório.';
    if (empty($senha)) $errors['senha'] = 'Senha é obrigatória.';

    if (empty($errors)) {
        $db   = getDB();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            setFlash('success', 'Bem-vindo, ' . $user['nome'] . '!');
            redirect('/index.php');
            exit;
        } else {
            $errors['geral'] = 'E-mail ou senha incorretos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flamengo System — Login</title>

    <!-- Fontes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- CSS da página de login -->
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/login.css">
</head>
<body>

    <!-- Fundo com foto Flamengo Libertadores 2019 -->
    <div class="bg-image"></div>
    <div class="bg-overlay"></div>

    <div class="login-wrapper">

        <!-- CABEÇALHO / LOGO -->
        <div class="login-header">
            <div class="crest-ring">
                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M50 5 L90 20 L90 55 C90 75 70 92 50 98 C30 92 10 75 10 55 L10 20 Z" fill="#C8102E"/>
                    <path d="M50 5 L90 20 L90 55 C90 75 70 92 50 98 C30 92 10 75 10 55 L10 20 Z" stroke="#FFD700" stroke-width="3" fill="none"/>
                    <rect x="10" y="44" width="80" height="14" fill="#000"/>
                    <rect x="43" y="20" width="14" height="62" fill="#000"/>
                    <text x="50" y="42" text-anchor="middle" font-family="serif" font-size="13" font-weight="bold" fill="#FFD700">CRF</text>
                </svg>
            </div>
            <h1>FLAMENGO <span>SYSTEM</span></h1>
            <p>Painel de Gestão Oficial</p>
        </div>

        <!-- CARD DE LOGIN -->
        <div class="login-card">

            <?php if (!empty($errors['geral'])): ?>
                <div class="alert-error">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                    </svg>
                    <?= htmlspecialchars($errors['geral']) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="csrf_token"
                       value="<?= $_SESSION['csrf_token'] ?? bin2hex(random_bytes(16)) ?>">

                <!-- Campo E-mail -->
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="16" height="16" fill="rgba(255,255,255,0.6)" viewBox="0 0 16 16">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                            </svg>
                        </span>
                        <input type="email" id="email" name="email"
                               class="<?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                               placeholder="admin@flamengo.com"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               autocomplete="email" required>
                    </div>
                    <?php if (isset($errors['email'])): ?>
                        <span class="invalid-feedback"><?= $errors['email'] ?></span>
                    <?php endif; ?>
                </div>

                <!-- Campo Senha -->
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="16" height="16" fill="rgba(255,255,255,0.6)" viewBox="0 0 16 16">
                                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                            </svg>
                        </span>
                        <input type="password" id="senha" name="senha"
                               class="<?= isset($errors['senha']) ? 'is-invalid' : '' ?>"
                               placeholder="••••••••"
                               autocomplete="current-password" required>
                        <button type="button" class="toggle-senha"
                                onclick="toggleSenha()" aria-label="Mostrar senha">
                            <svg id="eye-icon" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                            </svg>
                        </button>
                    </div>
                    <?php if (isset($errors['senha'])): ?>
                        <span class="invalid-feedback"><?= $errors['senha'] ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn-login">Entrar no Sistema</button>
            </form>

            <!-- Badge Libertadores -->
            <div class="badge-liber">
                <span class="trophy">🏆</span>
                <span>Campeão Libertadores 2019</span>
                <span class="trophy">🏆</span>
            </div>

        </div><!-- /.login-card -->

        <div class="login-footer">
            <strong>Flamengo System</strong> &mdash; Todos os direitos reservados
        </div>

    </div><!-- /.login-wrapper -->

    <!-- JS da página de login -->
    <script src="<?= BASE_PATH ?>/assets/js/login.js"></script>

</body>
</html>