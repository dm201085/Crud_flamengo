<?php
include('protect.php');
include('conexao.php');

$sql_code = "SELECT * FROM pagamentos ORDER BY id DESC";
$sql_query = $mysqli->query($sql_code) or die("Falha na execução: " . $mysqli->error);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamentos - CRF</title>
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
                Histórico de <span class="text-red-600">Pagamentos</span>
            </h1>
            <a href="pagamentos_cadastrar.php" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition shadow-md shadow-red-600/10">
                <i class="fa-solid fa-plus mr-1"></i> Lançar Pagamento
            </a>
        </div>

        <div class="bg-zinc-950 border border-zinc-800 rounded-xl overflow-hidden shadow-xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-zinc-900 border-b border-zinc-800">
                        <th class="p-4 text-xs uppercase tracking-wider font-bold text-gray-400 w-16">ID</th>
                        <th class="p-4 text-xs uppercase tracking-wider font-bold text-gray-400">Torcedor/Cliente</th>
                        <th class="p-4 text-xs uppercase tracking-wider font-bold text-gray-400">Valor (R$)</th>
                        <th class="p-4 text-xs uppercase tracking-wider font-bold text-gray-400">Método</th>
                        <th class="p-4 text-xs uppercase tracking-wider font-bold text-gray-400">Status</th>
                        <th class="p-4 text-xs uppercase tracking-wider font-bold text-gray-400">Data</th>
                        <th class="p-4 text-xs uppercase tracking-wider font-bold text-gray-400 text-center w-28">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/50">
                    <?php if ($sql_query->num_rows == 0): ?>
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500 text-sm">
                                Nenhum pagamento registrado.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php while ($pagamento = $sql_query->fetch_assoc()): ?>
                            <tr class="hover:bg-zinc-900/40 transition">
                                <td class="p-4 text-sm text-gray-500">#<?php echo $pagamento['id']; ?></td>
                                <td class="p-4 text-sm font-semibold text-white"><?php echo htmlspecialchars($pagamento['cliente']); ?></td>
                                <td class="p-4 text-sm text-green-500 font-medium">R$ <?php echo number_format($pagamento['valor'], 2, ',', '.'); ?></td>
                                <td class="p-4 text-sm text-gray-300"><?php echo htmlspecialchars($pagamento['metodo']); ?></td>
                                <td class="p-4 text-sm">
                                    <?php 
                                        $status = htmlspecialchars($pagamento['status']);
                                        if($status == 'Aprovado') echo "<span class='text-green-400 font-bold'>$status</span>";
                                        elseif($status == 'Pendente') echo "<span class='text-amber-400 font-bold'>$status</span>";
                                        else echo "<span class='text-red-500 font-bold'>$status</span>";
                                    ?>
                                </td>
                                <td class="p-4 text-sm text-gray-400"><?php echo date('d/m/Y', strtotime($pagamento['data_pagamento'])); ?></td>
                                <td class="p-4 text-sm text-center space-x-3">
                                    <a href="pagamentos_editar.php?id=<?php echo $pagamento['id']; ?>" class="text-amber-500 hover:text-amber-400 transition" title="Editar">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="pagamentos_deletar.php?id=<?php echo $pagamento['id']; ?>" class="text-red-500 hover:text-red-400 transition" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este pagamento?');">
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