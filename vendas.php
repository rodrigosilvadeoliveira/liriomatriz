
<?php include("cabecalhoAdm.php")?>
<?php
include('verificarLogin.php');
verificarLogin();
//session_start();
include('verifica_permissao.php');
ini_set('display_errors', 1); // Exibir erros no navegador (para fins de desenvolvimento)
error_reporting(E_ALL); // Relatar todos os tipos de erro (para fins de desenvolvimento)
date_default_timezone_set('America/Sao_Paulo'); // Definir fuso horário para Brasil/Brasília
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
        $sql = "SELECT * FROM cadastroadm WHERE id LIKE '%$data%' or nome LIKE '%$data%' or email LIKE '%$data%' ORDER BY id DESC";
    }
    else
    {
        $sql = "SELECT * FROM cadastroadm ORDER BY id DESC";
    }
    $result = $conexao->query($sql);
?>
    
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Sistema Flowers</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <script src="bootstrap.min.js"></script>
    <script src="quagga.min.js"></script>
    </head>
<body>
<br >
   <?php
       echo "<h1 id='BemVindo'>Vendedor(a) <U>$logado</u></h1>";
   ?>
<br>

<form id="meuForm" method="POST" action="vendas.php">
        <label for="codigo_barras"><b>Código de Barras:</b></label>
       <input type="text" name="codigo_barras" id="codigo_barras" />
        <input type="submit" value="Consultar" id="consultar"/>
    </form>
    <button id="openModal"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-camera" viewBox="0 0 16 16">
  <path d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1v6zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2z"/>
  <path d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5zm0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zM3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
</svg> Abrir Câmera</button>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-contentcamera">
        <div class="camera" id="camera" autoplay>
            <video autoplay="true" preload="auto" src muted="true" playsinline="true">
                <canvas class="drawingBuffer">
            </video>
        </div>
        <form id="meuForm" method="POST" action="vendas.php">
        <input type="text" class="lercodigo" name="codigo_barras" id="codigo_barras1" />
        <input type="submit" value="Consultar" id="consultar"/>
    </form>    
            <button id="closeModal">Fechar Câmera</button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script>
        let isModalOpen = false;

        document.getElementById('openModal').addEventListener('click', function () {
            document.getElementById('myModal').style.display = 'block';
            isModalOpen = true;

            // Inicialize o QuaggaJS quando o modal for aberto
            Quagga.init({
                inputStream: {
                    name: 'Live',
                    type: 'LiveStream',
                    target: document.querySelector('#camera')
                },
                decoder: {
                    readers: ['ean_reader', 'code_128_reader', 'upc_reader']
                }
            }, function (err) {
                if (err) {
                    console.error(err);
                    return;
                }
                Quagga.start();
            });

            document.getElementById('closeModal').addEventListener('click', function () {
                // Pare a câmera e feche o modal
                Quagga.stop();
                document.getElementById('myModal').style.display = 'none';
                isModalOpen = false;
            });

            Quagga.onDetected(function (data) {
                if (isModalOpen) {
                    var codigoBarras = data.codeResult.code;
                    var tipoCodigo = data.codeResult.format;
                    document.getElementById('codigo_barras1').value = codigoBarras;
                alert('Tipo de código de barras: ' + tipoCodigo);
                }
            });
        });
    </script>
<table class="table" id="tabelaAtendimento">
  <thead>
    <tr>
    <th scope="col">Id</th>
      <th scope="col">Barra</th>
      <th scope="col">Produto</th>
      <th scope="col">Modelo</th>
      <th scope="col">Tamanho</th>
      <th scope="col">Categoria</th>
      <th scope="col">Preço</th>

      <th scope="col">......</th>
    </tr>
  </thead>
  <tbody>
  <?php
include_once('config.php');

// Verifique se a lista de produtos já existe na sessão
if (!isset($_SESSION['produtos'])) {
    // Se não existir, crie uma nova lista vazia
    $_SESSION['produtos'] = array();
}

// Verifique se o código de barras foi enviado pelo formulário
if (isset($_POST['codigo_barras'])) {
    $codigoBarras = $_POST['codigo_barras'];

    // Realize a consulta no banco de dados
 include_once('configUser.php');

    $conexao = mysqli_connect($host, $usuario, $senha, $banco);

    if (!$conexao) {
        die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM produtos WHERE barra = '$codigoBarras' or id = '$codigoBarras'";
    $resultado = mysqli_query($conexao, $query);

    // Verifique se o produto foi encontrado
    if (mysqli_num_rows($resultado) > 0) {
        $row = mysqli_fetch_assoc($resultado);
        $_SESSION['produtos'][] = $row; // Adicione o produto à lista na sessão
    } else {
        echo "Nenhum produto encontrado com o código de barras informado.";
    }

    // Feche a conexão com o banco de dados
    mysqli_close($conexao);
}
// Verifique se o botão de confirmação de compra foi clicado
if (isset($_POST['confirmar_compra'])) {
    // Realize as ações necessárias para confirmar a compra, como salvar no banco de dados, enviar e-mails, etc.

    // Conecte-se ao banco de dados
include_once('configUser.php');
    
    $conexao = mysqli_connect($host, $usuario, $senha, $banco);
    $valorTotal = $_POST['valorTotal'];
    $tipodePagamento = $_POST['tipodePagamento'];
    $datas = date('Y-m-d'); // Data e hora atual
    $hora = date('H:i:s'); // Data e hora atual
// Insira as informações da compra no banco de dados
$query_pagamento = "INSERT INTO pagamento (valorTotal, tipodePagamento, datas, hora) VALUES ('$valorTotal', '$tipodePagamento', '$datas', '$hora')";
mysqli_query($conexao, $query_pagamento);

    if (!$conexao) {
        die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
    }

    $logado = $_SESSION['usuario'];
    // Insira as informações da compra no banco de dados
    $datas = date('Y-m-d'); // Data e hora atual
    $hora = date('H:i:s'); // Data e hora atual
    print_r($dataHora);
    print_r($hora);
    foreach ($_SESSION['produtos'] as $produto) {
        $barra = $produto['barra'];
        $nomeProduto = $produto['produto'];
        $modelo = $produto['modelo'];
        $tamanho = $produto['tamanho'];
        $categoria = $produto['categoria'];
        $valordevenda = $produto['valordevenda'];
        $valordecompra = $produto['valordecompra'];

        // Verifique se o produto está na tabela de compra e se há quantidade suficiente para vender
        $query_compra = "SELECT * FROM produtos WHERE barra = '$barra'";
        $resultado_compra = mysqli_query($conexao, $query_compra);

        if (mysqli_num_rows($resultado_compra) > 0) {
            $compra = mysqli_fetch_assoc($resultado_compra);
            $estoque = $compra['estoque'];

            if ($estoque >= 1) {
                // O produto está na tabela de compra e há quantidade suficiente para vender
                // Atualize a quantidade comprada na tabela de compra
                $nova_estoque = $estoque - 1;
                $query_update_compra = "UPDATE produtos SET estoque = $nova_estoque WHERE barra = '$barra'";
                mysqli_query($conexao, $query_update_compra);

                // Registre a venda na tabela de vendas
                $query_venda = "INSERT INTO vendas (barra, produto, modelo, tamanho, categoria, valordevenda, valordecompra, usuario, datas, hora) VALUES ('$barra', '$nomeProduto', '$modelo', '$tamanho', '$categoria', '$valordevenda', '$valordecompra', '$logado', '$datas', '$hora')";
                mysqli_query($conexao, $query_venda);
            } else {
              //  echo "Quantidade insuficiente para vender o produto com o código de barras: $barra";
                echo "<script>alert('Quantidade insuficiente para vender o produto com o código de barras: $barra');</script>";
            }
        } else {
            echo "Produto com o código de barras $barra não está na tabela de compra.";
        }
    }
    // Redirecionar para o formulário após exibir a mensagem de alerta
echo "<script>window.location.href = 'vendas.php';</script>";

    // Feche a conexão com o banco de dados (se você já está conectado, essa parte não é necessária)
    mysqli_close($conexao);

    // Limpe a lista de produtos na sessão
    $_SESSION['produtos'] = array();

    // Redirecione para a mesma página para atualizar a exibição
    header("Location: vendas.php");
    exit();


        // Query de inserção
        $query = "INSERT INTO vendas (barra, produto, modelo, tamanho, categoria, valordevenda, valordecompra, usuario, datas, hora) VALUES ('$barra', '$nomeProduto', '$modelo', '$tamanho', '$categoria', '$valordevenda', '$valordecompra', '$logado', '$datas', '$hora')";
        mysqli_query($conexao, $query);
    

    // Feche a conexão com o banco de dados
    mysqli_close($conexao);

    // Limpe a lista de produtos na sessão
    $_SESSION['produtos'] = array();

    // Redirecione para a mesma página para atualizar a exibição
    header("Location: vendas.php");
    exit();
}

// Exclua um produto da lista se o ID for fornecido na URL
if (isset($_GET['id'])) {
  $idProduto = $_GET['id'];

  // Encontre o índice do produto com base no ID
  $indiceProduto = -1;
  foreach ($_SESSION['produtos'] as $indice => $produto) {
      if ($produto['id'] == $idProduto) {
          $indiceProduto = $indice;
          break;
      }
  }

  // Se o produto for encontrado, remova-o da lista
  if ($indiceProduto != -1) {
      array_splice($_SESSION['produtos'], $indiceProduto, 1);
  }
// Verifique se a lista de produtos existe na sessão
if (!isset($_SESSION['produtos'])) {
    $_SESSION['produtos'] = array();
}

   // Se o produto for encontrado, remova-o da lista
    if ($indiceProduto != -1) {
        unset($_SESSION['produtos'][$indiceProduto]);
    }
}

// Exiba a lista de produtos e calcule o valor total
$valorTotal = 0; // Variável para armazenar o valor total

// Exiba a lista de produtos
foreach ($_SESSION['produtos'] as $produto) {
    echo "<tr>";
    echo "<td>" . $produto['id'] . "<br>";
    echo "<td>" . $produto['barra'] . "<br>";
    echo "<td>" . $produto['produto'] . "<br>";
    echo "<td>" . $produto['modelo'] . "<br>";
    echo "<td>" . $produto['tamanho'] . "<br>";
    echo "<td>" . $produto['categoria'] . "<br>";
    echo "<td>" . $produto['valordevenda'] . "<br>";
    echo "<br>";
    echo "<td>
            <a class='btn btn-sm btn-danger' href='deleteprodutodalista.php?id=" . $produto['id'] . "' title='Deletar'>
                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                    <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z'/>
                </svg>
            </a>
        </td>";
    echo "</tr>";
    $valorTotal += $produto['valordevenda']; // Adicione o valor de venda ao valor total
}

// Exiba o valor total
echo "<tr>";
echo "<td colspan='4'>Valor Total:</td>";
echo "<td>" . $valorTotal . "</td>";
echo "</tr>";


?>
<br>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmacaoModal" id="confirmar">
    Confirmar Compra
</button>

<!-- Modal de confirmação de compra -->
<div class="modal fade" id="confirmacaoModal" tabindex="-1" aria-labelledby="confirmacaoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmacaoModalLabel">Confirmar Compra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
           <!-- <div class="modal-body">
                Verifique valor total da compra e selecione forma de pagamento:
            </div>-->
            <div class="modal-footer">
            <form method="POST" id="selectPagamento" action="vendas.php">
                <label for="tipodePagamento"><b>Forma de Pagamento:</b></label><br>
                <select id="tipodePagamento" name="tipodePagamento" required>
                <option value="">Selecione</option>
                <option value="credito">Crédito</option>
                <option value="debito">Débito</option>
                <option value="pix">Pix</option>
                <option value="dinheiro">A vista</option>
                <option value="pendente">Pendente</option>
            </select>
            <label for="tipodePagamento"><b>Valor Total:</b>
            <input type="text" name="valorTotal" value="<?php echo $valorTotal; ?>" required></label><br>
            <label for="tipodePagamento"><b>Obs:</b>
            <input type="text" name="obs"></label><br>
            <p>Tem certeza de que deseja confirmar a compra?</p>
            <button type="submit" name="confirmar_compra" class="btn btn-primary" style= 'margin-left:15%'>Confirmar</button>
        </form>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function manterCursor() {
        var codigoBarras = document.getElementById('codigo_barras').value;

        // Restante do seu código de envio e manipulação do formulário

        // Coloca o foco de volta no campo de entrada após o envio do formulário
        document.getElementById('codigo_barras').focus();
    }

    // Chama a função manterCursor() após o carregamento completo do DOM
    document.addEventListener("DOMContentLoaded", manterCursor);
</script>
</table>
<table class="table" id="tabelaatendimentomobile">
  <thead>
    <tr>
    <th scope="col">Descrição</th>
      
      <th scope="col">Preço</th>

      <th scope="col">......</th>
    </tr>
  </thead>
  <tbody>
  <?php
foreach ($_SESSION['produtos'] as $produto) {
    echo "<tr>";
    echo "<td>";
    echo "<strong>ID:</strong> " . $produto['id'] . "<br>";
    echo "<strong>Código de Barras:</strong> " . $produto['barra'] . "<br>";
   echo "<strong>Produto:</strong> " . $produto['produto'] . "<br>";
    echo "<strong>Modelo:</strong> " . $produto['modelo'] . "<br>";
    echo "<strong>Tamanho:</strong> " . $produto['tamanho'] . "<br>";
    echo "</td>";
    echo "<td>" . $produto['valordevenda'] . "<br>";
    
    echo "<td>
            <a class='btn btn-sm btn-danger' href='deleteprodutodalista.php?id=" . $produto['id'] . "' title='Deletar'>
                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                    <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z'/>
                </svg>
            </a>
        </td>";
    echo "</tr>";
    
}

// Exiba o valor total
echo "<tr>";
echo "<td colspan='2'>Valor Total:</td>";
echo "<td>" . $valorTotal . "</td>";
echo "</tr>";


?>
<br>

</table>
    </body>
</html>