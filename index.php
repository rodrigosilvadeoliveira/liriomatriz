
<?php
include_once('config.php');
include_once('config_language.php');

$sql = "SELECT * FROM evento WHERE cartaz= 'carrousel' ORDER BY id DESC";
$result = $conexao->query($sql);

$sql = "SELECT * FROM evento WHERE cartaz= 'home' ORDER BY id DESC";
$resultHome = $conexao->query($sql);

//session_start();
date_default_timezone_set('America/Sao_Paulo');
   // print_r($_SESSION);
    
?>

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
    <style>
        .video-container {
            position: relative;
            width: 100%;
            max-width: 900px;
            margin: 0 auto 3rem;
            padding: 0 1rem;
        }
        
        /* Vídeo responsivo */
        .video-voluntarios {
            width: 100%;
            height: auto;
            aspect-ratio: 16/9;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: block;
        }
     
    </style>

<body>
    
    <?php include('sitecabecalho.php');?>
   
<br >
<main>

<div class="video-container">
            <video class="video-voluntarios" controls loop muted playsinline autoplay>
                <source src="sabedoria.mp4" type="video/mp4">
                <!-- <source src="voluntariado.webm" type="video/webm"> -->
                Seu navegador não suporta a tag de vídeo.
            </video>
        </div>

<div id="tabelacarrousel" class="carroussel">
    <div class="carroussel-container">
        <?php
        // Listar produtos no carrinho aqui

        while ($imagemNoCarrousel = mysqli_fetch_assoc($result)) {
            echo '<div class="produto">';
            echo '<a href="' . $imagemNoCarrousel['links'] . '">' . '<img class="imagenscarroussel" src="' . $imagemNoCarrousel['imagem'] . '">'.'</a>';
           
            echo '</div>';
        }
        ?>
    </div>
</div>
        <h1 id="titulocategoria"><?= __('titulo_categoria') ?></h1>
<div id="tabelaSite">
<div class="produtos-container">
        <table>
            <tbody>   
            <?php
            // Listar produtos no carrinho aqui
            
            while ($imagemHome = mysqli_fetch_assoc($resultHome)) {
                echo '<tr class="produtos">';
                echo '<td>';
                echo '<a href="' . $imagemHome['links'] . '">' .'<img class="imagens" src="' . $imagemHome['imagem'] . '">'.'</a>';
                echo '<div class="produto-info">';

                // echo '<b>' . $imagemHome['produto'] . '</b>';
                // echo '<p>' . $imagemHome['marca'] . ' - ' . $produtoNoCarrinho['caracteristicas'] . '</p>';
                // echo '<p>SKU ' . $produtoNoCarrinho['id'] . '</p>';
                // echo '<p>R$ ' . $produtoNoCarrinho['valordevenda'] . '</p>';
                // echo '<form action="' . $imagemHome['links'] . '">';
                //echo '<input type="hidden" name="id" value="' . $produtoNoCarrinho['id'] . '">';
               // echo '<input type="submit" class="linkredirect" value="Ir para a pagina">';
                echo '</form>';
                echo '</div>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>
</div>
<h1 id="titulohome">Lirio Matriz</h1>
<h3 id="subtitulohome"><?= __('bem_vindo_sub') ?></h3>
<h3 id="textos"><?= __('texto_prt1') ?></h3>


<h1 id="titulohome"><?= __('titulo_o_que_e') ?></h1>
<h3 id="titulohome"><?= __('sir') ?></h3>

<h3 id="subtitulohome"><?= __('simples') ?></h3>
<h3 id="textos"><?= __('simples_desc') ?></h3>

<h3 id="subtitulohome"><?= __('simples_intensa') ?></h2>
<h3 id="textos"><?= __('simples_intensa2') ?></h3>

<h3 id="subtitulohome"><?= __('simples_relevante') ?></h2>
<h3 id="textos"><?= __('simples_relevante2') ?></h3>

                                                                                   

<h1 id="titulohome">Como a Lírio funciona?</h1>
<h3 id="titulohome"> LÍRIO FUNCIONA EM 4C's.</h2>

<h3 id="subtitulohome"><?= __('simples_carater') ?></h2>
<h3 id="textos"><?= __('simples_carater2') ?></h3>

<h3 id="subtitulohome"><?= __('simples_competencia') ?></h2>
<h3 id="textos"><?= __('simples_competencia2') ?></h3>

<h3 id="subtitulohome"><?= __('combinacao') ?></h2>
<h3 id="textos"><?= __('combinacao2') ?></h3>

<h3 id="subtitulohome"><?= __('comprometimento') ?></h3>
<h3 id="textos"><?= __('comprometimento2') ?></h3>

                                                                                   
<h1 id="titulohome"><?= __('titulo') ?></h1>
<h3 id="titulohome"><?= __('titulo2') ?></h2>

<h3 id="subtitulohome"><?= __(key: 'paixao') ?></h2>
<h3 id="textos"><?= __(key: 'paixao2') ?></h3>

<h3 id="subtitulohome"><?= __(key: 'preparo') ?></h2>
<h3 id="textos"><?= __(key: 'preparo2') ?></h3>

<h3 id="subtitulohome"><?= __(key: 'proprosito') ?></h2>
<h3 id="textos"><?= __(key: 'proprosito2') ?></h3>
</h3>                                                       
<h3 id="textos"><?= __(key: 'saudacao') ?></h3>                                                                                                                                                                                                                                                                                                                
</main>
<div class="footer" id="footer">
      <?php include('sitefooter.php');?>
      </div>     
      <script>
$(document).ready(function(){
    $('.carroussel-container').slick({
        slidesToShow: 1, // Quantidade de slides visíveis ao mesmo tempo
        slidesToScroll: 1, // Quantidade de slides para avançar/retroceder
        autoplay: true, // Ativar a reprodução automática
        autoplaySpeed: 7000, // Velocidade da reprodução automática (em milissegundos)
    });
});
</script>     


</body>
</html>