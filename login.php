<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Estoque Merchan</title>
    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
        }
        .login {
            width: 400px;
            margin: 120px auto;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }
        h2 {
            text-align: center;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
        }
        button {
            width: 105%;
            padding: 10px;
            margin-top: 15px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .erro {
            color: red;
            text-align: center;
        }

        body {
            background: linear-gradient(rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.2)), 
                        url('Imagens/fundo.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            min-height: 100vh;   
        }
    </style>
</head>
<body>

<div class="login">
    <h2>Estoque Merchan</h2>

    <form action="verifica_login.php" method="POST">
        <input type="text" name="email" placeholder="Usuário" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>

    <?php
    if (isset($_GET['erro'])) {
        echo "<p class='erro'>E-mail ou senha inválidos</p>";
    }
    ?>
</div>

</body>
</html>
