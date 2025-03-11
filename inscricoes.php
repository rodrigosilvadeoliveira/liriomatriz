<?php include("cabecalhoSite.php")?>
<?php
session_start();

// Conecte-se ao banco de dados (substitua com suas configurações)
include('config.php');

$sql = "SELECT * FROM evento WHERE cartaz= 'formulario' ORDER BY id DESC";
$result = $conexao->query($sql);
   // print_r($_SESSION);
   
    ?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscrições</title>
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
     

</header>

<h1 id="titulonapagina">Conheça mais sobre Cursos e Áreas do Voluntariádo e faça sua inscrição</h1>
<h3 id="textos">*Antes de  inscrição verifique se tem requisitos necessário, alguns cursos e o voluntariado é necessario ser membro e batizado.</h3>

<!-- <div class="form-container">
         Cole o iframe aqui -->
       <!-- <iframe id="novocomeco" src="https://docs.google.com/forms/d/e/1FAIpQLSfIQWHrh-sMw6rVoDg-PSh2syVPn36i91ZUI67nbi2Yn9-yOA/viewform?embedded=true" width="640" height="950" frameborder="0" marginheight="0" marginwidth="0">Carregando…</iframe>
    </div>
    <div id="tabelaForm">
<div class="produtos-container">
        <table>
            <tbody>   
            
        </tbody>
    </table>
</div>
</div> -->
<tr>
<?php
            // Listar produtos no carrinho aqui
            
            while ($imagemHome = mysqli_fetch_assoc($result)) {
                
                echo '<form action="'. $imagemHome['inscricao'] . '.php">';
                echo '<h3 id="inscrever">' . $imagemHome['inscricao'] . ' <input type="submit" class="linkredirect" value="Inscrição">';
                echo '</h3>';
                echo '</form>';
                
            }
            ?>


<div class="footer" id="footer">
      <?php include('footerSite.php');?>
      </div>
</body>
</html>
