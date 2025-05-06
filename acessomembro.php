<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['telefone'])) {
    include_once('config.php');
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    // Consulta para buscar o usuário e seu perfil
    $sql = "SELECT * FROM membros WHERE email = ? AND telefone = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ss", $email, $telefone);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows < 1) {
        unset($_SESSION['email']);
        unset($_SESSION['telefone']);
        echo 'Usuário ou senha inválidos';
        header('Location: acessonegadomembro.php');
    } else {
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $email;
        $_SESSION['telefone'] = $telefone;
        $_SESSION['id'] = $row['id'];

        // Redireciona com base no perfil
        
                header("Location: edit_membros.php?id=$row[id]");
                
    }
} else {
    header('Location: acessonegadomembro.php');
    exit();
}
?>
