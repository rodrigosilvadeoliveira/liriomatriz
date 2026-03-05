<?php
session_start();
include_once('config.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber dados do formulário
    $id = $_POST['cadastroadm_id'];
    
    // Verificar se uma nova imagem foi enviada
    if (!empty($_POST['foto_crop']) && strpos($_POST['foto_crop'], 'data:image') === 0) {
        // Processar a nova imagem
        $foto_crop = $_POST['foto_crop'];
        $foto_perfil = processarImagem($foto_crop, $id);
    } else {
        // Manter a imagem existente
        $foto_perfil = $_POST['foto_atual'] ?? '';
    }
    
    // Atualizar no banco de dados
    $sql = "UPDATE musicos SET 
            foto = ? 
            WHERE cadastroadm_id = ?";
    
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("si", 
        $foto_perfil, $id);
    
    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Voluntário atualizado com sucesso!";
        header('Location: perfil.php');
    } else {
        $_SESSION['erro'] = "Erro ao atualizar voluntário: " . $conexao->error;
        header("Location: alterarfoto.php?id=$id");
    }
    
    $stmt->close();
    exit();
}

function processarImagem($data_url, $id) {
    // Separar o tipo e os dados da imagem
    list($type, $data) = explode(';', $data_url);
    list(, $data) = explode(',', $data);
    $data = base64_decode($data);
    
    // Gerar nome único para o arquivo
    $nome_arquivo = 'voluntario_' . $id . '_' . time() . '.jpg';
    $caminho_arquivo = 'uploads/' . $nome_arquivo;
    
    // Criar diretório se não existir
    if (!file_exists('uploads')) {
        mkdir('uploads', 0755, true);
    }
    
    // Salvar a imagem
    file_put_contents($caminho_arquivo, $data);
    
    return $caminho_arquivo;
}
?>