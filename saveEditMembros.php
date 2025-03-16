<?php
    // isset -> serve para saber se uma variável está definida
    include_once('config.php');
    if(isset($_POST['update']))
    {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $nascimento = $_POST['nascimento'];
    $datas = $_POST['datas'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $voluntario = $_POST['voluntario'];
    $lider = $_POST['lider'];
    $departamentoum = $_POST['departamentoum'];
    $departamentodois = $_POST['departamentodois'];
    $departamentotres = $_POST['departamentotres'];
    $status = $_POST['status'];

        
        $sqlInsert = "UPDATE membros 
        SET nome='$nome',sobrenome='$sobrenome',nascimento='$nascimento',datas='$datas',telefone='$telefone',email='$email',voluntario='$voluntario',lider='$lider',departamentoum='$departamentoum',departamentodois='$departamentodois',departamentotres='$departamentotres',status='$status'
        WHERE id=$id";
        $result = $conexao->query($sqlInsert);
        print_r($result);
    }
    header('Location: consulta_membros.php');

?>