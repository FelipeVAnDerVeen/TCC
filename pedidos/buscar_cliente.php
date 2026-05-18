<?php
require '../conexao.php';

$cod = $_GET['cod'];

$sql = "SELECT * FROM clientes WHERE codCliente = ?";
$stmt = $pdo->prepare($sql);

$stmt->execute([$cod]);

$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($cliente) {

    echo json_encode($cliente);

} else {

    echo json_encode([
        'erro' => true
    ]);
}