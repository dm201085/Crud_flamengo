<?php
include('../../protect.php');
include('../../conexao.php');

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);
$sql_buscar = "SELECT * FROM pagamentos WHERE id = '$id'";
$query_buscar = $mysqli->query($sql_buscar) or die($mysqli->error);
$pagamento = $query_buscar->fetch_assoc();

if (!$pagamento) {
    header("Location: index.php");
    exit();
}

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente = $mysqli->real_escape_string($_POST['cliente']);
    $valor = str_replace(',', '.', $_POST['valor']);
    $metodo = $mysqli->real_escape_string($_POST['metodo']);
    $status = $mysqli->real_escape_string($_POST['status']);
    $data_pagamento = $mysqli->real_escape_string($_POST['data_pagamento']);

    if (empty($cliente) || empty($valor) || empty($metodo) || empty($status) || empty($data_pagamento)) {
        $mensagem = "Preencha todos os campos.";
    } else {
        $sql_code = "UPDATE pagamentos SET cliente = '$cliente', valor = '$valor', metodo = '$metodo', status = '$status', data_pagamento = '$data_pagamento' WHERE id = '$id'";
        
        if ($mysqli->query($sql_code)) {
            header("Location: index.php");
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
    <title>Editar Pagamento - CRF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-zinc-900 text-gray-100 font-sans min-h-screen p-6 md:p-10">

    <div class="max-w-md mx-auto">
        <a href="index.php" class="text-red-500 hover:text-red-400 mb-6 inline-block font-semibold transition">
            <i class="fa-solid fa-arrow-left text-sm mr-1"></i> Cancelar e Voltar
        </a>

        <div class="bg-zinc-950 border border-zinc-800 p-6 rounded-xl shadow-xl">
            <h1 class="text-xl font-bold mb-6 text-white border-b border-zinc-800 pb-3">
                Editar <span class="text-amber-500">Pagamento</span>
            </h1>

            <?php if (!empty($mensagem)): ?>
                <div class="bg-red-950/40 border border-red-800 text-red-400 text-sm p-3 rounded mb-4 text-center">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Nome do Torcedor</label>
                    <input type="text" name="cliente" value="<?php echo htmlspecialchars($pagamento['cliente']); ?>" required class="w-full bg-zinc-900 border border-zinc-700 rounded-lg p-2.5 text-white focus:border-red-600 outline-none transition">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Valor (R$)</label>
                        <input type="text" name="valor" value="<?php echo htmlspecialchars($pagamento['valor']); ?>" required class="w-full bg-zinc-900 border border-zinc-700 rounded-lg p-2.5 text-white focus:border-red-600 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Data</label>
                        <input type="date" name="data_pagamento" value="<?php echo htmlspecialchars($pagamento['data_pagamento']); ?>" required class="w-full bg-zinc-900 border border-zinc-700 rounded-lg p-2.5 text-white focus:border-red-600 outline-none transition">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Método</label>
                        <select name="metodo" required class="w-full bg-zinc-900 border border-zinc-700 rounded-lg p-2.5 text-white focus:border-red-600 outline-none transition">
                            <option value="Pix" <?php if($pagamento['metodo'] == 'Pix') echo 'selected'; ?>>Pix</option>
                            <option value="Cartão de Crédito" <?php if($pagamento['metodo'] == 'Cartão de Crédito') echo 'selected'; ?>>Cartão de Crédito</option>
                            <option value="Boleto" <?php if($pagamento['metodo'] == 'Boleto') echo 'selected'; ?>>Boleto</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Status</label>
                        <select name="status" required class="w-full bg-zinc-900 border border-zinc-700 rounded-lg p-2.5 text-white focus:border-red-600 outline-none transition">
                            <option value="Aprovado" <?php if($pagamento['status'] == 'Aprovado') echo 'selected'; ?>>Aprovado</option>
                            <option value="Pendente" <?php if($pagamento['status'] == 'Pendente') echo 'selected'; ?>>Pendente</option>
                            <option value="Cancelado" <?php if($pagamento['status'] == 'Cancelado') echo 'selected'; ?>>Cancelado</option>
                        </select>
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