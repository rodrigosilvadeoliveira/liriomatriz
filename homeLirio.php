<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liro Matriz Loja</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<img id="logo" src ="lirioMatriz_preto.png">
    <h1 id="lirio">Sistema Lirio Matriz</h1>
    <div class="boxlogin">
    <a href="loginAdmin.php"><button class="buttonlogin">Acesso Admin - Loja</button></a>
    <a href="loginVoluntario.php"><button class="buttonlogin">Acesso Voluntário - Loja</button></a>
    <a href="loginIgreja.php"><button class="buttonlogin">Igreja</button></a>
    <a href="index.php"><button class="buttonlogin">Site</button></a>
    </div>
    <!-- <div class="boxlogin">
        <a href="loginAdmin.php" id="botaoadm">Administrador</a>
</div>
        <div class="boxloginVol">
        <a href="loginVoluntario.php" id="botaovol">Voluntário</a>
</div>
<div class="boxloginIgr">
        <a href="loginAdmin.php" id="botaoigr">Igreja</a>
</div> -->
        <!--
<a href="formulario.php" id="cadastre">Cadastre-se</a>"
//-->
<script>
    // Tempo de inatividade em milissegundos (1 hora = 3600000 ms)
    const tempoLimite = 3600000;

    // Redireciona para logout após o tempo limite
    setTimeout(() => {
        window.location.href = "sistema.php?timeout=1"; 
    }, tempoLimite);
</script>

</body>
</html>