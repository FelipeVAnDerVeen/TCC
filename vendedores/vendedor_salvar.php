<?php
require '../verifica_sessao.php';
require '../conexao.php';

$codVendedor = $_POST['codVendedor'];
$nomeVendedor = $_POST['nomeVendedor'];
$telefoneVendedor = $_POST['telefoneVendedor'];

$sql = "INSERT INTO vendedores 
(codVendedor, nomeVendedor, telefoneVendedor)
VALUES (:cod, :nome, :telefone)";

$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        ':cod' => $codVendedor,
        ':nome' => $nomeVendedor,
        ':telefone' => $telefoneVendedor
    ]);

    header("Location: vendedor_listar.php");
    exit;

} catch (PDOException $e) {
    echo "Erro: código do vendedor já existe.";
}
