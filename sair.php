<?php
include_once '../iniciar_sessao.php';
include_once '../conexao.php';
include_once('../head.php');

if (isset($_SESSION['usuario'])) {
    $idUsuario = $_SESSION['usuario'];
    $sqlUpdateOffline = "UPDATE usuario SET Online = 0 WHERE IdUsuario = $idUsuario";
    mysqli_query($conexao, $sqlUpdateOffline);
}

// Encerrar sessão e redirecionar para página de login
session_unset();
session_destroy();
header("Location: index.html");
exit();

mysqli_close($conexao);
?>
