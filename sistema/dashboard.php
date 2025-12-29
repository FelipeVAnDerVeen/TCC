<?php
require 'verifica_sessao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Sistema</title>
    <link rel="stylesheet" href="/TCC/sistema/css/estilo.css">
    <link rel="icon" href="../Imagens/caixa.png">
</head>
<body>

<?php include 'menu.php'; ?>

<div class="dashboard">

    <h1>Bem-vindo, <?php echo $_SESSION['usuario']; ?> 👋</h1>
    <p>Selecione uma opção abaixo para começar</p>

    <div class="dashboard-cards">

        <a href="../produtos/produto_listar.php" class="card-dashboard card-estoque">
            <h2>📦 Estoque</h2>
            <span>Visualizar produtos em estoque</span>
        </a>

        <a href="../produtos/produto_cadastro.php" class="card-dashboard card-produtos">
            <h2>➕ Produtos</h2>
            <span>Cadastrar novos produtos</span>
        </a>

        <a href="../clientes/cliente_listar.php" class="card-dashboard card-clientes">
            <h2>👤 Clientes</h2>
            <span>Gerenciar clientes</span>
        </a>

        <a href="../vendedores/vendedor_listar.php" class="card-dashboard card-vendedores">
            <h2>👨‍💼 Vendedores</h2>
            <span>Gerenciar vendedores</span>
        </a>

        <a href="../motoristas/motorista_listar.php" class="card-dashboard card-motoristas">
            <h2>🚚 Motoristas</h2>
            <span>Gerenciar motoristas</span>
        </a>

        <a href="../relatorios/romaneio.php?pedido=<?= $p['codPedido'] ?>"target="_blank" class="card-dashboard card-pedidos">
            <h2>📄 Romaneio</h2>
            <span>Registrar saída de produtos</span>
        </a>

    </div>
</div>

</body>
</html>
