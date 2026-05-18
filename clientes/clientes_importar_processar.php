<?php
require '../verifica_sessao.php';
require '../conexao.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (!isset($_FILES['arquivo'])) {
    die("Nenhum arquivo enviado.");
}

$arquivo = $_FILES['arquivo']['tmp_name'];

$spreadsheet = IOFactory::load($arquivo);
$sheet = $spreadsheet->getActiveSheet();
$linhas = $sheet->toArray();

$inseridos = 0;
$atualizados = 0;
$ignorados = 0;

$pdo->beginTransaction();

try {

    // Remove o cabeçalho
    unset($linhas[0]);

    $sql = "
        INSERT INTO clientes
        (codCliente, nomeCliente, cidadeCliente, enderecoCliente, codVendedor)
        VALUES (:cod, :nome, :cidade, :endereco, :vendedor)
        ON DUPLICATE KEY UPDATE
            nomeCliente = VALUES(nomeCliente),
            cidadeCliente = VALUES(cidadeCliente),
            enderecoCliente = VALUES(enderecoCliente),
            codVendedor = VALUES(codVendedor)
    ";

    $stmt = $pdo->prepare($sql);

    foreach ($linhas as $linha) {

        // Validação mínima
        if (
            empty($linha[0]) ||
            empty($linha[1]) ||
            empty($linha[4])
        ) {
            $ignorados++;
            continue;
        }

        $codCliente  = trim($linha[0]);
        $nomeCliente = trim($linha[1]);
        $cidade      = trim($linha[2] ?? '');
        $endereco    = trim($linha[3] ?? '');
        $codVendedor = trim($linha[4]);

        // VERIFICA SE O VENDEDOR EXISTE
        $verificaVendedor = $pdo->prepare(
            "SELECT COUNT(*) FROM vendedores WHERE codVendedor = ?"
        );

        $verificaVendedor->execute([$codVendedor]);

        if (!$verificaVendedor->fetchColumn()) {

            throw new Exception(
                "O vendedor código {$codVendedor} não está cadastrado no sistema."
            );
        }

        // VERIFICA SE CLIENTE JÁ EXISTE
        $verifica = $pdo->prepare(
            "SELECT COUNT(*) FROM clientes WHERE codCliente = ?"
        );

        $verifica->execute([$codCliente]);

        $existe = $verifica->fetchColumn();

        // INSERT / UPDATE
        $stmt->execute([
            ':cod'      => $codCliente,
            ':nome'     => $nomeCliente,
            ':cidade'   => $cidade,
            ':endereco' => $endereco,
            ':vendedor' => $codVendedor
        ]);

        if ($existe) {
            $atualizados++;
        } else {
            $inseridos++;
        }
    }

    $pdo->commit();

    // Redireciona com resumo
    
    header("Location: cliente_listar.php?inseridos=$inseridos&atualizados=$atualizados&ignorados=$ignorados");
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Erro ao importar clientes: " . $e->getMessage();
}
