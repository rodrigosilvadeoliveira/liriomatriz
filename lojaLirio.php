
<?php include("cabecalhoSite.php")?>
<?php
include_once('config.php');

$sql = "SELECT * FROM produtos WHERE carroussel = 'carrousel' ORDER BY id DESC";
$result = $conexao->query($sql);

//session_start();
ini_set('display_errors', 1); // Exibir erros no navegador (para fins de desenvolvimento)
error_reporting(E_ALL); // Relatar todos os tipos de erro (para fins de desenvolvimento)
date_default_timezone_set('America/Sao_Paulo'); // Definir fuso horário para Brasil/Brasília
include_once('config.php');
   // print_r($_SESSION);
    ?>
    
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Lirio Matriz</title>
    <<link rel="stylesheet" href="style.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <script src="bootstrap.min.js"></script>
    </head>
    </head>
<body>
<br >


<h1 id="titulonapagina">Pagina em Construção</h1>

<h3 id="textos">Conheça nossa Loja fisica: Rua Amanari, 629 - Vila Santa Teresinha, São Paulo - SP, 08247-060 - Brasil</h3>

<div class="footer" id="footer">
      <?php include('footerSite.php');?>
      </div>
    </body>
</html>