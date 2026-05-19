<?php
// ============================================
// auth/logout.php
// Logout do sistema — Flamengo System
// ============================================

require_once __DIR__ . '/../includes/config.php';

// ── SEGURANÇA ────────────────────────────────
// Garante que a sessão está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Só executa logout se o usuário estiver logado
if (!isset($_SESSION['user_id'])) {
    redirect('/login.php');
    exit;
}

// ── LOGOUT ───────────────────────────────────

// 1. Salva o nome antes de destruir a sessão
$nome = $_SESSION['user_nome'] ?? 'Usuário';

// 2. Limpa todas as variáveis da sessão
$_SESSION = [];

// 3. Destroi o cookie de sessão do navegador
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// 4. Destroi a sessão no servidor
session_destroy();

// 5. Inicia nova sessão limpa para exibir a mensagem flash
session_start();
session_regenerate_id(true);

// 6. Mensagem de despedida
setFlash('success', "Até logo, $nome! Você saiu do sistema com segurança. 👋");

// 7. Redireciona para o login
redirect('/login.php');
exit;