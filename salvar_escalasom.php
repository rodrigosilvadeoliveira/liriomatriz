<?php
include_once('config.php');
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

// Verificar se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

// Verificar se a ação é salvar_escala
if (!isset($_POST['acao']) || $_POST['acao'] !== 'salvar_escala') {
    echo json_encode(['success' => false, 'message' => 'Ação não especificada']);
    exit;
}

// Verificar se os dados foram enviados
if (!isset($_POST['dados'])) {
    echo json_encode(['success' => false, 'message' => 'Dados não enviados']);
    exit;
}

$usuario = $_SESSION['usuario'];
$dados = json_decode($_POST['dados'], true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

// Verificar se já existe um registro com ID = 1
$sqlCheck = "SELECT id FROM escalas_salvas WHERE id = 2";
$resultCheck = $conexao->query($sqlCheck);

if ($resultCheck && $resultCheck->num_rows > 0) {
    // Atualizar escala existente com ID = 1
    $sql = "UPDATE escalas_salvas SET nome = ?, descricao = ?, dados_escala = ?, usuario = ?, data_atualizacao = NOW() WHERE id = 2";
    $stmt = $conexao->prepare($sql);
    $dadosEscala = json_encode($dados, JSON_UNESCAPED_UNICODE);
    $stmt->bind_param("ssss", $dados['nome'], $dados['descricao'], $dadosEscala, $usuario);
} else {
    // Inserir nova escala com ID = 1
    $sql = "INSERT INTO escalas_salvas (id, nome, descricao, dados_escala, usuario) VALUES (1, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $dadosEscala = json_encode($dados, JSON_UNESCAPED_UNICODE);
    $stmt->bind_param("ssss", $dados['nome'], $dados['descricao'], $dadosEscala, $usuario);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Escala salva com sucesso']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar escala: ' . $conexao->error]);
}

// Fechar statement se existir
if (isset($stmt)) {
    $stmt->close();
}

$conexao->close();
?>