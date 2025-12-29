<?php
require '../verifica_sessao.php';
require '../conexao.php';

$clientes   = $pdo->query("SELECT codCliente, nomeCliente FROM clientes ORDER BY nomeCliente")->fetchAll();
$vendedores = $pdo->query("SELECT codVendedor, nomeVendedor FROM vendedores ORDER BY nomeVendedor")->fetchAll();
$motoristas = $pdo->query("SELECT codMotorista, nomeMotorista FROM motoristas ORDER BY nomeMotorista")->fetchAll();
$produtos   = $pdo->query("SELECT codProduto, nomeProduto, quantidadeProduto FROM produtos ORDER BY nomeProduto")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Novo Pedido</title>
    <link rel="stylesheet" href="/TCC/sistema/css/estilo.css">
    <link rel="icon" href="../Imagens/caixa.png">
</head>
<body>

<?php include '../sistema/menu.php'; ?>

<div class="conteudo">
<h2>Novo Pedido / Saída de Produtos</h2>

<form action="pedido_salvar.php" method="POST">

    <input type="number" name="codPedido" placeholder="Código do Pedido" required>

    <input type="text" name="numeroCarga" placeholder="Número da Carga" required>

    <select name="codCliente" required>
        <option value="">Cliente</option>
        <?php foreach ($clientes as $c): ?>
            <option value="<?= $c['codCliente'] ?>">
                <?= $c['codCliente'] ?> - <?= $c['nomeCliente'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="codVendedor" required>
        <option value="">Vendedor</option>
        <?php foreach ($vendedores as $v): ?>
            <option value="<?= $v['codVendedor'] ?>">
                <?= $v['codVendedor'] ?> - <?= $v['nomeVendedor'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="codMotorista" required>
        <option value="">Motorista</option>
        <?php foreach ($motoristas as $m): ?>
            <option value="<?= $m['codMotorista'] ?>">
                <?= $m['codMotorista'] ?> - <?= $m['nomeMotorista'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <h3>Produtos</h3>

    <?php foreach ($produtos as $p): ?>
        <div>
            <input type="checkbox" name="produtos[<?= $p['codProduto'] ?>]" value="<?= $p['codProduto'] ?>">
            <?= $p['nomeProduto'] ?> (Estoque: <?= $p['quantidadeProduto'] ?>)
            <input type="number" name="quantidades[<?= $p['codProduto'] ?>]" min="1" placeholder="Qtd">
        </div>
    <?php endforeach; ?>

    <br>
    <button type="submit">Registrar Pedido</button>
</form>
</div>

</body>
</html>
