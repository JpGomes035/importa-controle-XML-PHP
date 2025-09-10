<?php
include_once 'conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="shortcut icon" href="monitor.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300&display=swap" rel="stylesheet">
    <title>Login</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(to bottom, #2a9d8f, #264653);
        }

        .container {
            background: linear-gradient(to bottom, black, black);
            max-width: 500px;
            padding: 40px;
            border: 2px solid #f3f3f3;
            border-radius: 15px;
            box-shadow: 20px 20px 47px -3px rgba(0, 0, 0, 0.6);
            color: white;
        }

        h1 {
            font-size: 24px;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
        }

        .label {
            font-weight: bold;
        }

        table {
            width: 100%;
        }

        table td {
            font-weight: bold;
        }

        .form-group {
            margin-top: 20px;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #007bff;
        }

        .btn-block {
            display: block;
        }

        .justify-content-center {
            display: flex;
            justify-content: center;
        }

        .d-flex {
            display: flex;
        }

        .align-items-center {
            align-items: center;
        }

        .logo {
            margin-right: 30px;
            border-radius: 50%;
            margin-top: -10px;
            margin-left: -10px;
        }

        @media only screen and (max-width: 600px) {
            .container {
                max-width: 95%;
                /* Ajusta a largura do container para ocupar 90% da largura da tela */
                font-size: 14px;
                /* Aumenta o tamanho do texto */
                padding: 20px;
                /* Ajusta o espaçamento interno */
            }

            .form-control,
            button {
                width: 100%;
                /* Faz com que os inputs e botões ocupem 100% da largura disponível */
            }

            .logo {
                margin-right: 10px;
                /* Ajusta a margem do logo */
                width: 40px;
                /* Reduz o tamanho do logo */
                height: 40px;
            }
        }
    </style>
</head>

<body>
    <?php

    // Verifica se houve tentativa de login com credenciais inválidas
    if (isset($_GET['dadosInvalidos']) && $_GET['dadosInvalidos'] == 1) {
        echo '<p style="color: black;"><b>Credenciais de login inválidas. Por favor, tente novamente.</p></b>';
    }

    // Verifica se o usuário não está cadastrado
    if (isset($_GET['semCadastro'])) {
        $emailUsuario = htmlspecialchars($_GET['semCadastro']);
        echo '<p style="color: black;"><b>Usuário com o e-mail: <b>' . $emailUsuario . '</b> não está registrado.</p></b>';
    }

    // Verifica se houve múltiplos usuários encontrados com o mesmo e-mail
    if (isset($_GET['emailCadastrado'])) {
        $emailUsuario = htmlspecialchars($_GET['emailCadastrado']);
        echo '<p style="color: black;"><b>Múltiplos usuários encontrados com o e-mail ' . $emailUsuario . '.</p></b>';
    }

    // Prepara uma consulta SQL para buscar dados na tabela `informacoes`
    $sql = "SELECT id_info, nome, cnpj, email, telefone, rua, cep, cidade FROM `informacoes`";
    $resultado = mysqli_query($conexao, $sql);

    // Obtém dados do resultado da consulta
    while ($array = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        $id_info = $array['id_info'];
        $nome = $array['nome'];
        $email = $array['email'];
        $telefone = $array['telefone'];
    }
    ?>
    <div class="container">
        <div class="d-flex justify-content-center align-items-center">
            <img src="img/cadeado.jpeg" class="logo" width="110px" height="110px" alt="">
            <table>
                <tr>
                    <td>Empresa:</td>
                    <td>
                        <?php echo $nome; ?>
                    </td>
                </tr>
                <tr>
                    <td>Contato:</td>
                    <td>
                        <?php echo $email; ?>
                    </td>
                </tr>
                <tr>
                    <td>Telefone:</td>
                    <td>
                        <?php echo $telefone; ?>
                    </td>
                </tr>
            </table>
        </div>
        <form action="logar.php" method="POST">
            <div class="form-group">
                <label for="usuario" class="label">E-mail</label>
                <input type="email" name="usuario" id="usuario" class="form-control" placeholder="Digite o e-mail do usuário" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="senha" class="label">Senha</label>
                <input type="password" name="senha" id="senha" class="form-control" placeholder="Digite sua senha" autocomplete="off">
            </div>
            <br>
            <button type="submit" class="btn btn-success btn-block">Entrar</button>
            <br>
        </form>
    </div>
</body>

</html>