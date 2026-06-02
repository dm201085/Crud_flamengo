<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - CRF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-zinc-900 text-gray-100 font-sans min-h-screen flex flex-col md:flex-row">
    
    <aside class="w-full md:w-64 bg-black border-r border-red-700 flex flex-col justify-between p-4 shadow-xl">
        <div>
            <div class="flex items-center gap-3 mb-8 border-b border-zinc-800 pb-4 justify-center md:justify-start">
                <img src="img/campo.png" alt="Escudo Flamengo" class="w-12 h-12 object-contain">
                <div>
                    <h2 class="font-black text-xl tracking-wider text-red-600">FLAMENGO</h2>
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Painel Oficial</span>
                </div>
            </div>

            <nav class="space-y-2">
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-red-600 text-white font-semibold transition shadow-lg shadow-red-600/20">
                    <i class="fa-solid fa-chart-pie w-5"></i>
                    <span>Início</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-zinc-800 hover:text-red-500 transition font-medium">
                    <i class="fa-solid fa-user w-5"></i>
                    <span>Perfil</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-zinc-800 hover:text-red-500 transition font-medium">
                    <i class="fa-solid fa-gear w-5"></i>
                    <span>Configurações</span>
                </a>
            </nav>
        </div>

        <div class="mt-6 md:mt-0 pt-4 border-t border-zinc-800">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-red-950/40 hover:text-red-400 transition font-medium">
                <i class="fa-solid fa-right-from-bracket w-5"></i>
                <span>Sair</span>
            </a>
        </div>
    </aside>

    <main class="flex-1 p-6 md:p-10 flex flex-col justify-between">
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-zinc-800 pb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight">
                    Bem-vindo ao Painel, <span class="text-red-500"><?php echo isset($_SESSION['nome']) ? htmlspecialchars($_SESSION['nome']) : 'Visitante'; ?></span>!
                </h1>
                <p class="text-sm text-gray-400 mt-1">Manto Sagrado ativado. Gerencie suas opções abaixo.</p>
            </div>
            
            <div class="flex items-center gap-2 bg-zinc-800 px-4 py-2 rounded-full border border-red-600/30">
                <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></span>
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-300">Sessão Ativa</span>
            </div>
        </header>

       <section class="grid grid-cols-1 md:grid-cols-3 gap-6 my-8">
            <a href="adversarios.php" class="block bg-zinc-950 border-l-4 border-red-600 p-6 rounded-r-xl shadow-lg hover:bg-zinc-900 transition cursor-pointer">
                <h3 class="text-gray-400 font-medium text-sm uppercase">adversários</h3>
                <p class="text-sm font-bold mt-2 text-white">Acessar CRUD &rarr;</p>
            </a>
            
            <a href="ingressos.php" class="block bg-zinc-950 border-l-4 border-black p-6 rounded-r-xl shadow-lg border-y border-r border-zinc-800 hover:bg-zinc-900 transition cursor-pointer">
                <h3 class="text-gray-400 font-medium text-sm uppercase">Ingressos</h3>
                <p class="text-sm font-bold mt-2 text-red-500">Acessar CRUD &rarr;</p>
            </a>
            
           <a href="pagamentos.php" class="block bg-zinc-950 border-l-4 border-red-600 p-6 rounded-r-xl shadow-lg hover:bg-zinc-900 transition cursor-pointer">
                <h3 class="text-gray-400 font-medium text-sm uppercase">Pagamentos</h3>
                <p class="text-sm font-bold mt-2 text-white">Acessar CRUD &rarr;</p>
            </a>
        </section>

        <footer class="text-center text-xs text-gray-600 mt-auto pt-6 border-t border-zinc-800/50">
            &copy; <?php echo date('Y'); ?> Flamengo Dashboard. Desenvolvido com Raça, Amor e Paixão.
        </footer>
    </main>

</body>
</html>