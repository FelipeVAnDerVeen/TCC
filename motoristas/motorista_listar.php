<?php
require '../verifica_sessao.php';
require '../conexao.php';

$busca = $_GET['busca'] ?? '';

$sql = "SELECT * FROM motoristas
        WHERE codMotorista LIKE :busca
           OR nomeMotorista LIKE :busca
        ORDER BY nomeMotorista";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':busca', "%$busca%");
$stmt->execute();
$motoristas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Motoristas</title>
    <link rel="stylesheet" href="/TCC/sistema/css/estilo.css">
    <link rel="icon" href="../Imagens/caixa.png">
</head>
<body>

<?php include '../sistema/menu.php'; ?>

<div class="conteudo">
<h2>Motoristas</h2>

<form method="GET">
    <input name="busca" placeholder="Buscar motorista">
    <button>🔍 Filtrar</button>

    <a href="motorista_listar.php" style="margin-left:10px;">
        Limpar
    </a>
</form>

<table border="1" width="100%" cellpadding="8">
<tr>
    <th>Código</th>
    <th>Nome</th>
    <th>Placa</th>
    <th>Ações</th>
</tr>

<?php foreach ($motoristas as $m): ?>
<tr>
    <td><?= $m['codMotorista'] ?></td>
    <td><?= $m['nomeMotorista'] ?></td>
    <td><?= $m['placaMotorista'] ?></td>
    <td>
        <a href="motorista_editar.php?cod=<?= $m['codMotorista'] ?>">✏️ Editar</a> |
        <a href="motorista_excluir.php?cod=<?= $m['codMotorista'] ?>">🗑️ Excluir</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
</div>
</body>
</html>
