<?php
require '../verifica_sessao.php';
require '../conexao.php';

/* ATUALIZA STATUS */
if (isset($_POST['codPedido'], $_POST['status'])) {

    if ($_POST['status'] === 'Entregue') {
        $sql = "
            UPDATE pedidos 
            SET statusPedido = :status, dataEntrega = CURDATE()
            WHERE codPedido = :id
        ";
    } else {
        $sql = "
            UPDATE pedidos 
            SET statusPedido = :status, dataEntrega = NULL
            WHERE codPedido = :id
        ";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':status', $_POST['status']);
    $stmt->bindParam(':id', $_POST['codPedido']);
    $stmt->execute();
}

/* LISTA PEDIDOS */
$sql = "
SELECT 
    p.codPedido,
    p.dataPedido,
    p.statusPedido,
    p.dataEntrega,
    c.nomeCliente,
    c.codCliente,
    v.nomeVendedor,
    m.nomeMotorista
FROM pedidos p
JOIN clientes c ON p.codCliente = c.codCliente
JOIN vendedores v ON p.codVendedor = v.codVendedor
JOIN motoristas m ON p.codMotorista = m.codMotorista
ORDER BY p.dataPedido DESC
";

$pedidos = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pedidos</title>
    <link rel="stylesheet" href="/TCC/sistema/css/estilo.css">
    <link rel="icon" href="../Imagens/caixa.png">
</head>
<body>

<?php include '../sistema/menu.php'; ?>

<div class="conteudo">

<h2>Pedidos Registrados</h2>

<table border="1" width="100%" cellpadding="8">
<tr>
    <th>Código</th>
    <th>Data</th>
    <th>Cód. Cliente</th>
    <th>Cliente</th>
    <th>Vendedor</th>
    <th>Motorista</th>
    <th>Status</th>
</tr>

<?php foreach ($pedidos as $p): ?>
<tr>
    <td><?= $p['codPedido'] ?></td>
    <td><?= date('d/m/Y', strtotime($p['dataPedido'])) ?></td>
    <td><?= $p['codCliente'] ?></td>
    <td><?= $p['nomeCliente'] ?></td>
    <td><?= $p['nomeVendedor'] ?></td>
    <td><?= $p['nomeMotorista'] ?></td>

    <td>
        <form method="POST">
            <input type="hidden" name="codPedido" value="<?= $p['codPedido'] ?>">

            <select name="status" onchange="this.form.submit()">
                <option value="Aguardando" <?= $p['statusPedido'] == 'Aguardando' ? 'selected' : '' ?>>
                    Aguardando
                </option>
                <option value="Entregue" <?= $p['statusPedido'] == 'Entregue' ? 'selected' : '' ?>>
                    Entregue
                </option>
            </select>

            <?php if (!empty($p['dataEntrega'])): ?>
                <div style="font-size:12px; color:green;">
                    Entregue em: <?= date('d/m/Y', strtotime($p['dataEntrega'])) ?>
                </div>
            <?php endif; ?>
        </form>
    </td>
</tr>
<?php endforeach; ?>

</table>
</div>

</body>
</html>
