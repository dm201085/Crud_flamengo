<?php
session_start();

if (!isset($_SESSION['id'])) {
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Negado — CRF</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Barlow:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --vermelho:  #CC0000;
            --preto:     #0a0a0a;
            --ouro:      #C9A84C;
            --branco:    #F5F0E8;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Barlow', sans-serif;
            background-color: var(--preto);
            background-image: repeating-linear-gradient(
                45deg,
                rgba(204,0,0,0.03) 0px,
                rgba(204,0,0,0.03) 1px,
                transparent 1px,
                transparent 40px
            );
        }

        .box {
            text-align: center;
            padding: 60px 48px;
            background: rgba(20,20,20,0.9);
            border: 1px solid rgba(204,0,0,0.25);
            border-radius: 4px;
            max-width: 420px;
            width: 90%;
            animation: fadeUp 0.6s cubic-bezier(.22,1,.36,1) both;
            position: relative;
            overflow: hidden;
        }

        .box::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #990000, var(--ouro), #990000);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .icone {
            font-size: 52px;
            margin-bottom: 20px;
            display: block;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            color: var(--vermelho);
            margin-bottom: 12px;
        }

        p {
            font-size: 14px;
            color: rgba(245,240,232,0.4);
            font-weight: 300;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .btn {
            display: inline-block;
            padding: 13px 32px;
            background: linear-gradient(135deg, var(--vermelho), #990000);
            border-radius: 3px;
            color: #fff;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            transition: filter 0.2s, transform 0.15s;
            box-shadow: 0 4px 20px rgba(204,0,0,0.35);
        }

        .btn:hover {
            filter: brightness(1.15);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="box">
        <span class="icone">🔒</span>
        <h1>Acesso Negado</h1>
        <p>Você precisa estar autenticado para acessar esta página. Por favor, faça login para continuar.</p>
        <a href="index.php" class="btn">Ir para o Login</a>
    </div>
</body>
</html>
<?php
    exit();
}
?>