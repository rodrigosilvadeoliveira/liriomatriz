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
        $_SESSION['igreja_id'] = $row['igreja_id'];
// REGISTRA O LOGIN NO LOG
$data_login = date('Y-m-d'); // formato: 2025-04-21
$hora_login = date('H:i:s'); // formato: 14:30:05
$pagina = basename($_SERVER['PHP_SELF']); // pega o nome do arquivo atual, ex: membros.php

// Busca o nome e o nível de acesso do usuário atual
$usuario = $_SESSION['usuario'];
$nivel_acesso = $_SESSION['nivel_acesso'] ?? 'desconhecido';
$igreja_id = $_SESSION['igreja_id'];

// (opcional) Se quiser pegar também o nome do usuário na tabela de usuários:
$stmtUser = $conexao->prepare("SELECT nome FROM cadastroadm WHERE usuario = ?");
$stmtUser->bind_param("s", $usuario);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$rowUser = $resultUser->fetch_assoc();
$nome_usuario = $rowUser['nome'] ?? $usuario; // se não encontrar, usa o login mesmo

// Inserir o registro no log
$logSql = "INSERT INTO log_login (usuario, nome, nivel_acesso, data_login, hora_login, acao, igreja_id)
           VALUES (?, ?, ?, ?, ?, ?, ?)";
$logStmt = $conexao->prepare($logSql);
$logStmt->bind_param("sssssss", $usuario, $nome_usuario, $nivel_acesso, $data_login, $hora_login, $pagina, $igreja_id);
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
    case 'midia':
    case 'master':
    case 'live':
    case 'lider':
    case 'ministro':
    case 'consulta':
        // Marca para mostrar banner na página inicial
        $_SESSION['mostrar_banner_login'] = true;
        header('Location: paginainicial');
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
