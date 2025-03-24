<?php include("cabecalhoVol.php")?>
<?php
include('verificarLogin.php');
verificarLogin();

include_once('config.php');
   // print_r($_SESSION);
    if((!isset($_SESSION['usuario'])== true) and ($_SESSION['senha']) == true)
    {
      unset($_SESSION['usuario']);
      unset($_SESSION['senha']);
      header('Location: loginAdmin.php');
      
    }$logado = $_SESSION['usuario'];
    if(!empty($_GET['search']))
    {
        $data = $_GET['search'];
        $sql = "SELECT * FROM vendas WHERE barra LIKE '%$data%' or produto LIKE '%$data%' or modelo LIKE '%$data%' or categoria LIKE '%$data%' or datas LIKE '%$data%' ORDER BY id DESC";
    }
    
?>
     
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Loja Lirio Matriz</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <script src="bootstrap.min.js"></script>
</head>
<body>
<br><br><br>
   
<?php
    echo "<h1 id='BemVindoVendas'>Consulta de Vendas realizadas</h1>";
?>

<div>
    <!-- Botão para acionar a geração do arquivo Excel de toda consulta da pagina
    <a id="exportar" href="criarArquivo.php" target="_blank">
       -->
    </a>
</div>
<br>
<a id="incluirCadastro" value="Vendas Realizadas" href="vendasVol.php">Novo Atendimento</a>
<br>
<fieldset class="boxformularioRelatorio" style="margin-top: 4%;">
<form id="dataRelatorio" method="POST" action="vendasrealizadas.php">
    <label for="data_inicio"><b>Selecionar periodo para consulta:</b></label><br>
    <label for="data_inicio"><b>Data Inicio:</b></label>
    <input type="date" name="data_inicio" id="data_inicio" />
    <div>
    <label for="data_fim"><b>Data Fim:</b></label>
    <input type="date" name="data_fim" id="data_fim" />
    </div>
    <input type="submit" value="Consultar" id="Exportar"/>
</form>
</fieldset>
<div>
<table class="table" id="tabelaLista">
  <thead>
    <tr>
    <th scope="col">Id</th>
      <th scope="col">Barra</th>
      <th scope="col">Produto</th>
      <th scope="col">Modelo</th>
      <th scope="col">Tamanho</th>
      <th scope="col">Categoria</th>
      <th scope="col">Preço</th>
      <th scope="col">Voluntário</th>
      <th scope="col">data</th>
      <th scope="col">hora</th>
      
      
    </tr>
  </thead>
  <tbody>
  <?php
include('config.php');
  
   
  $sql = "SELECT * FROM vendas ORDER BY id DESC";
  $result = $conexao->query($sql);

  // Exiba a lista de produtos e calcule o valor total
$valorTotal = 0; // Variável para armazenar o valor total

        while($user_data = mysqli_fetch_assoc($result))
        {
            echo "<tr>";
            echo "<td>" .$user_data['id']. "</td>";

            echo "<td>" .$user_data['barra']. "</td>";
            
            echo "<td>" .$user_data['produto']. "</td>";

            echo "<td>" .$user_data['modelo']. "</td>";
            
            echo "<td>" .$user_data['tamanho']. "</td>";

            echo "<td>" .$user_data['categoria']. "</td>";

            echo "<td>" .$user_data['valordevenda']. "</td>";
            
            echo "<td>" .$user_data['usuario']. "</td>";

            echo "<td>" .$user_data['datas']. "</td>";
            echo "<td>" .$user_data['hora']. "</td>";

       
            echo "</tr>";
            $valorTotal += $user_data['valordevenda']; // Adicione o valor de venda ao valor total

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