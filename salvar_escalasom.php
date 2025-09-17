<?php
include('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'salvar_escalasom') {
    $response = ['success' => false, 'message' => ''];
    
    try {
        // Obter dados da escala
        $dados = json_decode($_POST['dados'], true);
        
        if (!$dados || !isset($dados['nome'])) {
            throw new Exception('Dados da escala inválidos.');
        }
        
        // Verificar se já existe uma escala com este nome
        $nome = $conexao->real_escape_string($dados['nome']);
        $checkSql = "SELECT id FROM escalas_som WHERE nome = '$nome'";
        $checkResult = $conexao->query($checkSql);
        
        if ($checkResult && $checkResult->num_rows > 0) {
            throw new Exception('Já existe uma escala com este nome.');
        }
        
        // Processar o arquivo PDF
        $pdfPath = '';
        if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'pdf_escalas/';
            
            // Criar diretório se não existir
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Gerar nome único para o arquivo
            $fileName = uniqid('escala_') . '.pdf';
            $pdfPath = $uploadDir . $fileName;
            
            // Mover arquivo para o diretório
            if (!move_uploaded_file($_FILES['pdf']['tmp_name'], $pdfPath)) {
                throw new Exception('Erro ao salvar o arquivo PDF.');
            }
        }
        
        // Preparar dados para inserção
        $descricao = isset($dados['descricao']) ? $conexao->real_escape_string($dados['descricao']) : '';
        $dadosEscala = $conexao->real_escape_string(json_encode($dados, JSON_UNESCAPED_UNICODE));
        $pdfPathDb = $conexao->real_escape_string($pdfPath);
        $dataCriacao = date('Y-m-d H:i:s');
        
        // Inserir no banco de dados
        $sql = "INSERT INTO escalas_som (nome, descricao, dados_escala, pdf_path, data_criacao) 
                VALUES ('$nome', '$descricao', '$dadosEscala', '$pdfPathDb', '$dataCriacao')";
        
        if ($conexao->query($sql)) {
            $response['success'] = true;
            $response['message'] = 'Escala salva com sucesso!';
        } else {
            // Se falhar a inserção, remover o arquivo PDF
            if ($pdfPath && file_exists($pdfPath)) {
                unlink($pdfPath);
            }
            throw new Exception('Erro ao salvar no banco de dados: ' . $conexao->error);
        }
        
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}