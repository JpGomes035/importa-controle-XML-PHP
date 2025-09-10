<?php

include_once '../conexao.php';

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar se os campos necessários foram fornecidos
    if (isset($_POST['id']) && isset($_POST['newCFOP'])) {
        $id = $_POST['id'];
        $newCFOP = $_POST['newCFOP'];
        $newunidadeMedida = $_POST['newunidadeMedida'];

        // Atualizar o CFOP no banco de dados
        $stmt = $conexao->prepare("UPDATE importaxml SET cfop = ?, unidadeMedida = ? WHERE id = ?");
        $stmt->bind_param('ssi', $newCFOP, $newunidadeMedida, $id);
        $stmt->execute();
        $stmt->close();

        echo "Ajuste feito com sucesso.";
    } else {
        echo "Campos não fornecidos.";
    }
} else {
    echo "Acesso inválido.";
}

$conexao->close();

?>
<br>
<a href="editar_xml.php">Voltar</a>
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
            color: #f4f4f9;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            box-sizing: border-box;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            font-weight: bold;
            color: #ffffff;
            background-color: #e76f51;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }

        a:hover {
            background-color: #f4a261;
            color: #000;
        }

        br+a {
            margin-top: 30px;
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
                font-size: 14px;
            }

            a {
                padding: 8px 15px;
            }
        }
    </style>
</head>

<body>

</body>

</html>