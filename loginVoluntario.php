<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
   
</head>
<body>
<img id="logo" src ="lirioMatriz_preto.png">
<div>
<h1 id="lirioLogin">Sistema de Loja <br><u>Lirio Matriz</u></h1>
</div>
    

    <div class="tela-login">
    <a href="index.php" >
<svg xmlns="http://www.w3.org/2000/svg" id="voltarLogin" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
  <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1z"/>
</svg>
</a>
    <form action="acessoVol.php" method="POST">
        <h1>Login Volunt</h1>
        <input class="login" type="text" placeholder="usuario" name="usuario">
        <br><br>
        <input class="login" type="password" placeholder="senha" name="senha">
        <br><br>
        <input class="inputSubmit" type="submit" name="submit" value="Enviar">
</form>
</div>

</body>
</html>