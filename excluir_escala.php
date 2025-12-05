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

include('registroslog.php');;

// Receber dados
$id = $_POST['id'] ?? 0;
$tabela = $_POST['tabela'] ?? 'escalas_louvor';

// Validar tabela permitida
$tabelas_permitidas = ['escalas_louvor', 'escalas_homens', 'escalas_louvorkids'];
if (!in_array($tabela, $tabelas_permitidas)) {
    die("Tabela não permitida.");
}


// Excluir registro
$sql = "DELETE FROM $tabela WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Opcional: excluir arquivo PDF associado
    $sql_file = "SELECT pdf_path FROM $tabela WHERE id = ?";
    $stmt_file = $conexao->prepare($sql_file);
    $stmt_file->bind_param("i", $id);
    $stmt_file->execute();
    $result = $stmt_file->get_result();
    
    if ($row = $result->fetch_assoc() && !empty($row['pdf_path']) && file_exists($row['pdf_path'])) {
        unlink($row['pdf_path']);
    }
    
    echo "ok";
} else {
    echo "Erro ao excluir: " . $conexao->error;
}
?>