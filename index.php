
<?php include("cabecalhoSite.php")?>
<?php
include_once('config.php');

$sql = "SELECT * FROM evento WHERE cartaz= 'carrousel' ORDER BY id DESC";
$result = $conexao->query($sql);

$sql = "SELECT * FROM evento WHERE cartaz= 'home' ORDER BY id DESC";
$resultHome = $conexao->query($sql);

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
<div id="tabelacarrousel" class="carroussel">
    <div class="carroussel-container">
        <?php
        // Listar produtos no carrinho aqui

        while ($imagemNoCarrousel = mysqli_fetch_assoc($result)) {
            echo '<div class="produto">';
            echo '<img class="imagenscarroussel" src="' . $imagemNoCarrousel['imagem'] . '">';
            echo '<div class="produto-info">';
            // Resto do código do produto
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</div>
<h1 id="titulocategoria">Você encontra na Liro Matriz</h1>
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
<h3 id="subtitulohome">BEM-VINDO À LÍRIO, BEM-VINDO A SUA CASA.</h2>
<h3 id="textos">É muito bom poder compartilhar com você a visão que nos torna Lírio, que faz nossa
Igreja cumprir um propósito no Reino de Deus. Quero antes de tudo, dizer que todas as denominações
são importantes, afinal você não escolhe uma Denominação por conta de Deus.
Deus está em todos lugares, mas você escolhe um lugar para congregar pois além de ser um local
que respeite a palavra de Deus,este ambiente também faz sentido e combina com você.

Queremos que você de forma prática possa compreender absolutamente tudo que pulsa
em nosso coração e, didaticamente desenvolvemos três perguntas que
responderão tudo sobre nós e nosso jeito Lírio de ser:</h3>


<h1 id="titulohome">O que a Lírio é?</h1>
<h3 id="titulohome">A LIRIO É <b>S.I.R.</b></h2>

<h3 id="subtitulohome">SIMPLES</h2>
<h3 id="textos">Uma Igreja para todos, que prega o evangelho de Jesus Cristo de forma
acessível e compreensível a todos.</h3>

<h3 id="subtitulohome">INTENSA</h2>
<h3 id="textos">Uma Igreja que em tudo que realiza aplica alto índices de energia; que prega,
canta e ora com vida tendo a plena certeza que Deus e move de acordo com a
nossa intensidade.</h3>

<h3 id="subtitulohome">RELEVANTE</h2>
<h3 id="textos">Uma Igreja onde seus membros são marcados com a cultura do Reino tendo
seu estilo de vida pessoal transformado em todos os campos da vida, onde
seus membros são a forma mais eficaz de propagação do evangelho, que
inspirados compartilham de forma espontânea e voluntária suas experiencias
com Deus em seu dia a dia;</h3>

                                                                                   

<h1 id="titulohome">Como a Lírio funciona?</h1>
<h3 id="titulohome"> LÍRIO FUNCIONA EM 4C's.</h2>

<h3 id="subtitulohome">CARÁTER</h2>
<h3 id="textos">Uma Igreja que vive a Sagrada Escritura de forma plena, sem adaptações, que
ensina seus membros no compromisso com a verdade, honestidade e
coerência.</h3>

<h3 id="subtitulohome">COMPETÊNCIA</h2>
<h3 id="textos">Uma Igreja que acredita que todos tem um chamado e uma vocação, e trabalha
para que todos se sintam úteis no servir combinando seus dons naturais e
espirituais no servir a Igreja e alcançar os perdidos.</h3>

<h3 id="subtitulohome">COMBINAÇÃO</h2>
<h3 id="textos">Uma Igreja que não negocia sua visão e vocação para se adequar a pessoas,
pressões por resultado, mas que tem humildade para compartilhar exaustivamente
nosso jeito Lírio de ser com todos os que desejarem se unir a nós.</h3>

<h3 id="subtitulohome">COMPROMETIMENTO</h3>
<h3 id="textos">Uma Igreja que não tem medo de sacrificar em prol do Reino, e que inspira
seus membros a servirem além de sua comodidade e horas livres;</h3>

                                                                                   

<h1 id="titulohome">Como a Lírio se mantém?</h1>
<h3 id="titulohome">A LÍRIO SE MANTÉM EM 3 P's.</h2>

<h3 id="subtitulohome">PAIXÃO</h2>
<h3 id="textos">Uma Igreja que tem consciência que nada é mais encantador e inspirador que
o evangelho, absolutamente nada é pesado ou cansativo.</h3>

<h3 id="subtitulohome">PREPARO</h2>
<h3 id="textos">Uma Igreja que oferece para todos os membros e voluntários centros de
formação para que compreendam com clareza nossa doutrina e cultura; que se
atualiza, que ama a modernização, mas sem abrir mão de sua essência e doutrina.</h3>

<h3 id="subtitulohome">PROPÓSITO</h2>
<h3 id="textos">Uma Igreja que é que é grande o suficiente para sonhar em escala mundial, e
pessoal o suficiente para que cada um encontre o seu lugar.

Esta é a sua Igreja, essa é a casa que Deus preparou para que você o sirva e
seja uma ferramenta relevante para a expansão de Seu Reino.
</h3>                                                       
<h3 id="textos">
Abraços de carinho e fé.
Rozilda Paixão
Pastora na Lirio Matriz
</h3>                                                                                                                                                                                                                                                                                                                

     
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
<div class="footer" id="footer">
      <?php include('footerSite.php');?>
      </div>
    </body>
</html>