<?php

include_once '../conexao.php';

// Verifica se foi submetido um formulário de pesquisa
if (isset($_GET['search'])) {
    $search = $conexao->real_escape_string($_GET['search']);

    // Consulta com a cláusula WHERE para filtrar os resultados com base na pesquisa
    $query = "SELECT * FROM importaxml WHERE numeroNF LIKE '%$search%' OR nomeFornecedor LIKE '%$search%' OR cfop LIKE '%$search%' OR chaveAcesso LIKE '%$search%' OR cnpjFornecedor LIKE '%$search%'";
    $result = $conexao->query($query);

    // Verifica se ocorreu um erro na consulta
    if (!$result) {
        die("Erro na consulta: " . $conexao->error);
    }
} else {
    // Consulta para obter todos os dados da tabela se não houver pesquisa
    $result = $conexao->query("SELECT * FROM importaxml");

    // Verifica se ocorreu um erro na consulta
    if (!$result) {
        die("Erro na consulta: " . $conexao->error);
    }
}

// Exibir os dados em uma tabela
echo "<h2>XMLs Importados</h2>";
echo "<form method='GET' class='search-form'>";
echo "<input type='text' name='search' placeholder='Digite sua pesquisa'>";
echo "<input type='submit' value='Pesquisar'>";
echo "</form>";

echo "<table>";
echo "<thead>";
echo "<tr><th>ID</th><th>Número NF</th><th>Valor Total</th><th>Valor ICMS</th><th>Valor IPI</th><th>Valor PIS</th><th>Valor COFINS</th><th>Chave Acesso</th><th>Medida</th><th>Fornecedor</th><th>CNPJ Fornecedor</th><th>CFOP</th><th>Download</th><th>Editar XML</th><th>Excluir XML</th></tr>";
echo "</thead>";
echo "<tbody>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['numeroNF'] . "</td>";
    echo "<td>" . number_format($row['valorTotal'], 2, ',', '.') . "</td>";
    echo "<td>" . number_format($row['valorICMS'], 2, ',', '.') . "</td>";
    echo "<td>" . number_format($row['valorIPI'], 2, ',', '.') . "</td>";
    echo "<td>" . number_format($row['valorPIS'], 2, ',', '.') . "</td>";
    echo "<td>" . number_format($row['valorCOFINS'], 2, ',', '.') . "</td>";
    echo "<td>" . $row['chaveAcesso'] . "</td>";
    echo "<td>" . $row['unidadeMedida'] . "</td>";
    echo "<td>" . $row['nomeFornecedor'] . "</td>";
    echo "<td>" . $row['cnpjFornecedor'] . "</td>";
    echo "<td>" . $row['cfop'] . "</td>";
    echo "<td><a href='download_xml.php?id=" . $row['id'] . "' class='btn-download'>Download</a></td>";
    echo "<td><a href='editar_item.php?id=" . $row['id'] . "' class='btn-edit'>Editar</a></td>";
    echo "<td><a href='excluir_xml.php?id=" . $row['id'] . "' class='btn-delete' onclick='return confirm(\"Tem certeza que deseja excluir este XML?\")'>Excluir</a></td>";
    echo "</tr>";
}

echo "</tbody>";
echo "</table>";
echo "</div>";

$conexao->close();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../monitor.png" type="image/x-icon">
    <title>Gerenciamento de XML</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to bottom, #2a9d8f, #264653);
            font-family: "Roboto", Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
        }

        .search-form {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-form input[type="text"] {
            flex: 1;
            max-width: 400px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-form input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .search-form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
            padding: 12px;
            text-transform: uppercase;
            font-size: 14px;
            font-weight: bold;
        }

        td {
            background-color: #f9f9f9;
            padding: 12px;
            text-align: center;
            color: #333;
            font-size: 14px;
        }

        td a {
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        td a:hover {
            color: #007bff;
        }

        .btn-download,
        .btn-edit,
        .btn-delete {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            color: white;
            text-transform: uppercase;
            margin: 5px 0;
            transition: background-color 0.3s ease;
        }

        .btn-download {
            background-color: #28a745;
        }

        .btn-edit {
            background-color: #ffc107;
            color: #333;
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .btn-download:hover {
            background-color: #218838;
        }

        .btn-edit:hover {
            background-color: #e0a800;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .back-btn {
            display: inline-block;
            background-color: #007BFF;
            /* Azul elegante */
            color: #fff;
            /* Texto branco */
            font-size: 16px;
            /* Tamanho da fonte */
            font-weight: bold;
            /* Negrito */
            padding: 10px 20px;
            /* Espaçamento interno */
            border-radius: 5px;
            /* Bordas arredondadas */
            text-decoration: none;
            /* Remove o sublinhado */
            transition: all 0.3s ease;
            /* Animação suave */
        }

        .back-btn:hover {
            background-color: #0056b3;
            /* Azul mais escuro ao passar o mouse */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Sombra elegante */
            transform: translateY(-2px);
            /* Efeito de elevação */
        }

        .back-btn:active {
            background-color: #004085;
            /* Azul ainda mais escuro ao clicar */
            transform: translateY(0);
            /* Reseta o efeito de elevação */
        }


        @media only screen and (max-width: 768px) {

            table,
            th,
            td {
                font-size: 12px;
            }

            .search-form {
                flex-direction: column;
                gap: 5px;
            }

            .search-form input[type="text"] {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <a href="index.php" class="back-btn">Voltar para o Início</a>
</body>

</html>