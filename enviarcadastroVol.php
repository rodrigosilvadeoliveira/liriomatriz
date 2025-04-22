<?php
session_start();
if(isset($_POST['submitAdm']))
{
    //print ('Nome: ' . $_POST['nome']);
    //print('<br>');
   
    include_once('config.php');
    
    $nome = $_POST['nome'];
    $usuario=$_POST['usuario'];
    $senha = $_POST['senha'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $celular = $_POST['celular'];

    $result = mysqli_query($conexao, "INSERT INTO cadastroVol(nome,usuario,senha,email,telefone,celular) 
    VALUES ('$nome','$usuario','$senha','$email','$telefone','$celular')");

header('Location: loginVoluntario.php');
}
?>