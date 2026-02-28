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
    <?php include('sitecabecalho.php');?>
    </div>    

</header>




<!--<h1 id="titulonapagina">Conheça o curso Mergulhar</h1>-->


        <!-- Cole o iframe aqui 
        <iframe src="mergulhar.pdf" width="100%" height="600px"></iframe>
        <iframe src="https://docs.google.com/presentation/d/e/2PACX-1vSfxEFCitoAF8MHY7jJVKSQI8MbdwwoF3CKyOGpMb19dh6jzHy4c0h265L6J_bToA/pub?start=true&loop=false&delayms=5000" width="100%" height="600px"></iframe>

    </div>-->
    <?php

$idioma = $_SESSION['lang'] ?? 'pt';

// Segurança extra
$idiomasPermitidos = ['pt', 'en'];
if (!in_array($idioma, $idiomasPermitidos)) {
    $idioma = 'pt';
}

$diretorio = "img/slides/" . $idioma . "/";
$slides = glob($diretorio . "*.jpg");

if (!empty($slides)) {
    foreach ($slides as $slide) {
        echo '<img class="mergulhar" src="'.$slide.'?t='.time().'" />';
    }
} else {
    echo '<p style="text-align:center; padding:2rem;">'.__('sem_imagem').'</p>';
}
?>
<div class="footer" id="footer">
      <?php include('sitefooter.php');?>
      </div>


</body>
</html>
