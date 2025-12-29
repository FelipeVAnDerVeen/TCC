<?php
require '../verifica_sessao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Como Importar Clientes</title>
    <link rel="stylesheet" href="/TCC/sistema/css/estilo.css">
    <link rel="icon" href="../Imagens/caixa.png">
</head>
<body>

<?php include '../sistema/menu.php'; ?>

<div class="conteudo">

    <h2>📘 Como importar clientes via Excel</h2>

    <p>
        Siga corretamente os passos abaixo para realizar a importação de clientes
        utilizando uma planilha Excel.
    </p>

    <h3>📂 1. Estrutura da Planilha</h3>
    <h3>🖼️ Exemplo de planilha</h3>

    <p>
        Utilize o modelo abaixo como referência para preencher corretamente
        a planilha de importação.
    </p>

    <div class="exemplo-planilha">
        <img src="/TCC/Imagens/exemplo_planilha_clientes.png"
            alt="Exemplo de planilha para importação de clientes">
    </div>

    <p>A planilha deve conter as colunas exatamente nesta ordem:</p>

    <table border="1" cellpadding="8" width="100%">
        <tr>
            <th>Coluna</th>
            <th>Descrição</th>
            <th>Obrigatória</th>
        </tr>
        <tr>
            <td>codCliente</td>
            <td>Código único do cliente</td>
            <td>Sim</td>
        </tr>
        <tr>
            <td>nomeCliente</td>
            <td>Nome do cliente</td>
            <td>Sim</td>
        </tr>
        <tr>
            <td>cidadeCliente</td>
            <td>Cidade do cliente</td>
            <td>Não</td>
        </tr>
        <tr>
            <td>enderecoCliente</td>
            <td>Endereço do cliente</td>
            <td>Não</td>
        </tr>
        <tr>
            <td>codVendedor</td>
            <td>Código do vendedor responsável</td>
            <td>Sim</td>
        </tr>
    </table>

    <h3>📊 2. Regras Importantes</h3>

    <ul>
        <li>✔ A primeira linha deve conter o cabeçalho</li>
        <li>✔ O arquivo deve estar no formato <strong>.xlsx</strong></li>
        <li style="color: red; font-size: 20px;">
            ✔ Clientes existentes serão <strong>atualizados</strong>
        </li>
        <li style="color: red; font-size: 20px;">
           ✔ Clientes novos serão <strong>cadastrados</strong>
        </li>
        <li>⚠ Linhas incompletas serão ignoradas</li>
    </ul>

    <h3>🚀 3. Como Enviar o Arquivo</h3>

    <ol>
        <li>Acesse o menu <strong>Clientes → Importar</strong></li>
        <li>Selecione o arquivo Excel</li>
        <li>Clique em <strong>Importar</strong></li>
        <li>Aguarde a mensagem de confirmação</li>
    </ol>

    <br>

    <a href="clientes_importar.php" class="btn-ajuda">
        ⬅ Voltar para Importação
    </a>

</div>

</body>
</html>
