<?php
require '../verifica_sessao.php';
require '../conexao.php';

$cod = $_GET['cod'] ?? null;

if (!$cod) {
    header('Location: vendedor_listar.php');
    exit;
}

/* SALVAR ALTERAÇÃO */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $sql = "
        UPDATE vendedores SET
            nomeVendedor = :nome,
            telefoneVendedor = :telefone
        WHERE codVendedor = :cod
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome'     => $_POST['nomeVendedor'],
        ':telefone' => $_POST['telefoneVendedor'],
        ':cod'      => $cod
    ]);

    header('Location: vendedor_listar.php');
    exit;
}

/* BUSCAR VENDEDOR */
$sql = "SELECT * FROM vendedores WHERE codVendedor = :cod";
$stmt = $pdo->prepare($sql);
$stmt->execute([':cod' => $cod]);
$vendedor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vendedor) {
    header('Location: vendedor_listar.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Vendedor</title>
    <link rel="stylesheet" href="/TCC/sistema/css/estilo.css">
</head>
<body>

<?php include '../sistema/menu.php'; ?>

<div class="conteudo">
    <h2>Editar Vendedor</h2>

    <form method="POST">
        <label>Nome do Vendedor</label><br>
        <input type="text" name="nomeVendedor"
               value="<?= htmlspecialchars($vendedor['nomeVendedor']) ?>" required><br><br>

        <label>Telefone</label><br>
        <input type="text" name="telefoneVendedor"
               value="<?= htmlspecialchars($vendedor['telefoneVendedor']) ?>" required><br><br>

        <button type="submit">💾 Salvar</button>
        <a href="vendedor_listar.php">Cancelar</a>
    </form>
</div>

</body>
</html>
