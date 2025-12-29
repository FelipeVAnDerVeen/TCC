<?php
require '../verifica_sessao.php';
require '../conexao.php';

$cod = $_GET['cod'] ?? null;

if (!$cod) {
    header('Location: produto_listar.php');
    exit;
}

if ($_POST) {
    $sql = "
        UPDATE produtos 
        SET nomeProduto = :nome,
            quantidadeProduto = :quantidade,
            pesoProduto = :peso
        WHERE codProduto = :cod
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $_POST['nomeProduto'],
        ':quantidade' => $_POST['quantidadeProduto'],
        ':peso' => $_POST['pesoProduto'],
        ':cod' => $cod
    ]);

    header('Location: produto_listar.php');
    exit;
}

$sql = "SELECT * FROM produtos WHERE codProduto = :cod";
$stmt = $pdo->prepare($sql);
$stmt->execute([':cod' => $cod]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="/TCC/sistema/css/estilo.css">
</head>
<body>

<?php include '../sistema/menu.php'; ?>

<div class="conteudo">
    <h2>Editar Produto</h2>

    <form method="POST">
        <p>
            <label>Produto</label><br>
            <input type="text" name="nomeProduto" value="<?= $produto['nomeProduto'] ?>" required>
        </p>

        <p>
            <label>Quantidade</label><br>
            <input type="number" name="quantidadeProduto" value="<?= $produto['quantidadeProduto'] ?>" required>
        </p>

        <p>
            <label>Peso Unitário</label><br>
            <input type="number" step="0.01" name="pesoProduto" value="<?= $produto['pesoProduto'] ?>" required>
        </p>

        <button type="submit">💾 Salvar</button>
        <a href="produto_listar.php">Cancelar</a>
    </form>
</div>

</body>
</html>
