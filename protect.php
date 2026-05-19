<?php
// ============================================
// includes/protect.php
// Proteção de rotas — Flamengo System
// ============================================

require_once __DIR__ . '/config.php';

// ── FUNÇÕES DE PROTEÇÃO ──────────────────────

/**
 * Verifica se o usuário está logado.
 * Se não estiver, redireciona para o login.
 */
function requireLogin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        setFlash('error', 'Você precisa estar logado para acessar esta página.');
        redirect('/login.php');
        exit;
    }
}

/**
 * Verifica se o usuário é administrador.
 * Se não for, redireciona para o painel com erro.
 */
function requireAdmin() {
    requireLogin();

    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        setFlash('error', 'Acesso negado. Você não tem permissão para acessar esta área.');
        redirect('/index.php');
        exit;
    }
}

/**
 * Verifica se o usuário JÁ está logado.
 * Se estiver, redireciona para o painel (evita acessar login duas vezes).
 */
function redirectIfLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['user_id'])) {
        redirect('/index.php');
        exit;
    }
}

/**
 * Valida o token CSRF enviado pelo formulário.
 * Encerra a execução se o token for inválido.
 */
function validateCsrf() {
    if (
        !isset($_POST['csrf_token']) ||
        !isset($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        http_response_code(403);
        die('❌ Token CSRF inválido. Requisição bloqueada por segurança.');
    }
}

/**
 * Gera e armazena um novo token CSRF na sessão.
 * Deve ser chamado ao iniciar a sessão.
 */
function generateCsrf() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Verifica se a requisição é do tipo POST.
 * Útil para proteger ações de criar/editar/deletar.
 */
function requirePost() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        die('❌ Método não permitido.');
    }
}

/**
 * Verifica se a requisição veio de dentro do próprio sistema.
 * Proteção básica contra requisições externas (CSRF via referer).
 */
function requireReferer() {
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $host    = $_SERVER['HTTP_HOST']    ?? '';

    if (!str_contains($referer, $host)) {
        http_response_code(403);
        die('❌ Requisição bloqueada: origem não permitida.');
    }
}

/**
 * Registra tentativas suspeitas no log do servidor.
 * Chame quando detectar comportamento anormal.
 */
function logSuspect(string $motivo) {
    $ip   = $_SERVER['REMOTE_ADDR']  ?? 'desconhecido';
    $uri  = $_SERVER['REQUEST_URI']  ?? 'desconhecido';
    $user = $_SESSION['user_id']     ?? 'não logado';
    $data = date('d/m/Y H:i:s');

    error_log("[SUSPEITO] $data | IP: $ip | URI: $uri | Usuário: $user | Motivo: $motivo");
}