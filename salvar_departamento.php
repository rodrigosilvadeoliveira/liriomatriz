<?php

header('Content-Type: application/json; charset=utf-8');

include_once('config.php');

session_start();

// ===========================
// RESPOSTA JSON
// ===========================
function respond($data){

    echo json_encode($data);

    exit;
}

// ===========================
// VALIDAR MÉTODO
// ===========================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    respond([
        'success' => false,
        'message' => 'Método inválido'
    ]);
}

// ===========================
// DADOS
// ===========================
$tipo   = trim($_POST['tipo'] ?? '');
$igreja = intval($_SESSION['igreja_id'] ?? 0);

// ===========================
// VALIDAR
// ===========================
if (!$tipo || !$igreja) {

    respond([
        'success' => false,
        'message' => 'Preencha todos os campos'
    ]);
}

// ===========================
// EVITAR DUPLICIDADE
// ===========================
$stmt = $conexao->prepare("
    SELECT id
    FROM categoria_escala
    WHERE tipo = ?
    AND igreja_id = ?
");

$stmt->bind_param(
    "si",
    $tipo,
    $igreja
);

$stmt->execute();

$res = $stmt->get_result();

if ($res->num_rows > 0) {

    respond([
        'success' => false,
        'message' => 'Departamento já existe'
    ]);
}

// ===========================
// INSERIR
// ===========================
$stmt = $conexao->prepare("
    INSERT INTO categoria_escala
    (
        tipo,
        igreja_id,
        created_at,
        updated_at
    )
    VALUES
    (
        ?,
        ?,
        NOW(),
        NOW()
    )
");

$stmt->bind_param(
    "si",
    $tipo,
    $igreja
);

// ===========================
// EXECUTAR
// ===========================
if ($stmt->execute()) {

    respond([
        'success' => true
    ]);

} else {

    respond([
        'success' => false,
        'message' => 'Erro ao salvar'
    ]);
}