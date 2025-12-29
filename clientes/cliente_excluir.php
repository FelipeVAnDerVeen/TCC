<?php
require '../verifica_sessao.php';
require '../conexao.php';

$cod = $_GET['cod'];

$sql = "DELETE FROM clientes WHERE codCliente = :cod";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':cod', $cod);
$stmt->execute();

header("Location: cliente_listar.php");
exit;
