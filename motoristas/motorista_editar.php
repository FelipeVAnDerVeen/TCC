<?php
require '../verifica_sessao.php';
require '../conexao.php';

$cod = $_GET['cod'] ?? null;

if (!$cod) {
    header('Location: motorista_listar.php');
    exit;
}

/* SALVAR ALTERAÇÃO */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $sql = "
        UPDATE motoristas SET
            nomeMotorista = :nome,
            placaMotorista = :placa
        WHERE codMotorista = :cod
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome'  => $_POST['nomeMotorista'],
        ':placa' => $_POST['placaMotorista'],
        ':cod'   => $cod
    ]);

    header('Location: motorista_listar.php');
    exit;
}

/* BUSCAR MOTORISTA */
$sql = "SELECT * FROM motoristas WHERE codMotorista = :cod";
$stmt = $pdo->prepare($sql);
$stmt->execute([':cod' => $cod]);
$motorista = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$motorista) {
    header('Location: motorista_listar.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Motorista</title>
    <link rel="stylesheet" href="/TCC/sistema/css/estilo.css">
</head>
<body>

<?php include '../sistema/menu.php'; ?>

<div class="conteudo">
    <h2>Editar Motorista</h2>

    <form method="POST">
        <label>Nome do Motorista</label><br>
        <input type="text" name="nomeMotorista"
               value="<?= htmlspecialchars($motorista['nomeMotorista']) ?>" required><br><br>

        <label>Placa do Veículo</label><br>
        <input type="text" name="placaMotorista"
               value="<?= htmlspecialchars($motorista['placaMotorista']) ?>" required><br><br>

        <button type="submit">💾 Salvar</button>
        <a href="motorista_listar.php">Cancelar</a>
    </form>
</div>

</body>
</html>
