<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
if (isset($_POST['submit']) && !empty($_POST['usuario']) && !empty($_POST['senha'])) {
    include_once('config.php');
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Consulta para buscar o usuário e seu perfil
    $sql = "SELECT * FROM cadastroadm WHERE usuario = ? AND senha = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ss", $usuario, $senha);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows < 1) {
        unset($_SESSION['usuario']);
        unset($_SESSION['senha']);
        echo 'Usuário ou senha inválidos';
        header('Location: acesso_negado.php');
    } else {
        $row = $result->fetch_assoc();
        $_SESSION['usuario'] = $usuario;
        $_SESSION['senha'] = $senha;
        $_SESSION['nivel_acesso'] = $row['nivel_acesso'];
        $_SESSION['nome'] = $row['nome'];
// REGISTRA O LOGIN NO LOG
$data_login = date('Y-m-d'); // formato: 2025-04-21
$hora_login = date('H:i:s'); // formato: 14:30:05

$logSql = "INSERT INTO log_login (usuario, nome, nivel_acesso, data_login, hora_login) VALUES (?, ?, ?, ?, ?)";
$logStmt = $conexao->prepare($logSql);
$logStmt->bind_param("sssss", $usuario, $row['nome'], $row['nivel_acesso'], $data_login, $hora_login);
$logStmt->execute();


        // Redireciona com base no perfil
        switch ($row['nivel_acesso']) {
            case 'admin':
                header('Location: vendas');
                break;
            case 'voluntario':
                header('Location: vendasVol');
                break;
            case 'secretaria':
                header('Location: cadastroMembrosAdm');
                break;
            case 'midia':
                header('Location: cadastroEvento');
                break;
            case 'master':
                header('Location: formularioMaster');
                break;
            default:
                header('Location: acesso_negado');
                break;
        }
        exit();
    }
} else {
    header('Location: acesso_negado.php');
    exit();
}
?>
