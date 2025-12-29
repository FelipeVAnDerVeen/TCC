<?php
require '../verifica_sessao.php';
require '../conexao.php';

$codCliente      = $_POST['codCliente'];
$nomeCliente     = $_POST['nomeCliente'];
$cidadeCliente   = $_POST['cidadeCliente'];
$enderecoCliente = $_POST['enderecoCliente'];
$codVendedor     = $_POST['codVendedor'];

$sql = "INSERT INTO clientes
(codCliente, nomeCliente, cidadeCliente, enderecoCliente, codVendedor)
VALUES (:cod, :nome, :cidade, :endereco, :vendedor)";

$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        ':cod'      => $codCliente,
        ':nome'     => $nomeCliente,
        ':cidade'   => $cidadeCliente,
        ':endereco' => $enderecoCliente,
        ':vendedor' => $codVendedor
    ]);

    header("Location: cliente_listar.php");
    exit;

} catch (PDOException $e) {
    echo "Erro: código do cliente já existe ou vendedor inválido.";
}
