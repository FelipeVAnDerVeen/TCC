<?php
require '../verifica_sessao.php';
require '../conexao.php';

/* SALVAR PEDIDO */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {

        $pdo->beginTransaction();

        $codCliente = $_POST['codCliente'];

        /* CRIA PEDIDO */
        $sqlPedido = "
            INSERT INTO pedidos
            (dataPedido, codCliente)
            VALUES (CURDATE(), :codCliente)
        ";

        $stmtPedido = $pdo->prepare($sqlPedido);

        $stmtPedido->execute([
            ':codCliente' => $codCliente
        ]);

        /* PEGA ID GERADO */
        $codPedido = $pdo->lastInsertId();

        /* PRODUTOS */
        if (!empty($_POST['codProduto'])) {

            foreach ($_POST['codProduto'] as $index => $codProduto) {

                $quantidade = $_POST['quantidade'][$index];

                /* IGNORA LINHAS VAZIAS */
                if (empty($codProduto) || empty($quantidade)) {
                    continue;
                }

                /* INSERE ITEM */
                $sqlItem = "
                    INSERT INTO itens_pedido
                    (codPedido, codProduto, quantidade)
                    VALUES (:pedido, :produto, :quantidade)
                ";

                $stmtItem = $pdo->prepare($sqlItem);

                $stmtItem->execute([
                    ':pedido' => $codPedido,
                    ':produto' => $codProduto,
                    ':quantidade' => $quantidade
                ]);

                /* BAIXA ESTOQUE */
                $sqlEstoque = "
                    UPDATE produtos
                    SET quantidadeProduto = quantidadeProduto - :quantidade
                    WHERE codProduto = :produto
                ";

                $stmtEstoque = $pdo->prepare($sqlEstoque);

                $stmtEstoque->execute([
                    ':quantidade' => $quantidade,
                    ':produto' => $codProduto
                ]);
            }
        }

        $pdo->commit();

        echo "
        <script>
            alert('Pedido registrado com sucesso!');
            window.location='pedido_listar.php';
        </script>
        ";

    } catch (Exception $e) {

        $pdo->rollBack();

        echo "Erro ao registrar pedido: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Solicitação de Merchan</title>

    <link rel="stylesheet" href="/TCC/sistema/css/estilo.css">
    <link rel="icon" href="../Imagens/caixa.png">
</head>
<body>

<?php include '../sistema/menu.php'; ?>

<div class="conteudo">

    <h2>Solicitação de Merchan</h2>

    <form method="POST">

        <!-- CLIENTE -->
        <div class="grupo-form">

            <label>Código do Cliente</label>

            <input 
                type="number"
                name="codCliente"
                id="codCliente"
                required
                onblur="buscarCliente()"
            >

        </div>

        <div class="grupo-form">

            <label>Nome do Cliente</label>

            <input 
                type="text"
                id="nomeCliente"
                readonly
            >

        </div>

        <hr>

        <h3>Produtos</h3>

        <?php for ($i = 0; $i < 5; $i++): ?>

            <div class="linha-produto">

                <input
                    type="text"
                    name="codProduto[]"
                    placeholder="Código Produto"
                    onblur="buscarProduto(this, <?= $i ?>)"
                >

                <input
                    type="text"
                    id="nomeProduto<?= $i ?>"
                    placeholder="Nome Produto"
                    readonly
                >

                <input
                    type="number"
                    name="quantidade[]"
                    placeholder="Quantidade"
                    min="1"
                >

            </div>

        <?php endfor; ?>

        <br>

        <button type="submit">
            Salvar Pedido
        </button>

    </form>

</div>

<script>

/* BUSCAR CLIENTE */
function buscarCliente() {

    let cod = document.getElementById('codCliente').value;

    fetch('buscar_cliente.php?cod=' + cod)

    .then(response => response.json())

    .then(data => {

        if (data.erro) {



            document.getElementById('nomeCliente').value = '';

            return;
        }

        document.getElementById('nomeCliente').value =
            data.nomeCliente;
    });
}

/* BUSCAR PRODUTO */
function buscarProduto(input, index) {

    let cod = input.value;

    fetch('buscar_produto.php?cod=' + cod)

    .then(response => response.json())

    .then(data => {

        if (data.erro) {


            document.getElementById(
                'nomeProduto' + index
            ).value = '';

            return;
        }

        document.getElementById(
            'nomeProduto' + index
        ).value = data.nomeProduto;
    });
}

</script>

</body>
</html>