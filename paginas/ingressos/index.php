<?php
include('protect.php');
include('conexao.php');

$sql_code = "SELECT * FROM ingressos ORDER BY id DESC";
$sql_query = $mysqli->query($sql_code) or die("Falha na execução: " . $mysqli->error);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingressos - CRF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-zinc-900 text-gray-100 font-sans min-h-screen p-6 md:p-10">

    <div class="max-w-5xl mx-auto">
        <a href="painel.php" class="text-red-500 hover:text-red-400 mb-6 inline-block font-semibold transition">
            <i class="fa-solid fa-arrow-left text-sm mr-1"></i> Voltar ao Painel
        </a>
        
        <div class="flex justify-between items-center mb-8 border-b border-zinc-800 pb-4">
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight">
                Gerenciar <span class="text-red-600">Ingressos</span>
            </h1>
            <a href="ingressos_cadastrar.php" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition shadow-md shadow-red-600/10">
                <i class="fa-solid fa-plus mr-1"></i> Novo Ingresso
            </a>
        </div>

        <div class="bg-zinc-950 border border-zinc-800 rounded-xl overflow-hidden shadow-xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-zinc-900 border-b border-zinc-800">
                        <th class="p-4 text-xs uppercase tracking-wider font-bold text-gray-400 w-16">ID</th>
                        <th class="p-4 text-xs uppercase tracking-wider font-bold text-gray-400">Partida</th>
                        <th class="p-4 text-xs uppercase tracking-wider font-bold text-gray-400">Setor</th>
                        <th class="p-4 text-xs uppercase tracking-wider font-bold text-gray-400">Preço (R$)</th>
                        <th class="p-4 text-xs uppercase tracking-wider font-bold text-gray-400">Qtd</th>
                        <th class="p-4 text-xs uppercase tracking-wider font-bold text-gray-400 text-center w-28">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/50">
                    <?php if ($sql_query->num_rows == 0): ?>
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500 text-sm">
                                Nenhum ingresso cadastrado ainda.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php while ($ingresso = $sql_query->fetch_assoc()): ?>
                            <tr class="hover:bg-zinc-900/40 transition">
                                <td class="p-4 text-sm text-gray-500">#<?php echo $ingresso['id']; ?></td>
                                <td class="p-4 text-sm font-semibold text-white"><?php echo htmlspecialchars($ingresso['partida']); ?></td>
                                <td class="p-4 text-sm text-gray-300"><?php echo htmlspecialchars($ingresso['setor']); ?></td>
                                <td class="p-4 text-sm text-green-500 font-medium">R$ <?php echo number_format($ingresso['preco'], 2, ',', '.'); ?></td>
                                <td class="p-4 text-sm text-gray-300"><?php echo $ingresso['quantidade']; ?></td>
                                <td class="p-4 text-sm text-center space-x-3">
                                    <a href="ingressos_editar.php?id=<?php echo $ingresso['id']; ?>" class="text-amber-500 hover:text-amber-400 transition" title="Editar">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="ingressos_deletar.php?id=<?php echo $ingresso['id']; ?>" class="text-red-500 hover:text-red-400 transition" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir os ingressos de <?php echo htmlspecialchars($ingresso['partida']); ?>?');">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>