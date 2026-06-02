<?php
include('../../protect.php');
include('../../conexao.php');

if (!isset($_GET['id'])) {
    header("Location: ingressos.php");
    exit();
}

$id = intval($_GET['id']);

$sql_buscar = "SELECT * FROM ingressos WHERE id = '$id'";
$query_buscar = $mysqli->query($sql_buscar) or die($mysqli->error);
$ingresso = $query_buscar->fetch_assoc();

if (!$ingresso) {
    header("Location: ingressos.php");
    exit();
}

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $partida = $mysqli->real_escape_string($_POST['partida']);
    $setor = $mysqli->real_escape_string($_POST['setor']);
    $preco = str_replace(',', '.', $_POST['preco']);
    $quantidade = intval($_POST['quantidade']);

    if (empty($partida) || empty($setor) || empty($preco) || empty($quantidade)) {
        $mensagem = "Preencha todos os campos.";
    } else {
        $sql_code = "UPDATE ingressos SET partida = '$partida', setor = '$setor', preco = '$preco', quantidade = '$quantidade' WHERE id = '$id'";
        
        if ($mysqli->query($sql_code)) {
            header("Location: ingressos.php");
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
    <title>Editar Ingresso - CRF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-zinc-900 text-gray-100 font-sans min-h-screen p-6 md:p-10">

    <div class="max-w-md mx-auto">
        <a href="ingressos.php" class="text-red-500 hover:text-red-400 mb-6 inline-block font-semibold transition">
            <i class="fa-solid fa-arrow-left text-sm mr-1"></i> Cancelar e Voltar
        </a>

        <div class="bg-zinc-950 border border-zinc-800 p-6 rounded-xl shadow-xl">
            <h1 class="text-xl font-bold mb-6 text-white border-b border-zinc-800 pb-3">
                Editar <span class="text-amber-500">Ingressos</span>
            </h1>

            <?php if (!empty($mensagem)): ?>
                <div class="bg-red-950/40 border border-red-800 text-red-400 text-sm p-3 rounded mb-4 text-center">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Partida</label>
                    <input type="text" name="partida" value="<?php echo htmlspecialchars($ingresso['partida']); ?>" required class="w-full bg-zinc-900 border border-zinc-700 rounded-lg p-2.5 text-white focus:border-red-600 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Setor</label>
                    <input type="text" name="setor" value="<?php echo htmlspecialchars($ingresso['setor']); ?>" required class="w-full bg-zinc-900 border border-zinc-700 rounded-lg p-2.5 text-white focus:border-red-600 outline-none transition">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Preço (R$)</label>
                        <input type="text" name="preco" value="<?php echo htmlspecialchars($ingresso['preco']); ?>" required class="w-full bg-zinc-900 border border-zinc-700 rounded-lg p-2.5 text-white focus:border-red-600 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Quantidade</label>
                        <input type="number" name="quantidade" value="<?php echo htmlspecialchars($ingresso['quantidade']); ?>" required class="w-full bg-zinc-900 border border-zinc-700 rounded-lg p-2.5 text-white focus:border-red-600 outline-none transition">
                    </div>
                </div>

                <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-4 rounded-lg text-sm uppercase tracking-wider transition shadow-lg shadow-amber-600/10 mt-4">
                    Salvar Alterações
                </button>
            </form>
        </div>
    </div>

</body>
</html>