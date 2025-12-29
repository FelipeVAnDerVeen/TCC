<?php
require '../verifica_sessao.php';
require '../conexao.php';



$busca = $_GET['busca'] ?? '';

$sql = "
SELECT c.*, v.nomeVendedor
FROM clientes c
JOIN vendedores v ON c.codVendedor = v.codVendedor
WHERE c.codCliente LIKE :busca
   OR c.nomeCliente LIKE :busca
ORDER BY c.nomeCliente
";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':busca', "%$busca%");
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (isset($_GET['inseridos'])): ?>
    <p style="color:green;">
        ✔ <?= $_GET['inseridos'] ?> clientes inseridos,
        ✏ <?= $_GET['atualizados'] ?> atualizados,
        ⚠ <?= $_GET['ignorados'] ?> ignorados
    </p>
<?php endif; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Clientes</title>
    <link rel="stylesheet" href="/TCC/sistema/css/estilo.css">
    <link rel="icon" href="../Imagens/caixa.png">
</head>
<body>

<?php include '../sistema/menu.php'; ?>

<div class="conteudo">
<h2>Clientes</h2>

<form method="GET" style="margin-bottom:15px;">
    <input type="text" name="busca" placeholder="Buscar cliente" value="<?= $busca ?>">
    <button>🔍 Filtrar</button>

    <a href="cliente_listar.php" style="margin-left:10px;">
        Limpar
    </a>
</form>

<table border="1" width="100%" cellpadding="8">
<tr>
    <th>Código</th>
    <th>Cliente</th>
    <th>Endereço</th>
    <th>Vendedor</th>
    <th>Ações</th>
</tr>

<?php foreach ($clientes as $c): ?>
<tr>
    <td><?= $c['codCliente'] ?></td>
    <td><?= $c['nomeCliente'] ?></td>
    <td><?= $c['enderecoCliente'] ?></td>
    <td><?= $c['nomeVendedor'] ?></td>
    <td>
        <a href="cliente_editar.php?cod=<?= $c['codCliente'] ?>">✏️ Editar</a> |
        <a href="cliente_excluir.php?cod=<?= $c['codCliente'] ?>"
           onclick="return confirm('Excluir cliente?')">🗑️ Excluir</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
</div>

</body>
</html>
