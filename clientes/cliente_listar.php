<?php
require '../verifica_sessao.php';
require '../conexao.php';



$busca = $_GET['busca'] ?? '';
$cidade = $_GET['cidade'] ?? '';
$vendedor = $_GET['vendedor'] ?? '';
$codCliente = $_GET['codCliente'] ?? '';

$sql = "
SELECT 
    c.*,
    v.nomeVendedor
FROM clientes c
LEFT JOIN vendedores v 
ON c.codVendedor = v.codVendedor
WHERE 1=1
";

$params = [];

/* FILTRO NOME */
if (!empty($busca)) {
    $sql .= " AND c.nomeCliente LIKE :busca";
    $params[':busca'] = "%$busca%";
}

/* FILTRO CIDADE */
if (!empty($cidade)) {
    $sql .= " AND c.cidadeCliente LIKE :cidade";
    $params[':cidade'] = "%$cidade%";
}

/* FILTRO VENDEDOR */
if (!empty($vendedor)) {
    $sql .= " AND v.nomeVendedor LIKE :vendedor";
    $params[':vendedor'] = "%$vendedor%";
}
if (!empty($codCliente)) {
    $sql .= " AND c.codCliente = :codCliente";
    $params[':codCliente'] = $codCliente;
}

$sql .= " ORDER BY c.nomeCliente";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* QUANTIDADE */
$totalClientes = count($clientes);
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Clientes</title>
    <link rel="stylesheet" href="/TCC/sistema/css/estilo.css">
    <link rel="icon" href="../Imagens/caixa.png">
</head>

<body>

    <?php include '../sistema/menu.php'; ?>

    <?php if (isset($_GET['inseridos'])): ?>

        <script>
            alert(
                "Importação concluída!\n\n" +
                "Clientes inseridos: <?= $_GET['inseridos'] ?>\n" +
                "Clientes atualizados: <?= $_GET['atualizados'] ?>\n" +
                "Linhas ignoradas: <?= $_GET['ignorados'] ?>"
            );
        </script>

    <?php endif; ?>



    <div class="conteudo">
        <h2>Clientes</h2>

        <form method="GET" class="filtro-clientes">

            <input
                type="text"
                name="codCliente"
                placeholder="Código do cliente"
                value="<?= htmlspecialchars($codCliente) ?>">

            <input
                type="text"
                name="busca"
                placeholder="Nome do cliente"
                value="<?= htmlspecialchars($busca) ?>">

            <input
                type="text"
                name="cidade"
                placeholder="Cidade"
                value="<?= htmlspecialchars($cidade) ?>">

            <input
                type="text"
                name="vendedor"
                placeholder="Vendedor"
                value="<?= htmlspecialchars($vendedor) ?>">

            <button type="submit">🔍 Filtrar</button>

            <a href="cliente_listar.php" class="btn-limpar">
                Limpar
            </a>

        </form>

        <div class="quantidade-clientes">
            Total encontrado: <strong><?= $totalClientes ?></strong> cliente(s)
        </div>


        <table border="1" width="100%" cellpadding="8">
            <tr>
                <th>Código</th>
                <th>Cliente</th>
                <th>Cidade</th>
                <th>Endereço</th>
                <th>Vendedor</th>
                <th>Ações</th>
            </tr>

            <?php foreach ($clientes as $c): ?>
                <tr>
                    <td><?= $c['codCliente'] ?></td>
                    <td><?= $c['nomeCliente'] ?></td>
                    <td><?= $c['cidadeCliente'] ?></td>
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