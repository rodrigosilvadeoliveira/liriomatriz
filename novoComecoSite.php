<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Lirio Matriz</title>
    <link rel="stylesheet" href="style.css?t=<?=time()?>">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <script src="bootstrap.min.js"></script>
    </head>

<body>
<header>
    <div class="cabecalho" id="cabecalhodoSite">
    <?php include('cabecalhoSite.php');?>
    </div>    

</header>

<form action="contato.php" method="post">


<h1 id="titulonapagina">Seja bem Vindo a Lirio Matriz</h1>

<div class="form-container">
        <!-- Cole o iframe aqui -->
        <iframe id="novocomeco" src="https://docs.google.com/forms/d/e/1FAIpQLSexuTFLBXX6HUnjjVQ9VObYxnXutQ9DLVD5IHD6PltfMXPrnw/viewform?pli=1" width="640" height="950" frameborder="0" marginheight="0" marginwidth="0">Carregando…</iframe>
    </div>
<div class="footer" id="footer">
      <?php include('footerSite.php');?>
      </div>


</body>
</html>
