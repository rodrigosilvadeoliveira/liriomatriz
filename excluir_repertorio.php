<?php
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');
include_once('config.php');

if(isset($_POST['id'])) {
    $idRepertorio = $_POST['id'];

    // Consulta para obter o caminho do arquivo antes de excluir
    $sqlSelect = "SELECT arquivo_repertorio FROM repertorio WHERE id = ?";
    $stmtSelect = $conexao->prepare($sqlSelect);
    $stmtSelect->bind_param("i", $idRepertorio);
    $stmtSelect->execute();
    $resultSelect = $stmtSelect->get_result();
    
    if ($resultSelect->num_rows > 0) {
        $row = $resultSelect->fetch_assoc();
        $caminhoArquivo = 'uploads/' . $row['arquivo_repertorio'];

        // Exclui o registro do banco de dados
        $sqlDelete = "DELETE FROM repertorio WHERE id = ?";
        $stmtDelete = $conexao->prepare($sqlDelete);
        $stmtDelete->bind_param("i", $idRepertorio);
        
        if ($stmtDelete->execute()) {
            // Se a exclusão do banco foi bem-sucedida, exclui o arquivo físico
            if (file_exists($caminhoArquivo)) {
                unlink($caminhoArquivo);
            }
            echo "ok";
        } else {
            echo "Erro ao excluir do banco de dados.";
        }
        $stmtDelete->close();
    } else {
        echo "Repertório não encontrado.";
    }
    
    $stmtSelect->close();
    $conexao->close();
} else {
    echo "ID do repertório não fornecido.";
}
?>