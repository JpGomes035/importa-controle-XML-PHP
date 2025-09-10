<?php
session_start();
include_once('conexao.php');
include_once('password.php');

$emailUsuario = trim($_POST['usuario']);
$senhaDigitada = trim($_POST['senha']);

$sql = "SELECT Email, Senha, IdUsuario FROM usuario WHERE Email = '$emailUsuario' AND Status = 'Ativo'";
$retornoEmailUsuario = mysqli_query($conexao, $sql);
$totalRetornado = mysqli_num_rows($retornoEmailUsuario);

if ($totalRetornado == 0) {
    header("Location: login.php?semCadastro=" . urlencode($emailUsuario));
    exit(); // Encerra o script após o redirecionamento
}

if ($totalRetornado >= 2) {
    header("Location: login.php?emailCadastrado=" . urlencode($emailUsuario));
    exit(); // Encerra o script após o redirecionamento
}

if ($totalRetornado == 1) {
    // Obter os dados do usuário
    $array = mysqli_fetch_array($retornoEmailUsuario, MYSQLI_ASSOC);
    $senhaCadastrada = $array['Senha'];
    $senhaDecodificada = sha1($senhaDigitada);

    if ($senhaDecodificada == $senhaCadastrada) {
        // Atualizar status para online
        $idUsuario = $array['IdUsuario'];
        $sqlUpdateOnline = "UPDATE usuario SET Online = '1' WHERE IdUsuario = $idUsuario";
        mysqli_query($conexao, $sqlUpdateOnline);

        $_SESSION['usuario'] = $array["IdUsuario"];
        header("Location: carregando_login.php");
        exit(); // Encerra o script após o redirecionamento
    } else {
        header("Location: login.php?dadosInvalidos=1");
        exit(); // Encerra o script após o redirecionamento
    }
}

mysqli_close($conexao);
