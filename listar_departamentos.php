<?php

header('Content-Type: application/json; charset=utf-8');

include_once('config.php');

session_start();

$igreja = $_SESSION['igreja_id'] ?? 0;

// ===========================
// BUSCAR DEPARTAMENTOS
// ===========================
$stmt = $conexao->prepare("
    SELECT 
        id,
        tipo
    FROM categoria_escala
    WHERE igreja_id = ?
    ORDER BY tipo ASC
");

$stmt->bind_param("i", $igreja);

$stmt->execute();

$res = $stmt->get_result();

$data = [];

while($r = $res->fetch_assoc()){

    $data[] = [
        'id'   => $r['id'],
        'tipo' => $r['tipo']
    ];
}

// ===========================
// RETORNO JSON
// ===========================
echo json_encode([
    'success' => true,
    'data'    => $data
]);