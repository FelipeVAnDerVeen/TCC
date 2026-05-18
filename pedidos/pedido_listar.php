<?php
require '../verifica_sessao.php';
require '../conexao.php';

/* =========================
   ATUALIZAR PEDIDO
========================= */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $codPedido = $_POST['codPedido'];
    $codMotorista = $_POST['codMotorista'];
    $numeroCarga = $_POST['numeroCarga'];
    $status = $_POST['status'];

    try {

        if ($status == 'Entregue') {

            $sql = "
                    UPDATE pedidos
                    SET
                        codMotorista = :motorista,
                        numeroCarga = :carga,
                        statusPedido = :status,
                        dataEntrega = CURDATE()
                    WHERE codPedido = :pedido
                ";
        } else {

            $sql = "
                    UPDATE pedidos
                    SET
                        codMotorista = :motorista,
                        numeroCarga = :carga,
                        statusPedido = :status,
                        dataEntrega = NULL
                    WHERE codPedido = :pedido
                ";
        }

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':motorista' => $codMotorista ?: null,
            ':carga' => $numeroCarga,
            ':status' => $status,
            ':pedido' => $codPedido
        ]);
    } catch (Exception $e) {

        echo "Erro ao atualizar pedido: " . $e->getMessage();
    }
}

$buscaPedido = $_GET['pedido'] ?? '';
$buscaCliente = $_GET['cliente'] ?? '';
$buscaMotorista = $_GET['motorista'] ?? '';
$buscaStatus = $_GET['status'] ?? '';
$buscaCarga = $_GET['carga'] ?? '';
$buscaDataInicio = $_GET['dataInicio'] ?? '';
$buscaDataFim = $_GET['dataFim'] ?? '';

$where = [];
$params = [];

/* PEDIDO */
if (!empty($buscaPedido)) {

    $where[] = "p.codPedido = :pedido";
    $params[':pedido'] = $buscaPedido;
}

/* CLIENTE */
if (!empty($buscaCliente)) {

    $where[] = "
        (
            c.nomeCliente LIKE :cliente
            OR c.codCliente LIKE :cliente
        )
    ";

    $params[':cliente'] = "%$buscaCliente%";
}

/* MOTORISTA */
if (!empty($buscaMotorista)) {

    $where[] = "m.nomeMotorista LIKE :motorista";

    $params[':motorista'] = "%$buscaMotorista%";
}

/* STATUS */
if (!empty($buscaStatus)) {

    $where[] = "p.statusPedido = :status";

    $params[':status'] = $buscaStatus;
}

/* CARGA */
if (!empty($buscaCarga)) {

    $where[] = "p.numeroCarga LIKE :carga";

    $params[':carga'] = "%$buscaCarga%";
}

/* DATA INICIAL */
if (!empty($buscaDataInicio)) {

    $where[] = "p.dataPedido >= :dataInicio";

    $params[':dataInicio'] = $buscaDataInicio;
}

/* DATA FINAL */
if (!empty($buscaDataFim)) {

    $where[] = "p.dataPedido <= :dataFim";

    $params[':dataFim'] = $buscaDataFim;
}

/* MONTA WHERE */
$filtroSQL = '';

if (!empty($where)) {

    $filtroSQL = 'WHERE ' . implode(' AND ', $where);
}

/* =========================
   LISTAR PEDIDOS
========================= */

$sql = "
SELECT
    p.*,

    c.nomeCliente,
    c.codCliente,

    m.nomeMotorista,
    m.placaMotorista

FROM pedidos p

JOIN clientes c
ON p.codCliente = c.codCliente

LEFT JOIN motoristas m
ON p.codMotorista = m.codMotorista

$filtroSQL

ORDER BY p.codPedido DESC
";

$stmt = $pdo->prepare($sql);

$stmt->execute($params);

$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   MOTORISTAS
========================= */

$sqlMotoristas = "
    SELECT *
    FROM motoristas
    ORDER BY nomeMotorista
";

$motoristas = $pdo->query($sqlMotoristas)->fetchAll(PDO::FETCH_ASSOC);

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

    <h2>Pedidos Registrados</h2>


    <form method="GET" style="margin-bottom:20px; display:flex; gap:10px; flex-wrap:wrap;" class="filtro-clientes">

        <input
            type="text"
            name="pedido"
            placeholder="Pedido"
            value="<?= $buscaPedido ?>">

        <input
            type="text"
            name="cliente"
            placeholder="Cliente"
            value="<?= $buscaCliente ?>">

        <input
            type="text"
            name="motorista"
            placeholder="Motorista"
            value="<?= $buscaMotorista ?>">

        <input
            type="text"
            name="carga"
            placeholder="Carga"
            value="<?= $buscaCarga ?>">

        <input
            type="date"
            name="dataInicio"
            value="<?= $buscaDataInicio ?>">

        <input
            type="date"
            name="dataFim"
            value="<?= $buscaDataFim ?>">

        <select name="status">

            <option value="">
                Status
            </option>

            <option value="Aguardando"
                <?= $buscaStatus == 'Aguardando' ? 'selected' : '' ?>>
                Aguardando
            </option>

            <option value="Entregue"
                <?= $buscaStatus == 'Entregue' ? 'selected' : '' ?>>
                Entregue
            </option>

        </select>

        <button type="submit">
            🔍 Filtrar
        </button>

        <a href="pedido_listar.php" class="btn-limpar">
            Limpar
        </a>

    </form>

    <p style="margin-bottom:20px;">
        <strong>
            Total de pedidos:
            <?= count($pedidos) ?>
        </strong>
    </p>

    <div class="conteudo">



        <table border="1" width="100%" cellpadding="8">

            <tr>

                <th>Pedido</th>
                <th>Data</th>
                <th>Cliente</th>
                <th>Produtos</th>
                <th>Motorista</th>
                <th>Carga</th>
                <th>Status</th>
                <th>Entrega</th>
                <th>Ações</th>

            </tr>

            <?php foreach ($pedidos as $p): ?>

                <tr>

                    <!-- PEDIDO -->
                    <td>
                        <?= $p['codPedido'] ?>
                    </td>

                    <!-- DATA -->
                    <td>
                        <?= date('d/m/Y', strtotime($p['dataPedido'])) ?>
                    </td>

                    <!-- CLIENTE -->
                    <td>

                        <?= $p['codCliente'] ?>
                        -
                        <?= $p['nomeCliente'] ?>

                    </td>

                    <!-- PRODUTOS -->
                    <td>

                        <?php

                        $sqlItens = "
                    SELECT
                        i.quantidade,

                        pr.nomeProduto,
                        pr.pesoProduto

                    FROM itens_pedido i

                    JOIN produtos pr
                    ON i.codProduto = pr.codProduto

                    WHERE i.codPedido = ?
                ";

                        $stmtItens = $pdo->prepare($sqlItens);

                        $stmtItens->execute([
                            $p['codPedido']
                        ]);

                        $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

                        $pesoTotal = 0;

                        foreach ($itens as $item) {

                            $pesoItem =
                                $item['quantidade']
                                *
                                $item['pesoProduto'];

                            $pesoTotal += $pesoItem;

                            echo "
                        • {$item['nomeProduto']}
                        ({$item['quantidade']})
                        <br>
                    ";
                        }

                        echo "
                    <hr>
                    <strong>
                        Peso Total:
                        {$pesoTotal} kg
                    </strong>
                ";
                        ?>

                    </td>

                    <!-- FORM -->
                    <form method="POST">

                        <input
                            type="hidden"
                            name="codPedido"
                            value="<?= $p['codPedido'] ?>">

                        <!-- MOTORISTA -->
                        <td>

                            <select name="codMotorista">

                                <option value="">
                                    Selecione
                                </option>

                                <?php foreach ($motoristas as $m): ?>

                                    <option
                                        value="<?= $m['codMotorista'] ?>"

                                        <?= $p['codMotorista'] == $m['codMotorista']
                                            ? 'selected'
                                            : ''
                                        ?>>

                                        <?= $m['nomeMotorista'] ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </td>

                        <!-- CARGA -->
                        <td>

                            <input
                                type="text"
                                name="numeroCarga"
                                value="<?= $p['numeroCarga'] ?>"
                                placeholder="Carga">

                        </td>

                        <!-- STATUS -->
                        <td>

                            <select name="status">

                                <option
                                    value="Aguardando"

                                    <?= $p['statusPedido'] == 'Aguardando'
                                        ? 'selected'
                                        : ''
                                    ?>>
                                    Aguardando
                                </option>


                                <option
                                    value="Entregue"

                                    <?= $p['statusPedido'] == 'Entregue'
                                        ? 'selected'
                                        : ''
                                    ?>>
                                    Entregue
                                </option>

                            </select>

                        </td>

                        <!-- ENTREGA -->
                        <td>

                            <?php if (!empty($p['dataEntrega'])): ?>

                                <?= date(
                                    'd/m/Y',
                                    strtotime($p['dataEntrega'])
                                ) ?>

                            <?php else: ?>

                                -

                            <?php endif; ?>

                        </td>

                        <!-- BOTÃO -->
                        <td>

                            <button type="submit">
                                Salvar
                            </button>

                    </form>

                    </td>

                </tr>

            <?php endforeach; ?>

        </table>

    </div>

</body>

</html>