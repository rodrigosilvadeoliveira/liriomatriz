<?php

date_default_timezone_set('America/Sao_Paulo');

include_once('config.php');
include_once('verificarLogin.php');

verificarLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: perfil.php');
    exit();
}

if (!isset($_POST['id']) || empty($_POST['id'])) {
    header('Location: perfil.php?erro=id_invalido');
    exit();
}

if (!isset($_POST['senha']) || empty(trim($_POST['senha']))) {
    header('Location: alteraracesso.php?id=' . (int)$_POST['id'] . '&erro=senha_vazia');
    exit();
}

$id = (int) $_POST['id'];
$senha = trim($_POST['senha']);

// Validação mínima
if (strlen($senha) < 6) {
    header('Location: alteraracesso.php?id=' . $id . '&erro=senha_curta');
    exit();
}

// Criptografa a senha usando o mesmo padrão bcrypt
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Atualiza somente a senha
$sql = "UPDATE cadastroadm SET senha = ? WHERE id = ?";

$stmt = $conexao->prepare($sql);

if (!$stmt) {
    header('Location: alteraracesso.php?id=' . $id . '&erro=erro_banco');
    exit();
}

$stmt->bind_param("si", $senha, $id);

if ($stmt->execute()) {
    $stmt->close();

    header('Location: perfil.php?sucesso=senha_alterada');
    exit();
}

$stmt->close();

header('Location: alteraracesso.php?id=' . $id . '&erro=erro_atualizar');
exit();