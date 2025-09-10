<?php
session_start();
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber dados do formulário
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $bateria = $_POST['bateria'];
    $violao = $_POST['violao'];
    $teclado = $_POST['teclado'];
    $baixo = $_POST['baixo'];
    $ministro = $_POST['ministro'];
    $vocal1 = $_POST['vocal1'];
    $vocal2 = $_POST['vocal2'];
    $vocal3 = $_POST['vocal3'];
    $talckback = $_POST['talckback'];
    $igreja = $_POST['igreja'];
    $live = $_POST['live'];
    $somkids = $_POST['somkids'];
    $ct = $_POST['ct'];
    $c1 = $_POST['c1'];
    $c2 = $_POST['c2'];
    $lt = $_POST['lt'];
    $lz = $_POST['lz'];
    $ph = $_POST['ph'];
    $danca = $_POST['danca'];
    $realtime = $_POST['real_time'];
    $realtimekids = $_POST['real_time_kids'];
    $recap = $_POST['recap'];
    // Verificar se uma nova imagem foi enviada
    if (!empty($_POST['foto_crop']) && strpos($_POST['foto_crop'], 'data:image') === 0) {
        // Processar a nova imagem
        $foto_crop = $_POST['foto_crop'];
        $foto_perfil = processarImagem($foto_crop, $id);
    } else {
        // Manter a imagem existente
        $foto_perfil = $_POST['foto_crop'];
    }
    
    // Atualizar no banco de dados
    $sql = "UPDATE musicos SET 
            nome = ?, bateria = ?, violao = ?, teclado = ?, baixo = ?, 
            ministro = ?, vocal1 = ?, vocal2 = ?, vocal3 = ?, talckback = ?, 
            igreja = ?, live = ?, somkids = ?, ct = ?, c1 = ?, c2 = ?, 
            lt = ?, lz = ?, ph = ?, danca = ?, real_time = ?, real_time_kids = ?, recap = ?, foto = ? 
            WHERE id = ?";
    
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssssssssssssssssssssssssi", 
        $nome, $bateria, $violao, $teclado, $baixo, 
        $ministro, $vocal1, $vocal2, $vocal3, $talckback, 
        $igreja, $live, $somkids, $ct, $c1, $c2, 
        $lt, $lz, $ph, $danca, $realtime, $realtimekids, $recap, $foto_perfil, $id);
    
    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Voluntário atualizado com sucesso!";
        header('Location: consulta_voluntariado.php');
    } else {
        $_SESSION['erro'] = "Erro ao atualizar voluntário: " . $conexao->error;
        header("Location: editar_voluntario.php?id=$id");
    }
    
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
    
    // Salvar a imagem
    file_put_contents($caminho_arquivo, $data);
    
    return $caminho_arquivo;
}
?>