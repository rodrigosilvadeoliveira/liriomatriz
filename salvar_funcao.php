<?php
header('Content-Type: application/json');

include_once('config.php');
session_start();

function respond($data){
    echo json_encode($data);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(['success' => false, 'message' => 'Método inválido']);
}

$nome = trim($_POST['nome'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');
$igreja = intval($_POST['igreja_id'] ?? 0);

if (!$nome || !$tipo || !$igreja) {
    respond(['success' => false, 'message' => 'Preencha todos os campos']);
}

// evitar duplicidade
$stmt = $conexao->prepare("
    SELECT id FROM funcoes 
    WHERE nome = ? AND igreja_id = ?
");
$stmt->bind_param("si", $nome, $igreja);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    respond(['success' => false, 'message' => 'Função já existe']);
}

// inserir
$stmt = $conexao->prepare("
    INSERT INTO funcoes (nome, tipo, igreja_id)
    VALUES (?, ?, ?)
");

$stmt->bind_param("ssi", $nome, $tipo, $igreja);

if ($stmt->execute()) {
    respond(['success' => true]);
} else {
    respond(['success' => false, 'message' => 'Erro ao salvar']);
}