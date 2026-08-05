<?php

header('Content-Type: application/json; charset=utf-8');

include_once('config.php');

session_start();

$id      = intval($_POST['id'] ?? 0);
$igreja  = intval($_SESSION['igreja_id'] ?? 0);

// ===========================
// VALIDAR ID
// ===========================
if(!$id){

    echo json_encode([
        'success' => false,
        'message' => 'ID inválido'
    ]);

    exit;
}

// ===========================
// VERIFICAR USO DO DEPARTAMENTO
// ===========================
// opcional:
// impede excluir categoria vinculada
// em funções cadastradas
$check = $conexao->prepare("
    SELECT id
    FROM funcoes
    WHERE tipo = (
        SELECT tipo
        FROM categoria_escala
        WHERE id = ?
        AND igreja_id = ?
    )
    LIMIT 1
");

$check->bind_param(
    "ii",
    $id,
    $igreja
);

$check->execute();

if($check->get_result()->num_rows > 0){

    echo json_encode([
        'success' => false,
        'message' => 'Departamento está vinculado a funções'
    ]);

    exit;
}

// ===========================
// EXCLUIR
// ===========================
$stmt = $conexao->prepare("
    DELETE FROM categoria_escala
    WHERE id = ?
    AND igreja_id = ?
");

$stmt->bind_param(
    "ii",
    $id,
    $igreja
);

// ===========================
// RETORNO
// ===========================
echo json_encode([
    'success' => $stmt->execute()
]);