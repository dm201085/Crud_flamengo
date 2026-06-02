<?php
include('../../protect.php');
include('../../conexao.php');

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $nome = $mysqli->real_escape_string($_POST['nome']);
    $estado = $mysqli->real_escape_string($_POST['estado']);

    if (empty($nome) || empty($estado)) {
        $mensagem = "Por favor, preencha todos os campos.";
    } else {
        $sql_code = "INSERT INTO adversarios (nome, estado) VALUES ('$nome', '$estado')";
        
        if ($mysqli->query($sql_code)) {
            header("Location: adversarios.php");
            exit();
        } else {
            $mensagem = "Erro ao cadastrar: " . $mysqli->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Adversário - CRF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-zinc-900 text-gray-100 font-sans min-h-screen p-6 md:p-10">

    <div class="max-w-md mx-auto">
        <a href="adversarios.php" class="text-red-500 hover:text-red-400 mb-6 inline-block font-semibold transition">
            <i class="fa-solid fa-arrow-left text-sm mr-1"></i> Cancelar e Voltar
        </a>

        <div class="bg-zinc-950 border border-zinc-800 p-6 rounded-xl shadow-xl">
            <h1 class="text-xl font-bold mb-6 text-white border-b border-zinc-800 pb-3">
                Cadastrar Novo <span class="text-red-600">Adversário</span>
            </h1>

            <?php if (!empty($mensagem)): ?>
                <div class="bg-red-950/40 border border-red-800 text-red-400 text-sm p-3 rounded mb-4 text-center">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Nome do Clube</label>
                    <input type="text" name="nome" placeholder="Ex: Vasco da Gama" required class="w-full bg-zinc-900 border border-zinc-700 rounded-lg p-2.5 text-white focus:border-red-600 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Estado (Sigla)</label>
                    <input type="text" name="estado" placeholder="Ex: RJ" maxlength="2" required class="w-full bg-zinc-900 border border-zinc-700 rounded-lg p-2.5 text-white focus:border-red-600 outline-none transition uppercase">
                </div>

                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg text-sm uppercase tracking-wider transition shadow-lg shadow-red-600/10">
                    Gravar no Banco
                </button>
            </form>
        </div>
    </div>

</body>
</html>