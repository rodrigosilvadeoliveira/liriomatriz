<?php
date_default_timezone_set('America/Sao_Paulo');
include('verificarLogin.php');
verificarLogin();
include_once("config.php");

// Verifica sessão
if((!isset($_SESSION['usuario']) == true) && ($_SESSION['senha']) == true) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
}
$logado = $_SESSION['usuario'];

include('registroslog.php');

$id = intval($_POST['id'] ?? 0);

if($id > 0){
    $sql = "DELETE FROM escalas_louvor WHERE id = $id";
    if($conexao->query($sql)){
        echo "ok";
    } else {
        echo "Erro ao excluir.";
    }
} else {
    echo "ID inválido.";
}

?>