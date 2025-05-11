<?php
// Conexão com o banco de dados (substitua as credenciais pelo seu ambiente)
include_once('config.php');

// Verifique se o ID do pedido foi fornecido na solicitação
if (isset($_POST['idPedido'])) {
    $idPedido = $_POST['idPedido'];
    
    // Atualize o status do pedido para "concluído" (ou o status desejado) no banco de dados
    $novoStatus = 'concluído'; // Altere para o status desejado
    $sql = "UPDATE pedidos SET status = '$novoStatus' WHERE id_pedidos = $idPedido";
    
    if ($conexao->query($sql) === TRUE) {
        // Envie um cabeçalho de resposta para informar ao JavaScript que a atualização foi bem-sucedida
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);

        // Feche a conexão com o banco de dados
        $conexao->close();
    } else {
        echo "Erro ao atualizar o status do pedido: " . $conexao->error;
    }
} else {
    echo "ID do pedido não fornecido na solicitação.";
}
header('Location: consultarPedido.php');
?>
