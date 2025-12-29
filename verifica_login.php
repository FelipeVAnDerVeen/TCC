<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'conexao.php';

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

$sql = "SELECT * FROM usuarios WHERE emailUsuario = :email";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario && password_verify($senha, $usuario['senhaUsuario'])) {

    $_SESSION['usuario'] = $usuario['nomeUsuario'];
    $_SESSION['tipo'] = $usuario['tipoUsuario'];
    $_SESSION['codUsuario'] = $usuario['codUsuario'];

    header("Location: sistema/dashboard.php");
    exit;

} else {
    header("Location: login.php?erro=1");
    exit;
}
