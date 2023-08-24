<?php
    // isset -> serve para saber se uma variável está definida
    include_once('config.php');
    if(isset($_POST['update']))
    {
        $id = $_POST['id'];
        $produto = $_POST['produto'];
        $modelo = $_POST['modelo'];
        $tamanho = $_POST['tamanho'];
        $categoria = $_POST['categoria'];
        $valordevenda = $_POST['valordevenda'];
        $estoque = $_POST['estoque'];
        $valordecompra = $_POST['valordecompra'];
        $fornecedor = $_POST['fornecedor'];
        $barra = $_POST['barra'];
        
        $sqlInsert = "UPDATE produtos 
        SET produto='$produto', modelo='$modelo', tamanho='$tamanho', categoria='$categoria', valordevenda='$valordevenda', estoque='$estoque', valordecompra='$valordecompra', fornecedor='$fornecedor', barra='$barra'
        WHERE id=$id";
        $result = $conexao->query($sqlInsert);
        print_r($result);
    }
    header('Location: consultaProdutosAdm.php');

?>