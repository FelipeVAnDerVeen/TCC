<?php
require '../verifica_sessao.php';
require '../conexao.php';

$codProduto = $_POST['codProduto'];
$nomeProduto = $_POST['nomeProduto'];
$quantidadeProduto = $_POST['quantidadeProduto'];
$pesoProduto = $_POST['pesoProduto'];

$sql = "INSERT INTO produtos 
(codProduto, nomeProduto, quantidadeProduto, pesoProduto)
VALUES (:cod, :nome, :quantidade, :peso)";

$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        ':cod' => $codProduto,
        ':nome' => $nomeProduto,
        ':quantidade' => $quantidadeProduto,
        ':peso' => $pesoProduto
    ]);

    header("Location: produto_listar.php");
    exit;

} catch (PDOException $e) {
    echo "Erro: código do produto já existe.";
}
