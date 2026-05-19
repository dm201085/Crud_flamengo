<?php
// ============================================
// config/conexao.php
// Conexão com o banco de dados — Flamengo System
// ============================================

// ── CREDENCIAIS ──────────────────────────────
// O Replit fornece essas variáveis automaticamente.
// Se estiver rodando local, troque pelos seus dados.
$host     = getenv('MYSQL_HOST')     ?: 'localhost';
$usuario  = getenv('MYSQL_USER')     ?: 'root';
$senha    = getenv('MYSQL_PASSWORD') ?: '';
$banco    = getenv('MYSQL_DATABASE') ?: 'flamengo_system';
$porta    = getenv('MYSQL_PORT')     ?: '3306';

// ── CONEXÃO PDO ──────────────────────────────
try {
    $pdo = new PDO(
        "mysql:host=$host;port=$porta;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha,
        [
            // Lança exceções em caso de erro (segurança)
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,

            // Retorna resultados como array associativo por padrão
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

            // Desativa emulação de prepared statements (evita SQL Injection)
            PDO::ATTR_EMULATE_PREPARES   => false,

            // Mantém a conexão persistente (melhor performance)
            PDO::ATTR_PERSISTENT         => true,
        ]
    );

} catch (PDOException $e) {
    // Em produção nunca mostre o erro real — apenas logue
    error_log('[ERRO DB] ' . $e->getMessage());
    die(json_encode([
        'erro' => 'Não foi possível conectar ao banco de dados. Tente novamente mais tarde.'
    ]));
}