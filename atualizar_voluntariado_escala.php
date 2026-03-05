<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber dados do formulário
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $bateria = $_POST['bateria'] ?? '';
    $violao = $_POST['violao'] ?? '';
    $teclado = $_POST['teclado'] ?? '';
    $baixo = $_POST['baixo'] ?? '';
    $ministro = $_POST['ministro'] ?? '';
    $vocal1 = $_POST['vocal1'] ?? '';
    $vocal2 = $_POST['vocal2'] ?? '';
    $vocal3 = $_POST['vocal3'] ?? '';
    $talckback = $_POST['talckback'] ?? '';
    $igreja = $_POST['igreja'] ?? '';
    $live = $_POST['live'] ?? '';
    $somkids = $_POST['somkids'] ?? '';
    $igreja_noite = $_POST['igreja_noite'] ?? '';
    $ct = $_POST['ct'] ?? '';
    $c1 = $_POST['c1'] ?? '';
    $c2 = $_POST['c2'] ?? '';
    $lt = $_POST['lt'] ?? '';
    $lz = $_POST['lz'] ?? '';
    $ph = $_POST['ph'] ?? '';
    $danca_manha = $_POST['danca_manhã'] ?? '';
    $danca_noite = $_POST['danca_noite'] ?? '';
    $realtime = $_POST['real_time'] ?? '';
    $realtimekids = $_POST['real_time_kids'] ?? '';
    $recap = $_POST['recap'] ?? '';
    $real_time_treinamento = $_POST['real_time_treinamento'] ?? '';
    $recap_treinamento = $_POST['recap_treinamento'] ?? '';
    $real_time_manha = $_POST['real_time_manhã'] ?? ''; // sem acento
    $real_time_noite  = $_POST['real_time_noite'] ?? '';
    $real_time_mulheres = $_POST['real_time_mulheres'] ?? '';
    $real_time_jovens = $_POST['real_time_jovens'] ?? '';
    $staff1 = $_POST['staff1'] ?? '';
    $staff2 = $_POST['staff2'] ?? '';
    $prof1_manha = $_POST['prof1_manhã'] ?? '';
    $prof2_manha = $_POST['prof2_manhã'] ?? '';
    $prof1_noite = $_POST['prof1_noite'] ?? '';
    $prof2_noite = $_POST['prof2_noite'] ?? '';
    
    // Verificar se uma nova imagem foi enviada via cropper
    $foto_atual = $_POST['foto_atual'] ?? '';
    $foto_crop = $_POST['foto_crop'] ?? '';
    
    // Verificar se é uma nova imagem (data URL base64)
    if (!empty($foto_crop) && $foto_crop !== $foto_atual && strpos($foto_crop, 'data:image') === 0) {
        // Processar a nova imagem
        $foto_perfil = processarImagem($foto_crop, $id);
    } else {
        // Manter a imagem existente
        $foto_perfil = $foto_atual;
    }
    
    // DEBUG: Verificar valores
    error_log("Total de campos: 36 (35 strings + 1 inteiro)");
    error_log("Foto final: " . $foto_perfil);
    
    // Atualizar no banco de dados - CONTE OS CAMPOS: são 35 campos string + 1 inteiro
    $sql = "UPDATE musicos SET 
            nome = ?, bateria = ?, violao = ?, teclado = ?, baixo = ?, 
            ministro = ?, vocal1 = ?, vocal2 = ?, vocal3 = ?, talckback = ?, 
            igreja = ?, live = ?, somkids = ?, igreja_noite = ?, ct = ?, c1 = ?, c2 = ?, 
            lt = ?, lz = ?, ph = ?, danca_manhã = ?, danca_noite = ?, real_time = ?, real_time_kids = ?, 
            recap = ?, real_time_treinamento = ?, recap_treinamento = ?, 
            real_time_manhã = ?, real_time_noite = ?, real_time_mulheres = ?, 
            real_time_jovens = ?, staff1 = ?, staff2 = ?, prof1_manhã = ?, prof2_manhã = ?, prof1_noite = ?, prof2_noite = ?, foto = ? 
            WHERE id = ?";
    
    // String de tipos: 35 's' para strings + 1 'i' para inteiro = 36 caracteres
    $stmt = $conexao->prepare($sql);
    
    if ($stmt === false) {
        $_SESSION['erro'] = "Erro na preparação da query: " . $conexao->error;
        header("Location: editar_voluntario.php?id=$id");
        exit();
    }
    
    // Vincular parâmetros - VERIFIQUE que há 36 variáveis para 36 placeholders
    $stmt->bind_param("ssssssssssssssssssssssssssssssssssssssi", 
        $nome, $bateria, $violao, $teclado, $baixo, 
        $ministro, $vocal1, $vocal2, $vocal3, $talckback, 
        $igreja, $live, $somkids, $igreja_noite, $ct,
        $c1, $c2, $lt, $lz, $ph, 
        $danca_manha, $danca_noite, $realtime, $realtimekids, $recap, $real_time_treinamento, 
        $recap_treinamento, $real_time_manha, $real_time_noite, $real_time_mulheres, $real_time_jovens, 
        $staff1, $staff2, $prof1_manha, $prof2_manha, $prof1_noite, 
        $prof2_noite, $foto_perfil, $id);
    
    if ($stmt->execute()) {
        // Limpar imagem da sessão se existir
        if (isset($_SESSION['imagem_cortada'])) {
            unset($_SESSION['imagem_cortada']);
        }
        
        $_SESSION['mensagem'] = "Voluntário atualizado com sucesso!";
        header('Location: consulta_voluntariado.php');
    } else {
        $_SESSION['erro'] = "Erro ao atualizar voluntário: " . $stmt->error;
        header("Location: editar_voluntario.php?id=$id");
    }
    
    $stmt->close();
    exit();
}

function processarImagem($data_url, $id) {
    // Verificar se é uma data URL válida
    if (strpos($data_url, 'data:image') !== 0) {
        return $data_url; // Retorna o valor original se não for data URL
    }
    
    // Separar o tipo e os dados da imagem
    $parts = explode(';', $data_url);
    if (count($parts) < 2) return '';
    
    $type = $parts[0];
    $data = $parts[1];
    
    // Verificar se é base64
    if (strpos($data, 'base64,') !== false) {
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);
    } else {
        $data = base64_decode($data);
    }
    
    // Verificar se a decodificação foi bem-sucedida
    if ($data === false) {
        return '';
    }
    
    // Determinar a extensão baseada no tipo
    $extensao = 'jpg';
    if (strpos($type, 'png') !== false) {
        $extensao = 'png';
    } elseif (strpos($type, 'gif') !== false) {
        $extensao = 'gif';
    }
    
    // Gerar nome único para o arquivo
    $nome_arquivo = 'voluntario_' . $id . '_' . time() . '.' . $extensao;
    $caminho_arquivo = 'uploads/' . $nome_arquivo;
    
    // Criar diretório se não existir
    if (!file_exists('uploads')) {
        mkdir('uploads', 0755, true);
    }
    
    // Salvar a imagem
    if (file_put_contents($caminho_arquivo, $data)) {
        return $caminho_arquivo;
    } else {
        error_log("Erro ao salvar imagem: " . $caminho_arquivo);
        return '';
    }
}
?>