
<?php include("cabecalhoAdm.php")?>
<?php
include('verificarLogin.php');
verificarLogin();
//session_start();
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
        $sql = "SELECT * FROM cadastroAdm WHERE id LIKE '%$data%' or nome LIKE '%$data%' or email LIKE '%$data%' ORDER BY id DESC";
    }
    else
    {
        $sql = "SELECT * FROM cadastroAdm ORDER BY id DESC";
    }
    $result = $conexao->query($sql);
?>
    
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Loja Lirio Matriz</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <script src="bootstrap.min.js"></script>
    <script>
        window.onload = function() {
            // Obtenha a referência ao campo de entrada
            var campo = document.getElementById("campo");

            // Defina o valor do campo usando uma variável
            var valorDaVariavel = "Valor preenchido por variável";
            campo.value = valorDaVariavel;

            // Defina o atributo readonly para impedir a edição pelo usuário
            campo.readOnly = true;
        };
    </script>
</head>
    </head>
<body>
<br >
   <?php
       echo "<h1 id='BemVindoVendas'>Vendedor(a) <U>$logado</u></h1>";
   ?>
<br>
<form id="meuForm" method="POST" action="vendas.php">
        <label id="codBarras" for="codigo_barras">Código de Barras:</label>
        <input type="text" name="codigo_barras" id="codigo_barras" />
        <input type="submit" value="Consultar" id="consultar"/>
    </form>
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
    $host = "Localhost";
    $usuario = "root";
    $senha = "";
    $banco = "lojalirio";

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
    $host = "Localhost";
    $usuario = "root";
    $senha = "";
    $banco = "lojalirio";
    
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
    print_r($datas);
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
            $qtdcomprada = $compra['estoque'];

            if ($qtdcomprada >= 1) {
                // O produto está na tabela de compra e há quantidade suficiente para vender
                // Atualize a quantidade comprada na tabela de compra
                $nova_qtdcomprada = $qtdcomprada - 1;
                $query_update_compra = "UPDATE produtos SET estoque = $nova_qtdcomprada WHERE barra = '$barra'";
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
        $query = "INSERT INTO vendas (barra, produto, modelo, tamanho, categoria, valordevenda, valordecompra, usuario, datas, hora) VALUES ('$barra', '$nomeProduto', '$modelo', '$tamanho', '$categoria', '$valordevenda', '$logado', '$valordecompra', '$datas', '$hora')";
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
echo "<td colspan='2' style='font-size: 22px;'><b>Valor Total:</b></td>";
echo "<td style='font-size: 22px;'>" . $valorTotal . "</td>";
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
                <label class="formaPagamento" for="tipodePagamento"><b>Forma de Pagamento:</b></label><br>
                <select class="tipodePagamento" id="tipodePagamento" name="tipodePagamento" required>
                <option value="">Selecione</option>
                <option value="credito">Crédito</option>
                <option value="debito">Débito</option>
                <option value="pix">Pix</option>
                <option value="dinheiro">A vista</option>
            </select>
            <p>
            <label class="valorTotal" for="tipodePagamento"><b>Valor Total:</b>
            <input type="text" name="valorTotal" value="<?php echo $valorTotal; ?>"></label><br></p>
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
    <script>
    // Tempo de inatividade em milissegundos (1 hora = 3600000 ms)
    const tempoLimite = 3600000;

    // Redireciona para logout após o tempo limite
    setTimeout(() => {
        window.location.href = "sistema.php?timeout=1"; 
    }, tempoLimite);
</script>

</body>
</html>