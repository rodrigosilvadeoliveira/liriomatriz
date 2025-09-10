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
if (!isset($_POST['acao']) || $_POST['acao'] !== 'salvar_escalacriativo') {
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

// Preparar campos principais
$nome       = $dados['nome'];
$descricao  = $dados['descricao'] ?? '';
$dadosEscala = json_encode($dados, JSON_UNESCAPED_UNICODE);
$imagemPath = null;

// 🔥 Se veio uma imagem no POST, salvar em pasta
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $nomeArquivo = 'escala_' . time() . '_' . rand(1000, 9999) . '.png';
    $pastaDestino = __DIR__ . '/uploads_escalas';

    if (!is_dir($pastaDestino)) {
        mkdir($pastaDestino, 0777, true);
    }

    $destinoCompleto = $pastaDestino . '/' . $nomeArquivo;

    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destinoCompleto)) {
        // Salvar caminho relativo para acessar depois na aplicação
        $imagemPath = 'uploads_escalas/' . $nomeArquivo;
    }
}

// Sempre inserir um novo registro
$sql = "INSERT INTO escalas_criativo (nome, descricao, dados_escala, usuario, imagem, data_atualizacao) 
        VALUES (?, ?, ?, ?, ?, NOW())";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("sssss", $nome, $descricao, $dadosEscala, $usuario, $imagemPath);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true, 
        'message' => 'Escala salva com sucesso',
        'id' => $stmt->insert_id,
        'imagem' => $imagemPath
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar escala: ' . $conexao->error]);
}

// Fechar statement e conexão
$stmt->close();
$conexao->close();
?>
