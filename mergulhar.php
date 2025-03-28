<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lirio Matriz</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <script src="bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

</head>
<body>
<header>
    <div class="cabecalho" id="cabecalho">
    <?php include('cabecalhoSite.php');?>
    </div>    

</header>

<form action="contato.php" method="post">


<h1 id="titulonapagina">Seja bem Vindo a Lirio Matriz</h1>

<div class="form-container">
        <!-- Cole o iframe aqui 
        <iframe src="mergulhar.pdf" width="100%" height="600px"></iframe>
        <iframe src="https://docs.google.com/presentation/d/e/2PACX-1vSfxEFCitoAF8MHY7jJVKSQI8MbdwwoF3CKyOGpMb19dh6jzHy4c0h265L6J_bToA/pub?start=true&loop=false&delayms=5000" width="100%" height="600px"></iframe>

    </div>-->
    <?php
$slides = glob("img/slides/*.jpg"); // Pega todas as imagens da pasta slides/
foreach ($slides as $slide) {
    echo '<img src="'.$slide.'" width="100%" />';
}
?>
<div class="footer" id="footer">
      <?php include('footerSite.php');?>
      </div>


</body>
</html>
