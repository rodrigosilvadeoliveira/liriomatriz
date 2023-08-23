<?php
session_start();
   // print_r($_REQUEST);

    if(isset($_POST['submit']) && !empty($_POST['usuario']) && !empty($_POST['senha']))
    {
//acessar sistema
        include_once('config.php');
        $usuario= $_POST['usuario'];
        $senha= $_POST['senha'];

        //print_r('usuario: ' . $usuario);
        //print_r('senha: ' . $senha);

        $sql = "SELECT * FROM cadastrovol WHERE usuario ='$usuario' and senha = '$senha'";

        $result = $conexao->query($sql);

        //mostra quantidade de registro e quais registros apos submit
       // print_r($sql);
       // print_r($result);

        //valida se tem o registro ou não pelo total de registro encontrados
        if(mysqli_num_rows($result) <1)
        {
            unset($_SESSION['usuario']);
            unset($_SESSION['senha']);
            print_r('Não existe registro');
            print_r('<a href="index.php" id="voltar">Voltar Pgina Home</a>');
        }else{
        $_SESSION['usuario'] = $usuario;
        $_SESSION['senha'] = $senha;
        header('Location: teste.php');
        exit(); 
    }
    {
        //não acessa
        header('location: loginVoluntario.php');

    }
    }

?>