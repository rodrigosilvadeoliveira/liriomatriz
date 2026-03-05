<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('config.php');
session_start();

if (isset($_POST['submitAdm'])) {

    $nome         = trim($_POST['nome']);
    $usuario      = trim($_POST['usuario']);
    $senha        = $_POST['senha'];
    $email        = trim($_POST['email']);
    $telefone     = trim($_POST['telefone']);
    $celular      = trim($_POST['celular']);
    $nivel_acesso = trim($_POST['nivel_acesso']);

   

    // Verifica se já existe usuário
    $stmtCheck = $conexao->prepare("SELECT id FROM cadastroadm WHERE usuario = ? LIMIT 1");
    $stmtCheck->bind_param("s", $usuario);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        echo "<script>alert('Este nome de usuário já está cadastrado!'); window.location.href='formulariolider.php';</script>";
        exit;
    }

    $stmtCheck->close();

    // Inicia transação
    $conexao->begin_transaction();

    try {

        // 1️⃣ Inserir em cadastroadm
        $stmtAdm = $conexao->prepare("INSERT INTO cadastroadm 
            (nome, usuario, senha, email, telefone, celular, nivel_acesso) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmtAdm->bind_param(
            "sssssss",
            $nome,
            $usuario,
            $senha,
            $email,
            $telefone,
            $celular,
            $nivel_acesso
        );

        if (!$stmtAdm->execute()) {
            throw new Exception("Erro ao cadastrar administrador.");
        }

        // Pega o ID gerado
        $cadastroadm_id = $conexao->insert_id;

        $stmtAdm->close();

        // 2️⃣ Inserir automaticamente na tabela musicos
        $stmtMusico = $conexao->prepare("INSERT INTO musicos (cadastroadm_id, nome) VALUES (?, ?)");
        $stmtMusico->bind_param("is", $cadastroadm_id, $usuario);

        if (!$stmtMusico->execute()) {
            throw new Exception("Erro ao cadastrar músico.");
        }

        $stmtMusico->close();

        // Se tudo deu certo → confirma no banco
        $conexao->commit();

        // Redirecionamento
        $perfilLogado = isset($_SESSION['nivel_acesso']) ? strtolower(trim($_SESSION['nivel_acesso'])) : '';

        if ($perfilLogado === 'master') {
            header("Location: edit_voluntarioescalaAcesso.php?id=" . $cadastroadm_id);
        } elseif ($perfilLogado === 'lider') {
            header("Location: edit_voluntarioescalaAcesso.php?id=" . $cadastroadm_id);
        } else {
            header('Location: sistema.php');
        }
        exit;

    } catch (Exception $e) {

        // Se algo falhar → desfaz tudo
        $conexao->rollback();
        echo "Erro: " . $e->getMessage();
    }
}
?>