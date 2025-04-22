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
    $nivel_acesso = $_POST['nivel_acesso'];

    $result = mysqli_query($conexao, "INSERT INTO cadastroAdm(nome,usuario,senha,email,telefone,celular,nivel_acesso) 
    VALUES ('$nome','$usuario','$senha','$email','$telefone','$celular','$nivel_acesso')");

header('Location: sistema.php');
}
?>