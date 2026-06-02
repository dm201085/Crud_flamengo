<?php
include('protect.php');
include('conexao.php');

if (!isset($_GET['id'])) {
    header("Location: adversarios.php");
    exit();
}

$id = intval($_GET['id']);

$sql_buscar = "SELECT * FROM adversarios WHERE id = '$id'";
$query_buscar = $mysqli->query($sql_buscar) or die($mysqli->error);
$adversario = $query_buscar->fetch_assoc();

if (!$adversario) {
    header("Location: adversarios.php");
    exit();
}

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $mysqli->real_escape_string($_POST['nome']);
    $estado = $mysqli->real_escape_string($_POST['estado']);

    if (empty($nome) || empty($estado)) {
        $mensagem = "Preencha todos os campos.";
    } else {
        $sql_code = "UPDATE adversarios SET nome = '$nome', estado = '$estado' WHERE id = '$id'";
        
        if ($mysqli->query($sql_code)) {
            header("Location: adversarios.php");
            exit();
        } else {
            $mensagem = "Erro ao atualizar: " . $mysqli->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Adversário - CRF</title>
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
                Editar <span class="text-amber-500">Adversário</span>
            </h1>

            <?php if (!empty($mensagem)): ?>
                <div class="bg-red-950/40 border border-red-800 text-red-400 text-sm p-3 rounded mb-4 text-center">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Nome do Clube</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($adversario['nome']); ?>" required class="w-full bg-zinc-900 border border-zinc-700 rounded-lg p-2.5 text-white focus:border-red-600 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Estado (Sigla)</label>
                    <input type="text" name="estado" value="<?php echo htmlspecialchars($adversario['estado']); ?>" maxlength="2" required class="w-full bg-zinc-900 border border-zinc-700 rounded-lg p-2.5 text-white focus:border-red-600 outline-none transition uppercase">
                </div>

                <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-4 rounded-lg text-sm uppercase tracking-wider transition shadow-lg shadow-amber-600/10">
                    Salvar Alterações
                </button>
            </form>
        </div>
    </div>

</body>
</html>