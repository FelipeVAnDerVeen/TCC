<?php
require '../conexao.php';

$codProduto = $_GET['codProduto'] ?? '';

$sql = "SELECT nomeProduto, pesoProduto FROM produtos WHERE codProduto = :cod";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':cod', $codProduto);
$stmt->execute();

$produto = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($produto);
