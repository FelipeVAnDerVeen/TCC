<?php
require '../verifica_sessao.php';
require '../conexao.php';

$cod = $_GET['cod'];

$sql = "DELETE FROM motoristas WHERE codMotorista = :cod";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':cod', $cod);
$stmt->execute();

header("Location: motorista_listar.php");
exit;
