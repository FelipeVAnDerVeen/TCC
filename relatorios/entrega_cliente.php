<?php
require '../verifica_sessao.php';
require '../conexao.php';

$dados = null;

if (!empty($_GET['codCliente'])) {
    $codCliente = $_GET['codCliente'];

    $sql = "
    SELECT 
        c.codCliente,
        c.nomeCliente,
        c.enderecoCliente,
        v.nomeVendedor,
        v.telefoneVendedor
    FROM clientes c
    JOIN vendedores v ON c.codVendedor = v.codVendedor
    WHERE c.codCliente = :cliente
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cliente', $codCliente);
    $stmt->execute();
    $dados = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <link rel="icon" href="../Imagens/caixa.png">

<head>
    <meta charset="UTF-8">
    <title>Entrega ao Cliente</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .relatorio {
            width: 100%;
            min-height: 100vh;
            box-sizing: border-box;
            padding: 40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .linha {
            font-size: 36px;
            margin: 12px 0;
        }

        hr {
            margin: 20px 0;
        }

        input {
            padding: 8px;
            /* Cria espaço interno */
            font-size: 20px;
            /* Define o tamanho da fonte do texto que o usuário digita */
            border-radius: 8px;
            /* Estética */
        }

        form,
        button {
            margin-bottom: 20px;
            font-size: 30px;
        }

        /* IMPRESSÃO */
        @media print {
            body {
                margin: 0;
            }

            .relatorio {
                width: 100%;
                height: 100%;
                padding: 20mm;
            }

            form,
            button {
                display: none;
            }
        }
    </style>

</head>

<body>

    <div class="relatorio">

        <h2>ENTREGA AO CLIENTE</h2>

        <form method="GET">
            <input type="number" name="codCliente" placeholder="Código do Cliente" required>
            <button type="submit">Buscar</button>
        </form>

        <?php if ($dados): ?>

            <hr>

            <div class="linha"><strong>Código do Cliente:</strong> <?= $dados['codCliente'] ?></div>
            <div class="linha"><strong>Nome do Cliente:</strong> <?= $dados['nomeCliente'] ?></div>
            <div class="linha"><strong>Endereço:</strong> <?= $dados['enderecoCliente'] ?></div>

            <hr>

            <div class="linha"><strong>Vendedor:</strong> <?= $dados['nomeVendedor'] ?></div>
            <div class="linha"><strong>Telefone do Vendedor:</strong> <?= $dados['telefoneVendedor'] ?></div>

            <button onclick="window.print()">Imprimir</button>

        <?php elseif (isset($_GET['codCliente'])): ?>
            <p><strong>Cliente não encontrado.</strong></p>
        <?php endif; ?>

    </div>

</body>

</html>