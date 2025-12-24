<?php
include('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'salvar_escalalouvornoite') {
    $response = ['success' => false, 'message' => ''];
    
    try {
        // Obter dados da escala
        $dados = json_decode($_POST['dados'], true);
        
        if (!$dados || !isset($dados['nome'])) {
            throw new Exception('Dados da escala inválidos.');
        }
        
        // Verificar se já existe uma escala com este nome
        $nome = $conexao->real_escape_string($dados['nome']);
        $checkSql = "SELECT id FROM escalas_louvornoite WHERE nome = '$nome'";
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
            
            // Obter o nome do arquivo do POST ou usar um padrão
            $fileName = isset($_POST['fileName']) ? $_POST['fileName'] : uniqid('escala_') . '.pdf';
            
            // Limpar o nome do arquivo para segurança
            $fileName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $fileName);
            
            // Garantir que termina com .pdf
            if (!preg_match('/\.pdf$/i', $fileName)) {
                $fileName .= '.pdf';
            }
            
            $pdfPath = $uploadDir . $fileName;
            
            // Verificar se o arquivo já existe e adicionar sufixo numérico se necessário
            $counter = 1;
            $originalFileName = $fileName;
            while (file_exists($pdfPath)) {
                $fileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '_' . $counter . '.pdf';
                $pdfPath = $uploadDir . $fileName;
                $counter++;
            }
            
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
        $sql = "INSERT INTO escalas_louvornoite (nome, descricao, dados_escala, pdf_path, data_criacao) 
                VALUES ('$nome', '$descricao', '$dadosEscala', '$pdfPathDb', '$dataCriacao')";
        
        if ($conexao->query($sql)) {
            $response['success'] = true;
            $response['message'] = 'Escala salva com sucesso!';
            $response['pdf_path'] = $pdfPath; // Opcional: retornar o caminho do PDF
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