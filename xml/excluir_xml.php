<?php

include_once '../conexao.php';

$id = $_GET['id'];

$sql = "DELETE FROM importaxml WHERE id = $id";
if ($conexao->query($sql) === TRUE) {
    header("Location: editar_xml.php?excluido=".$id); // Redireciona para a página de listagem de XMLs após exclusão
} else {
    echo "Erro ao excluir o XML: " . $conexao->error;
}

$conexao->close();

?>
