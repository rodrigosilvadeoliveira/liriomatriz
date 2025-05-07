<!DOCTYPE html>
<html lang="en">
    <head>
    <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login Adm</title>

    <!-- Estilos -->
    <link rel="stylesheet" href="style.css">

    <!-- Ícone para aba do navegador -->
    <link rel="icon" href="icons/icon-192.png" type="image/png">

    <!-- Manifesto PWA -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#0d6efd">
</head>

<body>
<img id="logo" src ="lirioMatriz_preto.png">
<div>
<h1 id="lirioLogin">Sistema <br><u>Lirio Matriz</u></h1>
</div>
    

    <div class="tela-login">
    <!-- <a href="homeLirio.php" >
<svg xmlns="http://www.w3.org/2000/svg" id="voltarLogin" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
  <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1z"/>
</svg>
</a> -->
    <form action="acessoAdm.php" method="POST">
        <div class="loginsenha">
        <h1 id="formlogin">Login</h1>
        <input class="login" type="text" placeholder="usuario" name="usuario">
        <br><br>
        <input class="login" type="password" placeholder="senha" name="senha">
        <br><br>
        <input class="inputSubmit" type="submit" name="submit" value="Enviar">
        </div>
</form>
</div>

<script>
    // Tempo de inatividade em milissegundos (1 hora = 3600000 ms)
    const tempoLimite = 3600000;

    // Redireciona para logout após o tempo limite
    setTimeout(() => {
        window.location.href = "sistema.php?timeout=1"; 
    }, tempoLimite);
</script>
<script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js');
  }
</script>

</body>
</html>