<?php
header('Content-Type: application/json');
include_once('config.php');

$id = intval($_POST['id'] ?? 0);

if(!$id){
    echo json_encode(['success'=>false,'message'=>'ID inválido']);
    exit;
}

// 🔥 opcional: verificar uso antes de excluir
$check = $conexao->prepare("
    SELECT id FROM voluntario_funcoes WHERE funcao_id = ?
");
$check->bind_param("i", $id);
$check->execute();

if($check->get_result()->num_rows > 0){
    echo json_encode([
        'success'=>false,
        'message'=>'Função está vinculada a voluntários'
    ]);
    exit;
}

$stmt = $conexao->prepare("DELETE FROM funcoes WHERE id = ?");
$stmt->bind_param("i", $id);

echo json_encode(['success'=>$stmt->execute()]);