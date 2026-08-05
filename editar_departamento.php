<?php

header('Content-Type: application/json; charset=utf-8');

include_once('config.php');

session_start();

$id    = intval($_POST['id'] ?? 0);
$tipo  = trim($_POST['tipo'] ?? '');
$igreja = $_SESSION['igreja_id'] ?? 0;

// ===========================
// VALIDAR DADOS
// ===========================
if(!$id || !$tipo){

    echo json_encode([
        'success' => false,
        'message' => 'Dados inválidos'
    ]);

    exit;
}

// ===========================
// ATUALIZAR DEPARTAMENTO
// ===========================
$stmt = $conexao->prepare("
    UPDATE categoria_escala
    SET 
        tipo = ?,
        updated_at = NOW()
    WHERE id = ?
    AND igreja_id = ?
");

$stmt->bind_param(
    "sii",
    $tipo,
    $id,
    $igreja
);

// ===========================
// EXECUTAR
// ===========================
echo json_encode([
    'success' => $stmt->execute()
]);