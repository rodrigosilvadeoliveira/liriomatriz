<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('config.php');
session_start(); // necessário para usar sessão

if (isset($_POST['submitAdm'])) {
    $nome         = $_POST['nome'];
    $usuario      = $_POST['usuario'];
    $senha        = $_POST['senha'];
    $email        = $_POST['email'];
    $telefone     = $_POST['telefone'];
    $celular      = $_POST['celular'];
    $nivel_acesso = $_POST['nivel_acesso'];

    // Verifica se já existe usuário com o mesmo login
    $checkUser = mysqli_query($conexao, "SELECT id FROM cadastroadm WHERE usuario = '$usuario' LIMIT 1");

    if (mysqli_num_rows($checkUser) > 0) {
        echo "<script>alert('Este nome de usuário já está cadastrado!'); window.location.href='formulariolider.php';</script>";
        exit;
    } else {
        // Insere novo cadastro
        $result = mysqli_query($conexao, "INSERT INTO cadastroadm(nome,usuario,senha,email,telefone,celular,nivel_acesso) 
        VALUES ('$nome','$usuario','$senha','$email','$telefone','$celular','$nivel_acesso')");

        if ($result) {
            // Normaliza perfil logado para evitar diferenças de maiúscula/minúscula/espaço
            $perfilLogado = isset($_SESSION['nivel_acesso']) ? strtolower(trim($_SESSION['nivel_acesso'])) : '';

            if ($perfilLogado === 'master') {
                
                echo "<script>alert('Cadastrado realizado com sucesso!'); window.location.href='formularioMaster.php';</script>";
                
            } elseif ($perfilLogado === 'lider') {
                 echo "<script>alert('Cadastrado realizado com sucesso!'); window.location.href='formularioMaster.php';</script>";
            } else {
                // fallback caso não tenha sessão ou perfil inválido
                header('Location: sistema.php');
            }
            exit;
        } else {
            echo "Erro ao cadastrar: " . mysqli_error($conexao);
        }
    }
}
?>
