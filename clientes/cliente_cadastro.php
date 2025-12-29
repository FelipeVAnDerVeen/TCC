<?php
require '../verifica_sessao.php';
require '../conexao.php';

$vendedores = $pdo->query("SELECT codVendedor, nomeVendedor FROM vendedores ORDER BY nomeVendedor")
                  ->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="/TCC/sistema/css/estilo.css">
    <link rel="icon" href="../Imagens/caixa.png">
</head>
<body>

<?php include '../sistema/menu.php'; ?>

<div class="conteudo">
    <h2>Cadastrar Cliente</h2>

    <form action="cliente_salvar.php" method="POST">
        <input type="number" name="codCliente" placeholder="Código do Cliente" required>
        <input type="text" name="nomeCliente" placeholder="Nome do Cliente" required>
        <input type="text" name="cidadeCliente" placeholder="Cidade">
        <input type="text" name="enderecoCliente" placeholder="Endereço">

        <select name="codVendedor" required>
            <option value="">Selecione o Vendedor</option>
            <?php foreach ($vendedores as $v): ?>
                <option value="<?= $v['codVendedor'] ?>">
                    <?= $v['codVendedor'] ?> - <?= $v['nomeVendedor'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Salvar</button>
    </form>
</div>

</body>
</html>
