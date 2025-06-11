
<?php

include_once('config.php');
   // print_r($_SESSION);
    
    if(!empty($_GET['search']))
    {
        $data = $_GET['search'];
        $sql = "SELECT * FROM produto WHERE barra LIKE '%$data%' LIKE '%$data%' or modelo LIKE '%$data%' or categoria LIKE '%$data%' or datas LIKE '%$data%' ORDER BY id DESC";
    }
    
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
    <?php include('cabecalhoMembros.php');?>
    </div>

<br><br><br>
   
<?php
    echo "<h1 id='BemVindoVendas'>Consulta de Vendas das Panquecas</h1>";
?>

<div id="tabelaSite">
<div class="produtos-container">
        <a id="btncadastroadm" class="butnavegacao"href="vendasrealizadaspanquecas.php">Relatorio de vendas</a>
        <a id="btncadastroadm" class="butnavegacao"href="consultaPedidoentregues.php">Pedidos entregues</a>
        <a id="btncadastroadm" class="butnavegacao"href="consultaPedido.php">Pedidos Pendentes</a>
        </div>
        </div>

<div>
    <!-- Botão para acionar a geração do arquivo Excel de toda consulta da pagina
    <a id="exportar" href="criarArquivo.php" target="_blank">
       -->
    </a>
</div>

<div>
<table class="table" id="tabelaLista">
  <thead>
    <tr>
    <th scope="col">Id</th>
      <th scope="col">Produto</th>
      <th scope="col">Modelo</th>
      <th scope="col">Preço</th>
      
    </tr>
  </thead>
  <tbody>
  <?php
include('config.php');
  
   
  $sql = "SELECT * FROM produto WHERE produto = 'Teste' ORDER BY id_produto DESC";
  $result = $conexao->query($sql);
            
            echo "<td>" .$user_data['produto']. "</td>";

            echo "<td>" .$user_data['modelo']. "</td>";
            
            echo "<td>" .$user_data['preco_unitario']. "</td>";
            
            echo "</tr>";
            $valorTotal += $user_data['preco_unitario']; // Adicione o valor de venda ao valor total

        }
        echo "<tr>";
        echo "<td colspan='4'><b>Valor Total:</b></td>";
        echo "<td>" . $valorTotal . "</td>";
        echo "</tr>";

    

  ?>
    
    </tr>
  </tbody>
</table>
</div>

<script>
    // Tempo de inatividade em milissegundos (1 hora = 3600000 ms)
    const tempoLimite = 3600000;

    // Redireciona para logout após o tempo limite
    setTimeout(() => {
        window.location.href = "sistema.php?timeout=1"; 
    }, tempoLimite);
</script>

</body>
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
        window.location = 'vendasrealizadas.php?search='+search.value;
    }
</script>
</html> 