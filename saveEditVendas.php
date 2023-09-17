<?php
    // isset -> serve para saber se uma variável está definida
    include_once('config.php');
    if(isset($_POST['update']))
    {
        $id = $_POST['id'];
        $barra = $_POST['barra'];
        $produto = $_POST['produto'];
        $modelo = $_POST['modelo'];
        $tamanho = $_POST['tamanho'];
        $categoria = $_POST['categoria'];
        $valordevenda = $_POST['valordevenda'];
        $usuario = $_POST['usuario'];
        
        $sqlInsert = "UPDATE vendas 
        SET barra='$barra',produto='$produto',modelo='$modelo',tamanho='$tamanho',categoria='$categoria',valordevenda='$valordevenda',usuario='$usuario'
        WHERE id=$id";
        $result = $conexao->query($sqlInsert);
        print_r($result);
    }
    header('Location: vendasrealizadas.php');

?>