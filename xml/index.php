<?php
include_once '../iniciar_sessao.php';
include_once('../head.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="monitor.png" type="image/x-icon">
    <title>Leitor de NF XML</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom, #2a9d8f, #264653);
            color: #f4f4f9;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        h2 {
            font-size: 2rem;
            color: #e9c46a;
            margin-bottom: 20px;
        }

        form {
            background: #264653;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        form input[type="file"] {
            margin-top: 10px;
            margin-bottom: 15px;
            width: 100%;
            padding: 10px;
            border: 1px solid #e9c46a;
            border-radius: 5px;
            background-color: #f4f4f9;
            color: #264653;
        }

        form input[type="submit"] {
            background-color: #e76f51;
            color: #f4f4f9;
            font-size: 1rem;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        form input[type="submit"]:hover {
            background-color: #e63946;
        }

        a button {
            background-color: #2a9d8f;
            color: #f4f4f9;
            font-size: 1rem;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        a button:hover {
            background-color: #21867a;
        }

        p {
            margin-top: 20px;
            background: #1d3557;
            padding: 15px;
            border-radius: 8px;
            color: #f4f4f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            form {
                padding: 15px;
            }

            p {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <h2>Leitor de NF XML</h2>
    <form action="processar_xml.php" method="post" enctype="multipart/form-data">
        <label for="xmlFile">Selecione o arquivo XML da Nota Fiscal:</label><br>
        <input type="file" id="xmlFile" name="xmlFile" accept=".xml">
        <br><br>
        <input type="submit" value="Processar">
    </form>
    <br>
    <a href="editar_xml.php"><button>Listagem de importações</button></a>
    <br>
    <a href="../sair.php"><button>Sair</button></a>
    <p>Esta interface permite a leitura e o armazenamento de XMLs de compra para posterior consulta e download. Ao processar os XMLs, os dados pertinentes serão armazenados para uso futuro, garantindo acesso rápido e eficiente às informações.</p>
</body>
</html>
