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
<div class="cabecalho" id="cabecalhodoSite">
    <?php include('cabecalhoSite.php');?>
    </div>

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

<table class="table" id="tabelaLista" style="width: 99%;">
  <thead>
    <tr>
    <th scope="col">Inscrição</th>
      <th scope="col">Data inicio</th>
      <th scope="col">Fim inscrição</th>
           
      <th scope="col">......</th>
    </tr>
  </thead>
  <tbody>
  <?php
            // Listar produtos no carrinho aqui
            
            while ($imagemHome = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                
                echo '<form action="'. $imagemHome['inscricao'] . '.php">';
                echo "<td>".'<h2 id="inscrever">' . $imagemHome['inscricao'] . "</td>";
                echo '</h2>';
                echo "<td>".$imagemHome['inicio']."</td>";
                echo "<td>".$imagemHome['fim']."</td>";
                echo "<td>" . ' <input type="submit" class="linkredirect" value="Cadastrar">'."</td>";
                echo '</tr>';
                echo '</form>';
                
                echo "</tr>";

            }
    
    
    
      ?>
        
        </tr>
      </tbody>
    </table>
    </div>
<div class="footer" id="footer">
      <?php include('footerSite.php');?>
      </div>


</body>
</html>
