<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="monitor.png" type="image/x-icon">
    <title>XML</title>
    <style>
        body {
            background: linear-gradient(to bottom, #2a9d8f, #264653);
            color: #333;
            font-family: "Roboto", Arial, sans-serif;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-weight: 400;
            box-sizing: border-box;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color:rgb(16, 17, 17);
        }

        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: left;
        }

        label {
            font-size: 16px;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"],
        input[type="hidden"] {
            width: 94%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        a {
            font-weight: bold;
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
        }

        .message {
            font-size: 18px;
            color: #dc3545;
            margin-top: 20px;
            font-weight: bold;
        }

        @media only screen and (max-width: 768px) {
            form {
                padding: 15px;
            }

            h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <?php

    // Conectar ao banco de dados (substitua 'seu_host', 'seu_usuario', 'sua_senha' e 'seu_banco' pelos seus valores)
    include_once '../conexao.php';

    // Verificar se o ID foi fornecido na URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Consultar o item específico no banco de dados
        $result = $conexao->query("SELECT * FROM importaxml WHERE id = $id");

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Exibir um formulário para editar o CFOP
            echo "<h2>Editar informações do XML</h2>";
            echo "<form action='processar_edicao.php' method='post'>";
            echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
            echo "<label for='newCFOP'>CFOP Padrão:</label>";
            echo "<input type='text' name='newCFOP' value='" . $row['cfop'] . "'>";
            echo "<label for='newunidadeMedida'>Medida Padrão:</label>";
            echo "<input type='text' name='newunidadeMedida' value='" . $row['unidadeMedida'] . "'>";
            echo "<input type='submit' value='Alterar'>";
            echo "</form>";
        } else {
            echo "<p class='message'>Item não encontrado.</p>";
        }
    } else {
        echo "<p class='message'>ID não fornecido.</p>";
    }

    $conexao->close();

    ?>
</body>
</html>
