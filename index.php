<?php
session_start();
include('conexao.php');

$erro = "";

if (isset($_POST['email']) && isset($_POST['senha'])) {

    if (empty($_POST['email'])) {

        $erro = "Preencha seu e-mail.";

    } elseif (empty($_POST['senha'])) {

        $erro = "Preencha sua senha.";

    } else {

        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        $sql_code = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";

        $sql_query = $mysqli->query($sql_code) 
        or die("Falha na execução do código SQL: " . $mysqli->error);

        $quantidade = $sql_query->num_rows;

        if ($quantidade == 1) {

            $usuario = $sql_query->fetch_assoc();

            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];

            header("Location: painel.php");
            exit();

        } else {

            $erro = "E-mail ou senha incorretos.";

        }
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
                    type="text"
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