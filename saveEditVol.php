<?php
    // isset -> serve para saber se uma variável está definida
    include_once('config.php');
    if(isset($_POST['update']))
    {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $usuario = $_POST['usuario'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $telefone = $_POST['telefone'];
        $celular = $_POST['celular'];

        
        $sqlInsert = "UPDATE cadastroadm 
        SET nome='$nome',usuario='$usuario',senha='$senha',email='$email',telefone='$telefone',celular='$celular'
        WHERE id=$id";
        $result = $conexao->query($sqlInsert);
        print_r($result);
    }
    header('Location: consulta_voluntarios.php');

?>