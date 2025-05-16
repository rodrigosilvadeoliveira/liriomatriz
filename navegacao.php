<?php


include_once('config.php');

// Corrigindo lógica de verificação
if (!isset($_SESSION['usuario']) || !isset($_SESSION['senha'])) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
    exit;
}

$logado = $_SESSION['usuario'];
$perfil = $_SESSION['nivel_acesso']; // Pegamos o perfil do usuário logado

// Pesquisa, se aplicável
if (!empty($_GET['search'])) {
    $data = $_GET['search'];
    $sql = "SELECT * FROM membros WHERE id LIKE '%$data%' or nome LIKE '%$data%' or sobrenome LIKE '%$data%' ORDER BY id DESC";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscrições</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <script src="bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

</head>
<body>
    <div id="tabelaSite">
<div class="produtos-container">
    <?php if (in_array($perfil, ['secretaria', 'master'])): ?>
        <a id="btncadastroadm" class="butnavegacao"href="cadastroMembrosAdm.php">Novo Membro(a)</a>
        <a id="btnconsmmembro" class="butnavegacao"href="consulta_membros.php">Consultar Membros</a>
        <a id="btnbusca" class="butnavegacao"href="consulta_membros_busca.php">Pesquisa Membro(a)</a>
        <a id="btninscricao" class="butnavegacao"href="cadastroForm.php">Inscrições</a>
        <a id="btncadrelatorio" class="butnavegacao"href="relatoriodepartamento">Cadastrar Relatórios</a>
        <a id="btnconsrela" class="butnavegacao"href="listar_relatoriosdep">Consultar Relatórios</a>
        <a id="btnsiteimg" class="butnavegacao"href="cadastroEvento">Site imagens</a>
    <?php endif; ?>

    <?php if (in_array($perfil, ['master'])): ?>
         <a id="btnformmaster" class="butnavegacao"href="formularioMaster.php" value="Novo Cadastro">Cadastro Acesso</a>
         <a id="btnconslog" class="butnavegacao"href="consulta_logs.php" value="Novo Cadastro">Log</a>
    <?php endif; ?>
    
    <?php if (in_array($perfil, ['midia', 'master'])): ?>
         <a id="btnsiteimg" class="butnavegacao"href="cadastroEvento">Site imagens</a>
        
    <?php endif; ?>

    <?php if (in_array($perfil, ['live', 'master'])): ?>
         <a id="btnsiteimg" class="butnavegacao"href="cadastrolive">Site Live</a>
    <?php endif; ?>

</div>
</div>
</body>
</html>
