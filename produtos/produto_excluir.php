<?php
require '../verifica_sessao.php';
require '../conexao.php';

$cod = $_GET['cod'];

$sql = "DELETE FROM produtos WHERE codProduto = :cod";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':cod', $cod);
$stmt->execute();

header("Location: produto_listar.php");
exit;
