<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('config.php');
session_start(); // necessário para usar sessão

echo "Debug: Iniciando processo de atualização<br>";

if (isset($_POST['submitAdm'])) {
    echo "Debug: Formulário submetido<br>";
    
    $id           = $_POST['id'];
    $nome         = $_POST['nome'];
    $usuario      = $_POST['usuario'];
    $senha        = $_POST['senha'];
    $email        = $_POST['email'];
    $telefone     = $_POST['telefone'];
    $celular      = $_POST['celular'];
    $nivel_acesso = $_POST['nivel_acesso'];
    
    echo "Debug: Dados recebidos - ID: $id, Nome: $nome, Usuário: $usuario<br>";

    // Verifica a conexão
    if (!$conexao) {
        die("Debug: Erro na conexão: " . mysqli_connect_error());
    }
    echo "Debug: Conexão OK<br>";

    // Verifica se a senha foi alterada ou mantida
    if (empty($senha)) {
        echo "Debug: Senha vazia - atualizando sem senha<br>";
        $query = "UPDATE cadastroadm SET 
            nome = '$nome',
            usuario = '$usuario',
            email = '$email',
            telefone = '$telefone',
            celular = '$celular',
            nivel_acesso = '$nivel_acesso'
            WHERE id = '$id'";
    } else {
        echo "Debug: Nova senha fornecida - atualizando com senha<br>";
        $query = "UPDATE cadastroadm SET 
            nome = '$nome',
            usuario = '$usuario',
            senha = '$senha',
            email = '$email',
            telefone = '$telefone',
            celular = '$celular',
            nivel_acesso = '$nivel_acesso'
            WHERE id = '$id'";
    }
    
    echo "Debug: Query a ser executada: $query<br>";
    
    $result = mysqli_query($conexao, $query);
    
    if ($result) {
        echo "Debug: Query executada com sucesso<br>";
        echo "Debug: Linhas afetadas: " . mysqli_affected_rows($conexao) . "<br>";
        
        // Verifica se alguma linha foi realmente atualizada
        if (mysqli_affected_rows($conexao) > 0) {
            echo "Debug: Registro atualizado com sucesso<br>";
            
            // Normaliza perfil logado para evitar diferenças de maiúscula/minúscula/espaço
            $perfilLogado = isset($_SESSION['nivel_acesso']) ? strtolower(trim($_SESSION['nivel_acesso'])) : '';
            echo "Debug: Perfil logado: $perfilLogado<br>";

            if ($perfilLogado === 'master' || $perfilLogado === 'lider') {
                echo "Debug: Redirecionando para perfil.php<br>";
                header('Location: perfil.php');
            } else {
                echo "Debug: Redirecionando para index.php<br>";
                header('Location: index.php');
            }
            exit;
        } else {
            echo "Debug: Nenhuma linha foi atualizada. O ID $id existe no banco?<br>";
            
            // Verifica se o ID existe
            $checkId = mysqli_query($conexao, "SELECT id FROM cadastroadm WHERE id = '$id'");
            if (mysqli_num_rows($checkId) > 0) {
                echo "Debug: O ID $id existe no banco. Os dados podem ser os mesmos.<br>";
            } else {
                echo "Debug: ERRO - O ID $id NÃO existe no banco de dados!<br>";
            }
        }
    } else {
        echo "Debug: Erro na execução da query: " . mysqli_error($conexao) . "<br>";
    }
} else {
    echo "Debug: submitAdm não está setado no POST<br>";
    print_r($_POST);
}
?>