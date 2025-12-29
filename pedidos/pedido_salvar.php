<?php
require '../verifica_sessao.php';
require '../conexao.php';

$pdo->beginTransaction();

try {
    $codPedido   = $_POST['codPedido'];
    $numeroCarga = $_POST['numeroCarga'];
    $codCliente  = $_POST['codCliente'];
    $codVendedor = $_POST['codVendedor'];
    $codMotorista= $_POST['codMotorista'];
    $dataPedido  = date('Y-m-d');

    // INSERE PEDIDO
    $sqlPedido = "INSERT INTO pedidos 
    (codPedido, dataPedido, statusPedido, numeroCarga, codCliente, codVendedor, codMotorista)
    VALUES (:cod, :data, 'Aguardando', :carga, :cliente, :vendedor, :motorista)";

    $stmt = $pdo->prepare($sqlPedido);
    $stmt->execute([
        ':cod' => $codPedido,
        ':data' => $dataPedido,
        ':carga' => $numeroCarga,
        ':cliente' => $codCliente,
        ':vendedor' => $codVendedor,
        ':motorista' => $codMotorista
    ]);

    // PRODUTOS
    foreach ($_POST['produtos'] as $codProduto) {
        $quantidade = $_POST['quantidades'][$codProduto];

        // INSERE ITEM
        $sqlItem = "INSERT INTO itens_pedido 
        (codPedido, codProduto, quantidade)
        VALUES (:pedido, :produto, :quantidade)";

        $stmt = $pdo->prepare($sqlItem);
        $stmt->execute([
            ':pedido' => $codPedido,
            ':produto' => $codProduto,
            ':quantidade' => $quantidade
        ]);

        // BAIXA ESTOQUE
        $sqlEstoque = "UPDATE produtos
        SET quantidadeProduto = quantidadeProduto - :qtd
        WHERE codProduto = :produto";

        $stmt = $pdo->prepare($sqlEstoque);
        $stmt->execute([
            ':qtd' => $quantidade,
            ':produto' => $codProduto
        ]);
    }

    $pdo->commit();
    header("Location: pedido_listar.php");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Erro ao registrar pedido: " . $e->getMessage();
}
