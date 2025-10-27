<?php
$data_login = date('Y-m-d'); // formato: 2025-04-21
$hora_login = date('H:i:s'); // formato: 14:30:05
$pagina = basename($_SERVER['PHP_SELF']); // pega o nome do arquivo atual, ex: membros.php

// Busca o nome e o nível de acesso do usuário atual
$usuario = $_SESSION['usuario'];
$nivel_acesso = $_SESSION['nivel_acesso'] ?? 'desconhecido';

// (opcional) Se quiser pegar também o nome do usuário na tabela de usuários:
$stmtUser = $conexao->prepare("SELECT nome FROM cadastroadm WHERE usuario = ?");
$stmtUser->bind_param("s", $usuario);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$rowUser = $resultUser->fetch_assoc();
$nome_usuario = $rowUser['nome'] ?? $usuario; // se não encontrar, usa o login mesmo

// Inserir o registro no log
$logSql = "INSERT INTO log_login (usuario, nome, nivel_acesso, data_login, hora_login, acao)
           VALUES (?, ?, ?, ?, ?, ?)";
$logStmt = $conexao->prepare($logSql);
$logStmt->bind_param("ssssss", $usuario, $nome_usuario, $nivel_acesso, $data_login, $hora_login, $pagina);
$logStmt->execute();
$logStmt->close();
?>