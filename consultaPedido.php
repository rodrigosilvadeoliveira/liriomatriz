<?php include("cabecalhoIgreja.php")?>
<?php
//include('verificarLogin.php');
//verificarLogin();
//session_start();
include_once('config.php');
   // print_r($_SESSION);
  //  if((!isset($_SESSION['usuario'])== true) and ($_SESSION['senha']) == true)
    //{
 //     unset($_SESSION['usuario']);
   //   unset($_SESSION['senha']);
     // header('Location: login.php');
      
   // }$logado = $_SESSION['usuario'];
    if(!empty($_GET['search']))
    {
        $data = $_GET['search'];
        $sql = "SELECT * FROM vendasprodutos WHERE id LIKE '%$data%' or produto LIKE '%$data%' or modelo LIKE '%$data%' ORDER BY id DESC";
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

    <br><br>
    
<?php
    echo "<h1 id='BemVindo'>Lista de Pedidos Pendentes</h1>";
    ?>
    
    <div id="tabelaSite">
<div class="produtos-container">
        <a id="btncadastroadm" class="butnavegacao"href="vendasrealizadaspanquecas.php">Relatorio de vendas</a>
        <a id="btncadastroadm" class="butnavegacao"href="consultaPedidoentregues.php">Pedidos entregues</a>
        <a id="btncadastroadm" class="butnavegacao"href="consultaPedido.php">Pedidos Pendentes</a>
        </div>
        </div>
<?php
// Conexão ao banco de dados (substitua as credenciais pelo seu ambiente)
include_once('config.php');
// Verifique a conexão
if ($conexao->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
}

// Consulta SQL para obter pedidos pendentes com informações de clientes e produtos
$query = "SELECT
    c.id_clientes AS id_cliente,
    c.nome AS nome_cliente,
    c.telefone AS telefone_cliente,
    p.id_pedidos AS id_pedido,
    p.status AS status_pedido,
    p.data_pedido AS data_pedido,
    p.hora_pedido AS hora_pedido,
    pr.id_lista AS id_lista,
    pr.id_pedido AS id_pedido_produto,
    pr.id_produto AS id_produto,
    pr.produto AS nome_produto,
    pr.modelo AS modelo_produto,
    pr.quantidade AS quantidade_produto,
    pr.preco_unitario AS preco_unitario,
    pr.linhatotal AS linhatotal_produto
FROM
    clientes AS c
JOIN
    pedidos AS p ON c.id_clientes = p.id_clientes
JOIN
    produto AS pr ON p.id_pedidos = pr.id_pedido
WHERE
    p.status = 'pendente'";

$resultado = $conexao->query($query);

// Verifique se a consulta retornou resultados
if ($resultado->num_rows > 0) {
    
    echo "<div class='scroll-horizontal'>";
    echo "<table class='table' id='tabelaLista'>";
    echo "<tr><th>Pedido</th><th>Status</th></th><th>Nome</th><th>Telefone</th>
    <th>Data do Pedido</th><th>SKU</th><th>Produto</th><th>Tp.Produto</th><th>Vl.Prod</th><th>Vl.Total</th>
    <th><img class='' id='' src='img/tucano.png' align='' width='30' height='30'></th></tr>";
    
    while ($row = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id_pedido'] . "</td>";
        echo "<td>" . $row['status_pedido'] . "</td>";
       
        echo "<td>" . $row['nome_cliente'] . "</td>";
        echo "<td>" . $row['telefone_cliente'] . "</td>";
        
        
        echo "<td>" . $row['data_pedido'] . "</td>";
        echo "<td>" . $row['id_produto'] . "</td>";
        echo "<td>" . $row['nome_produto'] . "</td>";
        echo "<td>" . $row['modelo_produto'] . "</td>";
        echo "<td>" . $row['preco_unitario'] . "</td>";
        echo "<td>" . $row['linhatotal_produto'] . "</td>";
        echo "<td><button class='concluir-button' data-id='" . $row['id_pedido'] . "'>Concluir</button></td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "</div>";
} else {
    echo "Nenhum pedido pendente encontrado.";
}

// Feche a conexão com o banco de dados
$conexao->close();
?>
    
    </tr>
  </tbody>
</table>
</div>
<div id="modal-confirmacao" style="display:none; position: fixed; top: 0; left: 0;
    width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000;">

    <div style="background: #fff; padding: 20px; border-radius: 8px; width: 300px; 
        margin: 100px auto; text-align: center; box-shadow: 0 0 10px #000;">
        <p>Pedido concluído com sucesso!</p>
        <button id="modal-ok">OK</button>
    </div>
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
        window.location = 'consultaCatalogo.php?search='+search.value;
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
    document.getElementById('modelo').addEventListener('change', function() {
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
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('qtdcomprada').addEventListener('change', function() {
      var categoriaSelecionada = this.value;
      var linhas = document.querySelectorAll('tbody tr');

      linhas.forEach(function(linha) {
        var categoriaTd = linha.querySelector('td:nth-child(8)'); // Quarta coluna é a de Categoria
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
   <script>
   document.addEventListener('click', function (event) {
    if (event.target.classList.contains('concluir-button')) {
        const idPedido = event.target.getAttribute('data-id');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'atualizarStatusPedido.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                const resposta = JSON.parse(xhr.responseText);
                if (resposta.success) {
                    mostrarModalConfirmacao();
                } else {
                    alert('Erro ao atualizar o pedido: ' + resposta.message);
                }
            } else {
                console.error('Erro na requisição');
            }
        };
        xhr.send('idPedido=' + idPedido);
    }
});

// Função para exibir o modal
function mostrarModalConfirmacao() {
    const modal = document.getElementById('modal-confirmacao');
    modal.style.display = 'block';

    const okBtn = document.getElementById('modal-ok');
    okBtn.onclick = function () {
        window.location.href = 'consultaPedido.php';
    };
}
</script>

</html>