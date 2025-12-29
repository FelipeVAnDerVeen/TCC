<?php
require '../verifica_sessao.php';
require '../conexao.php';

$cliente = $motorista = null;

if (!empty($_GET['codCliente'])) {
    $sql = "
        SELECT 
            c.codCliente, c.nomeCliente, c.enderecoCliente,
            v.nomeVendedor, v.telefoneVendedor
        FROM clientes c
        JOIN vendedores v ON c.codVendedor = v.codVendedor
        WHERE c.codCliente = :cliente
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cliente', $_GET['codCliente']);
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!empty($_GET['codMotorista'])) {
    $sql = "SELECT nomeMotorista, placaMotorista FROM motoristas WHERE codMotorista = :m";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':m', $_GET['codMotorista']);
    $stmt->execute();
    $motorista = $stmt->fetch(PDO::FETCH_ASSOC);
}

$dataHoje = date('d/m/Y');
$numeroCarga = $_GET['numeroCarga'] ?? '';
?>

<!DOCTYPE html>
<html lang="pt-br">
    <link rel="icon" href="../Imagens/caixa.png">
<head>
    <meta charset="UTF-8">
    <title>Romaneio de Saída</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: auto; background: #fff; padding: 30px; border: 1px solid #ddd; }
        h2 { text-align: center; text-transform: uppercase; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .secao-info { display: flex; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; }
        .bloco { width: 48%; margin-bottom: 15px; }
        .linha { margin: 4px 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 8px; text-align: center; }
        input { padding: 5px; width: 90%; border: 1px solid #ccc; }
        
        /* Ajuste para não mostrar bordas de input na impressão */
        @media print {
            button, .no-print { display: none !important; }
            body { background: #fff; padding: 0; }
            .container { border: none; width: 100%; }
            input { border: none; background: transparent; text-align: center; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="no-print" style="text-align: right; font-size: 12px;"><?= $dataHoje ?></div>
    <h2>ROMANEIO DE SAÍDA</h2>

    <form method="GET" class="no-print">
        <div style="background: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <strong>Filtros de Busca:</strong><br>
            Cód. Cliente: <input type="number" name="codCliente" style="width: 80px" value="<?= $_GET['codCliente'] ?? '' ?>">
            Cód. Motorista: <input type="number" name="codMotorista" style="width: 80px" value="<?= $_GET['codMotorista'] ?? '' ?>">
            Nº Carga: <input type="text" name="numeroCarga" style="width: 100px" value="<?= $numeroCarga ?>">
            <button type="submit">Gerar Romaneio</button>
        </div>
    </form>

    <div class="secao-info">
        <div class="bloco">
            <strong>DADOS DO CLIENTE / VENDEDOR</strong>
            <?php if ($cliente): ?>
                <div class="linha"><strong>Cliente:</strong> <?= $cliente['codCliente'] ?> - <?= $cliente['nomeCliente'] ?></div>
                <div class="linha"><strong>Endereço:</strong> <?= $cliente['enderecoCliente'] ?></div>
                <div class="linha"><strong>Vendedor:</strong> <?= $cliente['nomeVendedor'] ?> (<?= $cliente['telefoneVendedor'] ?>)</div>
            <?php else: ?>
                <div class="linha"><i>Nenhum cliente selecionado</i></div>
            <?php endif; ?>
        </div>

        <div class="bloco">
            <strong>LOGÍSTICA</strong>
            <div class="linha"><strong>Data:</strong> <?= $dataHoje ?></div>
            <?php if ($motorista): ?>
                <div class="linha"><strong>Motorista:</strong> <?= $motorista['nomeMotorista'] ?></div>
                <div class="linha"><strong>Placa:</strong> <?= $motorista['placaMotorista'] ?></div>
            <?php endif; ?>
            <div class="linha"><strong>Carga nº:</strong> <?= $numeroCarga ?></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Cód. Produto</th>
                <th>Nome</th>
                <th>Qtd</th>
                <th>Peso Unit.</th>
                <th>Peso Total</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < 10; $i++): ?>
                <tr>
                    <td><input type="text" class="codProduto" onblur="buscarProduto(this)"></td>
                    <td><input type="text" class="nomeProduto" readonly></td>
                    <td><input type="number" class="quantidade" oninput="calcularLinha(this)"></td>
                    <td><input type="text" class="pesoUnitario" readonly></td>
                    <td><input type="text" class="pesoTotalLinha" readonly></td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <div style="margin-top: 20px; text-align: right;">
        <h3>Total de Peso: <span id="pesoTotalGeral">0.00</span> kg</h3>
    </div>

    <button onclick="window.print()" class="no-print" style="background: #28a745; color: white; border: none; cursor: pointer; padding: 10px 20px;">Imprimir Romaneio</button>
</div>

<script>
    // Mantive suas funções originais de JS
    function buscarProduto(campo) {
        let codProduto = campo.value;
        let linha = campo.closest('tr');
        if (!codProduto) return;
        fetch('buscar_produto.php?codProduto=' + codProduto)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    linha.querySelector('.nomeProduto').value = data.nomeProduto;
                    linha.querySelector('.pesoUnitario').value = data.pesoProduto;
                    calcularLinha(linha.querySelector('.quantidade'));
                }
            });
    }

    function calcularLinha(campoQtd) {
        let linha = campoQtd.closest('tr');
        let qtd = parseFloat(campoQtd.value) || 0;
        let peso = parseFloat(linha.querySelector('.pesoUnitario').value) || 0;
        let totalLinha = qtd * peso;
        linha.querySelector('.pesoTotalLinha').value = totalLinha.toFixed(2);
        calcularTotalGeral();
    }

    function calcularTotalGeral() {
        let total = 0;
        document.querySelectorAll('.pesoTotalLinha').forEach(campo => {
            total += parseFloat(campo.value) || 0;
        });
        document.getElementById('pesoTotalGeral').innerText = total.toFixed(2);
    }
</script>
</body>
</html>