<?php require '../verifica_sessao.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produto</title>
    <link rel="stylesheet" href="/TCC/sistema/css/estilo.css">
    <link rel="icon" href="../Imagens/caixa.png">
</head>
<body>

<?php include '../sistema/menu.php'; ?>

<div class="conteudo">
    <h2>Cadastrar Produto</h2>

    <form action="produto_salvar.php" method="POST">
        <input type="text" name="codProduto" placeholder="Código do Produto" required>
        <input type="text" name="nomeProduto" placeholder="Nome do Produto" required>
        <input type="number" name="quantidadeProduto" placeholder="Quantidade" required>
        <input type="number" step="0.01" name="pesoProduto" placeholder="Peso unitário" required>

        <button type="submit">Salvar</button>
    </form>
</div>

</body>
</html>
