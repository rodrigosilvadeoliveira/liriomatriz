<?php include("cabecalhoAdm.php")?>
<?php
include('verificarLogin.php');
verificarLogin();

include_once('config.php');
   // print_r($_SESSION);
    if((!isset($_SESSION['usuario'])== true) and ($_SESSION['senha']) == true)
    {
      unset($_SESSION['usuario']);
      unset($_SESSION['senha']);
      header('Location: login.php');
      
    }$logado = $_SESSION['usuario'];
    if(!empty($_GET['search']))
    {
        $data = $_GET['search'];
        $sql = "SELECT * FROM vendas WHERE barra LIKE '%$data%' or produto LIKE '%$data%' or datas LIKE '%$data%' ORDER BY id DESC";
    }
    else
    {
        $sql = "SELECT * FROM vendas ORDER BY datas DESC";
    }
    $result = $conexao->query($sql);
?>
     
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Lirio Matriz</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <script src="bootstrap.min.js"></script>
</head>
<body>
<br><br><br>
   
<?php
    echo "<h3 id='bemRelatorio'>Relatórios</h3>";
?>

<h3 id="instrucao" style="font-size:17px; margin-left:1%;">
<b>*Instruções para gerar relatório:</b><br>
    1. Selecione data inicio e a data fim de acordo com o periodo a ser consultado.
    <p>2. Click no botão Exportar.
    <br>3. Verificar na Pasta download arquivo excel do relatório</p> </h3>
<fieldset class="boxformularioRelatorio">
<form id="dataRelatorio" method="POST" action="relatorioVendasporProduto.php">
    <label for="data_inicio"><b>Relatório de Vendas por produto:</b></label><br>
    <label for="data_inicio"><b>Data Inicio:</b></label>
    <input type="date" name="data_inicio" id="data_inicio" /><br>
    <label for="data_fim"><b>Data Fim:</b></label>
    <input type="date" name="data_fim" id="data_fim" />
    <input type="submit" value="Exportar" id="Exportar"/>
</form>
</fieldset>
<br>
<fieldset class="boxformularioRelatorio">
<form id="dataRelatorio" method="POST" action="relatorio_vendasAdm.php">
    <label for="data_inicio"><b>Relatório de Vendas por tipo de Pgto:</b></label><br>
    <label for="data_inicio"><b>Data Inicio:</b></label>
    <input type="date" name="data_inicio" id="data_inicio" /><br>
    <label for="data_fim"><b>Data Fim:</b></label>
    <input type="date" name="data_fim" id="data_fim" />
    <input type="submit" value="Exportar" id="Exportar"/>
</form>
</fieldset>
<br>
<fieldset class="boxformularioRelatorio">
<form id="dataRelatorio" method="POST" action="relatorioestoque.php">
    <label for="data_inicio"><b>Relatório de Estoque:</b></label><br>
    <label for="data_inicio"><b>Data Inicio:</b></label>
    <input type="date" name="data_inicio" id="data_inicio" /><br>
    <label for="data_fim"><b>Data Fim:</b></label>
    <input type="date" name="data_fim" id="data_fim" />
    <input type="submit" value="Exportar" id="Exportar"/>
</form>
</fieldset>

<br>
<fieldset class="boxformularioRelatorio">
<form id="dataRelatorio" method="POST" action="relatorio_estoque_completo.php">
    <label for="data_inicio"><b>Exportar em planilha todo o Estoque:</b></label>
    <input type="submit" value="Exportar" id="Exportar"/>
</form>
</fieldset>



</div>
<script>
    var search = document.getElementById('pesquisar');

    search.addEventListener("keydown", function(event) {
        if (event.key === "Enter") 
        {
            searchData();
        }
    });

    function searchData()
    {
        window.location = 'sistema.php?search='+search.value;
    }
</script>
</html>