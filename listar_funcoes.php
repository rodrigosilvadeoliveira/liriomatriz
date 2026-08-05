<?php
header('Content-Type: application/json');
include_once('config.php');
session_start();

$igreja = $_SESSION['igreja_id'] ?? 0;

$stmt = $conexao->prepare("
    SELECT id, nome, tipo 
    FROM funcoes 
    WHERE igreja_id = ?
    ORDER BY tipo ASC
");

$stmt->bind_param("i", $igreja);
$stmt->execute();

$res = $stmt->get_result();

$data = [];
while($r = $res->fetch_assoc()){
    $data[] = $r;
}

echo json_encode([
    'success' => true,
    'data' => $data
]);