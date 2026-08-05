<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('config.php');
session_start();

if (isset($_POST['submitAdm'])) {

    $id            = intval($_POST['id']);
    $cadastroadm_id= intval($_POST['cadastroadm_id']);
    $igreja_id     = intval($_POST['igreja_id']);

    $foto          = trim($_POST['foto']);
    $nome          = trim($_POST['nome']);
    $usuario       = trim($_POST['usuario']);
    $senha         = trim($_POST['senha']);
    $email         = trim($_POST['email']);
    $telefone      = trim($_POST['telefone']);
    $celular       = trim($_POST['celular']);
    $nivel_acesso  = trim($_POST['nivel_acesso']);

    /*
    =====================================
    VALIDAR USUÁRIO DUPLICADO
    =====================================
    */

    $stmtCheck = $conexao->prepare("
        SELECT id
        FROM cadastroadm
        WHERE usuario = ?
        AND id <> ?
        LIMIT 1
    ");

    $stmtCheck->bind_param("si", $usuario, $id);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if($stmtCheck->num_rows > 0){

        echo "<script>
                alert('Este usuário já está cadastrado.');
                history.back();
              </script>";
        exit;
    }

    $stmtCheck->close();

    $conexao->begin_transaction();

    try{

        /*
        =====================================
        ATUALIZA CADASTRO ADMIN
        =====================================
        */

        if(!empty($senha)){

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $stmtAdm = $conexao->prepare("
                UPDATE cadastroadm
                SET
                    igreja_id=?,
                    nome=?,
                    usuario=?,
                    senha=?,
                    email=?,
                    telefone=?,
                    celular=?,
                    nivel_acesso=?
                WHERE id=?
            ");

            $stmtAdm->bind_param(
                "isssssssi",
                $igreja_id,
                $nome,
                $usuario,
                $senhaHash,
                $email,
                $telefone,
                $celular,
                $nivel_acesso,
                $id
            );

        }else{

            $stmtAdm = $conexao->prepare("
                UPDATE cadastroadm
                SET
                    igreja_id=?,
                    nome=?,
                    usuario=?,
                    email=?,
                    telefone=?,
                    celular=?,
                    nivel_acesso=?
                WHERE id=?
            ");

            $stmtAdm->bind_param(
                "issssssi",
                $igreja_id,
                $nome,
                $usuario,
                $email,
                $telefone,
                $celular,
                $nivel_acesso,
                $id
            );

        }

        if(!$stmtAdm->execute()){
            throw new Exception("Erro ao atualizar administrador.");
        }

        $stmtAdm->close();

        /*
        =====================================
        ATUALIZA VOLUNTÁRIO
        =====================================
        */

        $stmtVol = $conexao->prepare("
            UPDATE voluntarios
            SET
                igreja_id=?,
                usuario=?,
                foto=?
            WHERE cadastroadm_id=?
        ");

        $stmtVol->bind_param(
            "issi",
            $igreja_id,
            $usuario,
            $foto,
            $cadastroadm_id
        );

        if(!$stmtVol->execute()){
            throw new Exception("Erro ao atualizar voluntário.");
        }

        $stmtVol->close();

        /*
        =====================================
        BUSCA ID DO VOLUNTÁRIO
        =====================================
        */

        $stmtBusca = $conexao->prepare("
            SELECT id
            FROM voluntarios
            WHERE cadastroadm_id=?
            LIMIT 1
        ");

        $stmtBusca->bind_param("i",$cadastroadm_id);
        $stmtBusca->execute();

        $voluntario = $stmtBusca->get_result()->fetch_assoc();

        $stmtBusca->close();

        $conexao->commit();

        header("Location: consultaacessos");
        exit;

    }catch(Exception $e){

        $conexao->rollback();

        echo "Erro: ".$e->getMessage();

    }

}
?>