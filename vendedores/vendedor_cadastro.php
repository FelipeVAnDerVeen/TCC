<?php require '../verifica_sessao.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Vendedor</title>
    <link rel="stylesheet" href="/TCC/sistema/css/estilo.css">
    <link rel="icon" href="../Imagens/caixa.png">
</head>
<body>

<?php include '../sistema/menu.php'; ?>

<div class="conteudo">
    <h2>Cadastrar Vendedor</h2>

    <form action="vendedor_salvar.php" method="POST">
        <input type="number" name="codVendedor" placeholder="Código do Vendedor" required>
        <input type="text" name="nomeVendedor" placeholder="Nome do Vendedor" required>
        <input type="text" name="telefoneVendedor" placeholder="Telefone" required>

        <button type="submit">Salvar</button>
    </form>
</div>

</body>
</html>
