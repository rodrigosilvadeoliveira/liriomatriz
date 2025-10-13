<?php
// delete_musica.php

// Segurança e configuração
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');
include_once('config.php');

// Valida se veio um ID via GET ou POST
$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);

if ($id <= 0) {
    header("Location: musicas.php?error=invalid_id");
    exit;
}

// Busca a música no banco
$sql = "SELECT arquivo FROM musicas WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $arquivo = $row['arquivo'];
    $caminhoArquivo = "uploads/musicas/" . $arquivo;

    // Exclui o arquivo físico se existir
    if ($arquivo && file_exists($caminhoArquivo)) {
        unlink($caminhoArquivo);
    }

    // Exclui do banco
    $deleteSql = "DELETE FROM musicas WHERE id = ?";
    $deleteStmt = $conexao->prepare($deleteSql);
    $deleteStmt->bind_param("i", $id);

    if ($deleteStmt->execute()) {
        header("Location: musicas.php?success=deleted");
    } else {
        header("Location: musicas.php?error=db_error");
    }

    $deleteStmt->close();

} else {
    header("Location: musicas.php?error=not_found");
}

$stmt->close();
$conexao->close();
exit;
?>
