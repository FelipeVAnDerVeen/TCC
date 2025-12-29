<?php require '../verifica_sessao.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Motorista</title>
    <link rel="stylesheet" href="/TCC/sistema/css/estilo.css">
    <link rel="icon" href="../Imagens/caixa.png">
</head>
<body>

<?php include '../sistema/menu.php'; ?>

<div class="conteudo">
    <h2>Cadastrar Motorista</h2>

    <form action="motorista_salvar.php" method="POST">
        <input type="number" name="codMotorista" placeholder="Código do Motorista" required>
        <input type="text" name="nomeMotorista" placeholder="Nome do Motorista" required>
        <input type="text" name="placaMotorista" placeholder="Placa do Veículo" required>

        <button type="submit">Salvar</button>
    </form>
</div>

</body>
</html>
