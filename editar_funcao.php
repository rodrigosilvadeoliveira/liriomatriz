<?php
header('Content-Type: application/json');
include_once('config.php');

$id = intval($_POST['id'] ?? 0);
$nome = trim($_POST['nome'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');

if(!$id || !$nome || !$tipo){
    echo json_encode(['success'=>false,'message'=>'Dados inválidos']);
    exit;
}

$stmt = $conexao->prepare("
    UPDATE funcoes 
    SET nome = ?, tipo = ?
    WHERE id = ?
");

$stmt->bind_param("ssi", $nome, $tipo, $id);

echo json_encode(['success' => $stmt->execute()]);