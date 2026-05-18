<?php
require 'verifica_sessao.php';
require '../conexao.php';



$dataInicio = $_GET['dataInicio'] ?? date('Y-m-01');
$dataFim = $_GET['dataFim'] ?? date('Y-m-d');

$sqlGrafico = "
    SELECT 
        DATE_FORMAT(dataPedido, '%m/%Y') AS mes,

        COUNT(*) AS totalPedidos,

        SUM(
            CASE 
                WHEN statusPedido = 'Entregue'
                THEN 1
                ELSE 0
            END
        ) AS totalEntregues

    FROM pedidos

    WHERE dataPedido BETWEEN :inicio AND :fim

    GROUP BY DATE_FORMAT(dataPedido, '%m/%Y')

    ORDER BY MIN(dataPedido)
";

$stmtGrafico = $pdo->prepare($sqlGrafico);

$stmtGrafico->execute([
    ':inicio' => $dataInicio,
    ':fim' => $dataFim
]);

$dadosGrafico = $stmtGrafico->fetchAll(PDO::FETCH_ASSOC);

/* ARRAYS PARA O CHART */

$labels = [];
$pedidosMes = [];
$entreguesMes = [];

foreach ($dadosGrafico as $d) {

    $labels[] = $d['mes'];

    $pedidosMes[] = $d['totalPedidos'];

    $entreguesMes[] = $d['totalEntregues'];
}

/* =========================
   TOTAL CLIENTES
========================= */

$sqlClientes = "
    SELECT COUNT(*)
    FROM clientes
";

$stmtClientes = $pdo->prepare($sqlClientes);

$stmtClientes->execute();

$totalClientes = $stmtClientes->fetchColumn();


/* =========================
   PESO TOTAL ENTREGUE
========================= */

/* =========================
   PESO ENTREGUE POR MÊS
========================= */

$sqlPeso = "
    SELECT

        DATE_FORMAT(p.dataPedido, '%m/%Y') AS mes,

        SUM(
            ip.quantidade * pr.pesoProduto
        ) AS pesoTotal

    FROM pedidos p

    JOIN itens_pedido ip
        ON p.codPedido = ip.codPedido

    JOIN produtos pr
        ON ip.codProduto = pr.codProduto

    WHERE p.statusPedido = 'Entregue'
    AND p.dataPedido BETWEEN :inicio AND :fim

    GROUP BY DATE_FORMAT(p.dataPedido, '%m/%Y')

    ORDER BY MIN(p.dataPedido)
";

$stmtPeso = $pdo->prepare($sqlPeso);

$stmtPeso->execute([
    ':inicio' => $dataInicio,
    ':fim' => $dataFim
]);

$dadosPeso = $stmtPeso->fetchAll(PDO::FETCH_ASSOC);

$labelsPeso = [];
$valoresPeso = [];

foreach ($dadosPeso as $p) {

    $labelsPeso[] = $p['mes'];

    $valoresPeso[] = $p['pesoTotal'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Sistema</title>
    <link rel="stylesheet" href="/TCC/sistema/css/estilo.css">
    <link rel="icon" href="../Imagens/caixa.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>


<body>

    <?php include 'menu.php'; ?>

    <div class="dashboard">

        <!-- TÍTULO -->
        <h1>
            Bem-vindo, <?= $_SESSION['usuario']; ?> 👋
        </h1>

        <p>
            Selecione uma opção abaixo para começar
        </p>

        <!-- CARDS -->
        <div class="dashboard-cards">

            <a href="../produtos/produto_listar.php"
                class="card-dashboard card-estoque">

                <h2>📦 Estoque</h2>

                <span>
                    Visualizar produtos em estoque
                </span>

            </a>

            <a href="../produtos/produto_cadastro.php"
                class="card-dashboard card-produtos">

                <h2>➕ Produtos</h2>

                <span>
                    Cadastrar novos produtos
                </span>

            </a>

            <a href="../clientes/cliente_listar.php"
                class="card-dashboard card-clientes">

                <h2>👤 Clientes</h2>

                <span>
                    Gerenciar clientes
                </span>

            </a>

            <a href="../vendedores/vendedor_listar.php"
                class="card-dashboard card-vendedores">

                <h2>👨‍💼 Vendedores</h2>

                <span>
                    Gerenciar vendedores
                </span>

            </a>

            <a href="../motoristas/motorista_listar.php"
                class="card-dashboard card-motoristas">

                <h2>🚚 Motoristas</h2>

                <span>
                    Gerenciar motoristas
                </span>

            </a>

            <a href="../pedidos/pedido_listar.php"
                class="card-dashboard card-pedidos">

                <h2>📄 Pedidos</h2>

                <span>
                    Gerenciar pedidos
                </span>

            </a>

        </div>

        <!-- FILTRO -->
        <form method="GET" class="filtro-dashboard">

            <div class="campo-filtro">
                <label>Data Inicial</label>

                <input
                    type="date"
                    name="dataInicio"
                    value="<?= $dataInicio ?>">
            </div>

            <div class="campo-filtro">
                <label>Data Final</label>

                <input
                    type="date"
                    name="dataFim"
                    value="<?= $dataFim ?>">
            </div>

            <button type="submit">
                Filtrar
            </button>

        </form>



        <!-- GRÁFICOS -->
        <div class="dashboard-graficos">

            <!-- GRÁFICO 1 -->
            <div class="grafico-card">

                <h3>Pedidos x Entregues</h3>

                <div style="height:300px;">
                    <canvas id="graficoPedidos"></canvas>
                </div>

            </div>

            <!-- GRÁFICO 2 -->
            <!-- KPI CLIENTES -->
            <div class="grafico-card kpi-centralizado">

                <h3>Clientes Cadastrados</h3>

                <div class="numero-kpi">

                    <span id="contadorClientes">
                        0
                    </span>

                </div>

            </div>
            <!-- GRÁFICO 3 -->
            <div class="grafico-card">

                <h3>Peso Entregue por Mês</h3>

                <canvas id="graficoPeso"></canvas>

            </div>

        </div>

    </div>

    <script>
        const ctx = document.getElementById('graficoPedidos');

        new Chart(ctx, {

            type: 'bar',

            data: {

                labels: <?= json_encode($labels) ?>,

                datasets: [

                    {
                        label: 'Pedidos',

                        data: <?= json_encode($pedidosMes) ?>,

                        backgroundColor: '#3498db'
                    },

                    {
                        label: 'Entregues',

                        data: <?= json_encode($entreguesMes) ?>,

                        backgroundColor: '#2ecc71'
                    }

                ]
            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <script>
        const totalClientes = <?= $totalClientes ?>;

        const contador =
            document.getElementById('contadorClientes');

        let numeroAtual = 0;

        const velocidade = Math.ceil(totalClientes / 80);

        const intervalo = setInterval(() => {

            numeroAtual += velocidade;

            if (numeroAtual >= totalClientes) {

                numeroAtual = totalClientes;

                clearInterval(intervalo);
            }

            contador.innerText = numeroAtual;

        }, 300);
    </script>

    <script>
        const ctxPeso = document.getElementById('graficoPeso');

        new Chart(ctxPeso, {

            type: 'bar',

            data: {

                labels: <?= json_encode($labelsPeso) ?>,

                datasets: [{

                    label: 'Peso Entregue (kg)',

                    data: <?= json_encode($valoresPeso) ?>,

                    backgroundColor: '#3498db'

                }]
            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>


</body>

</html>