
<?php include("cabecalhoSite.php")?>
<?php
session_start();

// Conecte-se ao banco de dados (substitua com suas configurações)
include('config.php');

$sql = "SELECT * FROM evento WHERE inscricao= 'voluntariado_divulgar' ORDER BY id DESC";
$result = $conexao->query($sql);
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
<br >
<br >
<br >

<h1 id="titulohome">Programação</h1>

<div id="programacaoSite">
<div class="produtos-container">
        <table>
            <tbody>   
            <?php
            // Listar produtos no carrinho aqui
            
            while ($produtoNoCarrinho = mysqli_fetch_assoc($result)) {
                echo '<tr class="produtos">';
                echo '<td>';
                echo '<img class="imagens" src="' . $produtoNoCarrinho['imagem'] . '">';
                
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>
</div>
<div class="footer" id="footer">
      <?php include('footerSite.php');?>
      </div>
    </body>
</html>