<?php
require '../verifica_sessao.php';
require '../conexao.php';

$cod = $_GET['cod'];

if ($_POST) {
    $sql = "
        UPDATE clientes SET
        nomeCliente = :nome,
        enderecoCliente = :endereco,
        cidadeCliente = :cidade,
        codVendedor = :vendedor
        WHERE codCliente = :cod
    ";

    $pdo->prepare($sql)->execute([
        ':nome' => $_POST['nomeCliente'],
        ':endereco' => $_POST['enderecoCliente'],
        ':cidade' => $_POST['cidadeCliente'],
        ':vendedor' => $_POST['codVendedor'],
        ':cod' => $cod
    ]);

    header('Location: cliente_listar.php');
}

$cliente = $pdo->query("SELECT * FROM clientes WHERE codCliente=$cod")->fetch();
$vendedores = $pdo->query("SELECT * FROM vendedores")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Editar Cliente</title>
<link rel="stylesheet" href="/TCC/sistema/css/estilo.css">
</head>
<body>

<?php include '../sistema/menu.php'; ?>

<div class="conteudo">
<h2>Editar Cliente</h2>

<form method="POST">
    <h3>Nome Cliente</h3>
    <input name="nomeCliente" value="<?= $cliente['nomeCliente'] ?>"><br>
    <h3>Endereço Cliente</h3>
    <input name="enderecoCliente" value="<?= $cliente['enderecoCliente'] ?>"><br>
    <h3>Cidade Cliente</h3>
    <input name="cidadeCliente" value="<?= $cliente['cidadeCliente'] ?>"><br>

    <h3>Vendedor Cliente</h3>
    <select name="codVendedor">
        <?php foreach ($vendedores as $v): ?>
        <option value="<?= $v['codVendedor'] ?>" 
            <?= $v['codVendedor']==$cliente['codVendedor']?'selected':'' ?>>
            <?= $v['nomeVendedor'] ?>
        </option>
        <?php endforeach; ?>
    </select><br><br>

    <button>💾 Salvar</button>
</form>
</div>
</body>
</html>
