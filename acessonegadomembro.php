<html lang="en">
    <head>
    <meta charset="=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Adm</title>
    <link rel="stylesheet" href="style.css">
   
</head>
<body>
<img id="logo" src ="lirioMatriz_preto.png">
<div>
<h1 id="lirioLogin">Sistema de Lirio <br><u>Lirio Matriz</u></h1>
</div>
    

    <div class="tela-login">

    <form action="loginmembro.php" method="POST">
   
    <h1>Usuário ou senha inválida</h1>
        <input class="inputSubmit" type="submit" name="submit" value="tentar Novamente">
</form>
</div>

<script>
    // Tempo de inatividade em milissegundos (1 hora = 3600000 ms)
    const tempoLimite = 3600000;

    // Redireciona para logout após o tempo limite
    setTimeout(() => {
        window.location.href = "loginmembro.php?timeout=1"; 
    }, tempoLimite);
</script>

</body>
</html>