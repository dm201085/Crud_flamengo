<?php
session_start();
include('conexao.php');

$erro = "";

if (isset($_POST['email']) && isset($_POST['senha'])) {

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql_code = "SELECT id, nome, senha FROM usuarios WHERE email = ? LIMIT 1";
    
    if ($stmt = $mysqli->prepare($sql_code)) {
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $usuario = $result->fetch_assoc();

            if ($senha === $usuario['senha']) {
                
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];

                header("Location: painel.php");
                exit();
            } else {
                $erro = "E-mail ou senha incorretos.";
            }
        } else {
            $erro = "E-mail ou senha incorretos.";
        }
        
        $stmt->close();
    } else {
        $erro = "Falha na preparação da consulta.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clube de Regatas do Flamengo — Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Barlow:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
</head>

<body>

    <div class="bg"></div>

    <div class="card">

        <div class="logo-area">
            <div class="escudo"></div>

            <h1>
                Clube de Regatas<br>do Flamengo
                <span>Área Restrita</span>
            </h1>
        </div>

        <?php if (!empty($erro)): ?>
            <div class="erro">
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">

            <div class="campo">
                <label for="email">E-mail</label>
                <input 
                    type="email" 
                    id="email"
                    name="email"
                    placeholder="seu@email.com"
                    autocomplete="email"
                    required
                >
            </div>

            <div class="campo">
                <label for="senha">Senha</label>
                <input 
                    type="password"
                    id="senha"
                    name="senha"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" class="btn">
                Entrar
            </button>

        </form>

        <p class="rodape">
            © <?php echo date('Y'); ?> Clube de Regatas do Flamengo
        </p>

    </div>

</body>
</html>