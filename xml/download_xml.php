<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    include_once '../conexao.php';

    // Verificar a conexão
    if ($conexao->connect_error) {
        die("Erro de conexão: " . $conexao->connect_error);
    }

    // Consulta para obter o caminho do arquivo XML
    $result = $conexao->query("SELECT xmlFilePath FROM importaxml WHERE id = $id");

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $xmlFilePath = $row['xmlFilePath'];

        // Realizar o download do XML
        header('Content-Type: application/xml');
        header('Content-Disposition: attachment; filename="' . basename($xmlFilePath) . '"');
        readfile($xmlFilePath);
        exit;
    }
}

echo "ID inválido ou arquivo não encontrado.";
?>
