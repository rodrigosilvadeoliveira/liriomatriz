<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('config.php');
if(isset($_POST['submitAdm']))
{
    //print ('Nome: ' . $_POST['nome']);
    //print('<br>');
   
    
    $nome = $_POST['nome'];
    $usuario=$_POST['usuario'];
    $senha = $_POST['senha'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $celular = $_POST['celular'];
    $nivel_acesso = $_POST['nivel_acesso'];

    $result = mysqli_query($conexao, "INSERT INTO cadastroadm(nome,usuario,senha,email,telefone,celular,nivel_acesso) 
    VALUES ('$nome','$usuario','$senha','$email','$telefone','$celular','$nivel_acesso')");

header('Location: sistema.php');
}
?>