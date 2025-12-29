<?php
require '../verifica_sessao.php';
require '../conexao.php';

$codMotorista   = $_POST['codMotorista'];
$nomeMotorista  = $_POST['nomeMotorista'];
$placaMotorista = $_POST['placaMotorista'];

$sql = "INSERT INTO motoristas 
(codMotorista, nomeMotorista, placaMotorista)
VALUES (:cod, :nome, :placa)";

$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        ':cod'   => $codMotorista,
        ':nome'  => $nomeMotorista,
        ':placa' => $placaMotorista
    ]);

    header("Location: motorista_listar.php");
    exit;

} catch (PDOException $e) {
    echo "Erro: código do motorista já existe.";
}
