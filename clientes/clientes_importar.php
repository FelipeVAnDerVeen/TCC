<?php
require '../verifica_sessao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Importar Clientes</title>
    <link rel="stylesheet" href="/TCC/sistema/css/estilo.css" >
    <link rel="icon" href="../Imagens/caixa.png">
</head>
<body>

<?php include '../sistema/menu.php'; ?>

<div class="conteudo">
    <h2>Importar Clientes via Excel</h2>

    <a href="clientes_como_importar.php" class="btn-ajuda" target="_blank">
        Como importar clientes via Excel
    </a>

    <form action="clientes_importar_processar.php" 
          method="POST" 
          enctype="multipart/form-data">

        <input type="file" name="arquivo" accept=".xlsx,.xls" required>
        <br><br>

        <button type="submit">📥 Importar</button>
        <a href="cliente_listar.php">Cancelar</a>
    </form>
</div>

</body>
</html>
