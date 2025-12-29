<?php
require '../verifica_sessao.php';
require '../conexao.php';

$busca = $_GET['busca'] ?? '';

$sql = "SELECT * FROM vendedores 
        WHERE codVendedor LIKE :busca 
           OR nomeVendedor LIKE :busca
        ORDER BY nomeVendedor";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':busca', "%$busca%");
$stmt->execute();
$vendedores = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Vendedores</title>
    <link rel="stylesheet" href="/TCC/sistema/css/estilo.css">
    <link rel="icon" href="../Imagens/caixa.png">
</head>
<body>

<?php include '../sistema/menu.php'; ?>

<div class="conteudo">
<h2>Vendedores</h2>

<form method="GET">
    <input name="busca" placeholder="Buscar vendedor">
    <button>🔍 Filtrar</button>

    <a href="vendedor_listar.php" style="margin-left:10px;">
        Limpar
    </a>
</form>

<table border="1" width="100%" cellpadding="8">
<tr>
    <th>Código</th>
    <th>Nome</th>
    <th>Telefone</th>
    <th>Ações</th>
</tr>

<?php foreach ($vendedores as $v): ?>
<tr>
    <td><?= $v['codVendedor'] ?></td>
    <td><?= $v['nomeVendedor'] ?></td>
    <td><?= $v['telefoneVendedor'] ?></td>
    <td>
        <a href="vendedor_editar.php?cod=<?= $v['codVendedor'] ?>">✏️ Editar</a> |
        <a href="vendedor_excluir.php?cod=<?= $v['codVendedor'] ?>">🗑️ Excluir</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
</div>
</body>
</html>
