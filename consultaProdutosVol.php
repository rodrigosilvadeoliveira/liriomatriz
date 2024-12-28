<?php include("cabecalhoVol.php")?>
<?php
include('verificarLogin.php');
verificarLogin();
//session_start();
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
        $sql = "SELECT * FROM produtos WHERE id LIKE '%$data%' or produto LIKE '%$data%' or modelo LIKE '%$data%' or categoria LIKE '%$data%' ORDER BY id DESC";
    }
    else
    {
        $sql = "SELECT * FROM produtos ORDER BY id DESC";
    }
    $result = $conexao->query($sql);
?>
     
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Consulta Produtos Lirio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <br><br>
    <div class="linkTitulo">
<?php
    echo "<h1 id='BemVindo'>Catalogo de Produtos e Estoque</h1>";
?>
</div>
<div>
<a id="incluirCadastro" href="cadastroProdutoVol.php" value="Novo Cadastro">Novo Produto</a>
<table class="table" id="tabelaLista">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Barra</th>
      <th scope="col">Produto</th>
      <th scope="col">Modelo</th>
      <th scope="col">Tamanho</th>
      <th scope="col">Categoria</th>
      <th scope="col">Valor de Venda</th>
      <th scope="col">Estoque</th>
      <th scope="col">Valor de Compra</th>
      <th scope="col">Fornecedor</th>
      <th scope="col">Data e hora</th>
      
    </tr>
  </thead>
  <tbody>
  <?php
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
            
            echo "<td>" .$user_data['estoque']. "</td>";

            echo "<td>" .$user_data['valordecompra']. "</td>";
            
            echo "<td>" .$user_data['fornecedor']. "</td>";
            
            echo "<td>" .$user_data['datas']. "</td>";

            echo "<td>" .$user_data['hora']. "</td>";
            
       
            echo "</tr>";

        }



  ?>
    
    </tr>
  </tbody>
</table>
</div>

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
        window.location = 'consultaProdutosVol.php?search='+search.value;
    }
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('categoria').addEventListener('change', function() {
      var categoriaSelecionada = this.value;
      var linhas = document.querySelectorAll('tbody tr');

      linhas.forEach(function(linha) {
        var categoriaTd = linha.querySelector('td:nth-child(4)'); // Quarta coluna é a de Categoria
        var categoriaProduto = categoriaTd.textContent.toLowerCase().trim(); // Pegando o texto da categoria e colocando em minúsculas

        // Verificando se a categoria selecionada é igual à categoria do produto na linha atual
        if (categoriaSelecionada === '' || categoriaProduto === categoriaSelecionada) {
          linha.style.display = 'table-row'; // Mostra a linha
        } else {
          linha.style.display = 'none'; // Esconde a linha
        }
      });
    });
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('marca').addEventListener('change', function() {
      var categoriaSelecionada = this.value;
      var linhas = document.querySelectorAll('tbody tr');

      linhas.forEach(function(linha) {
        var categoriaTd = linha.querySelector('td:nth-child(5)'); // Quarta coluna é a de Categoria
        var categoriaProduto = categoriaTd.textContent.toLowerCase().trim(); // Pegando o texto da categoria e colocando em minúsculas

        // Verificando se a categoria selecionada é igual à categoria do produto na linha atual
        if (categoriaSelecionada === '' || categoriaProduto === categoriaSelecionada) {
          linha.style.display = 'table-row'; // Mostra a linha
        } else {
          linha.style.display = 'none'; // Esconde a linha
        }
      });
    });
  });
</script>
<script>
  // Obtenha o elemento select
const categoriaSelect = document.getElementById('categoria');

// Array com novas categorias
const novasCategorias = ['teste', 'Teste2'];

// Função para adicionar as novas categorias ao select
function adicionarNovasCategorias() {
  for (const novaCategoria of novasCategorias) {
    const option = document.createElement('option');
    option.value = novaCategoria;
    option.textContent = novaCategoria;
    categoriaSelect.appendChild(option);
  }
}

// Chame a função para adicionar as novas categorias
adicionarNovasCategorias();
  </script>
</html>