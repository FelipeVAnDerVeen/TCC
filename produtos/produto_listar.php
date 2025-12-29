<?php
require '../verifica_sessao.php';
require '../conexao.php';

$filtro = $_GET['busca'] ?? '';

$sql = "SELECT * FROM produtos 
        WHERE codProduto LIKE :busca 
           OR nomeProduto LIKE :busca
        ORDER BY nomeProduto";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':busca', "%$filtro%");
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Estoque</title>
    <link rel="stylesheet" href="/TCC/sistema/css/estilo.css">
    <link rel="icon" href="../Imagens/caixa.png">
</head>
<body>

<?php include '../sistema/menu.php'; ?>

<div class="conteudo">
    <h2>Estoque de Produtos</h2>

    <form method="GET" style="margin-bottom:15px;">
        <input 
            type="text" 
            name="busca" 
            placeholder="Buscar por código ou produto"
            value="<?= htmlspecialchars($filtro) ?>"
            style="padding:8px; width:250px;"
        >

        <button type="submit" style="padding:8px 12px;">
            🔍 Filtrar
        </button>

        <a href="produto_listar.php" style="margin-left:10px;">
            Limpar
        </a>
    </form>

    <table border="1" width="100%" cellpadding="8">
        <tr>
            <th>Código</th>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Peso Unitário</th>
            <th>Peso Total</th>
            <th>Ações</th>
        </tr>

        <?php foreach ($produtos as $p): ?>
        <tr>
            <td><?= $p['codProduto'] ?></td>
            <td><?= $p['nomeProduto'] ?></td>
            <td><?= $p['quantidadeProduto'] ?></td>
            <td><?= $p['pesoProduto'] ?></td>
            <td><?= $p['pesoProduto'] * $p['quantidadeProduto'] ?></td>
            <td>
                <a href="produto_editar.php?cod=<?= $p['codProduto'] ?>">
                    ✏️ Editar
                </a>
                |
                <a href="produto_excluir.php?cod=<?= $p['codProduto'] ?>"
                onclick="return confirm('Deseja excluir este produto?')">
                🗑️ Excluir
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
