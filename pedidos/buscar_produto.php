<?php
require '../conexao.php';

$cod = $_GET['cod'];

$sql = "SELECT * FROM produtos WHERE codProduto = ?";
$stmt = $pdo->prepare($sql);

$stmt->execute([$cod]);

$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if ($produto) {

    echo json_encode($produto);

} else {

    echo json_encode([
        'erro' => true
    ]);
}