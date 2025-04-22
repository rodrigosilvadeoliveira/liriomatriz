<?php
session_start();

if (isset($_GET['id'])) {
    $idProduto = $_GET['id'];

    // Percorra a lista de produtos na sessão
    foreach ($_SESSION['produtos'] as $indice => $produto) {
        // Verifique se o ID do produto corresponde ao ID fornecido na URL
        if ($produto['id'] == $idProduto) {
            // Remova o produto da lista
            unset($_SESSION['produtos'][$indice]);
            break;
        }
    }
}

// Redirecione de volta para a página anterior
header("Location: vendas.php");
exit();
?>