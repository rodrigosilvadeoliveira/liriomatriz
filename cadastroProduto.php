<?php include("cabecalhoAdm.php")?>
<?php
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');
ini_set('display_errors', 1); // Exibir erros no navegador (para fins de desenvolvimento)
error_reporting(E_ALL); // Relatar todos os tipos de erro (para fins de desenvolvimento)
date_default_timezone_set('America/Sao_Paulo'); // Definir fuso horário para Brasil/Brasília

if(isset($_POST['submitPro']))
{
include_once("config.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $barra = $_POST["barra"];

    // Consulta para verificar se o código de barras já existe
    $sql = "SELECT * FROM produtos WHERE barra = '$barra'";
    $result = $conexao->query($sql);

    if ($result->num_rows > 0) {
        // Código de barras já existe, apresentar mensagem de alerta
        echo "<script>alert('Código de barras já registrado no banco de dados.');</script>";
        
    } else {
        // Código de barras não existe, prosseguir com o cadastro
        // ... (código para inserir o registro no banco de dados)
    }
    
}
// Redirecionar para o formulário após exibir a mensagem de alerta
echo "<script>window.location.href = 'cadastroProduto.php';</script>";
error_reporting(0);
// Insira as informações da compra no banco de dados
// Data e hora atual
$datas = date('Y-m-d'); // Data e hora atual
$hora = date('H:i:s');
print_r($dataHora);
$produto = $_POST['produto'];
$modelo=$_POST['modelo'];
$tamanho=$_POST['tamanho'];
$categoria = $_POST['categoria'];
$valordevenda = $_POST['valordevenda'];
$estoque = $_POST['estoque'];
$valordecompra = $_POST['valordecompra'];
$fornecedor = $_POST['fornecedor'];
$barra = $_POST['barra'];

$result = mysqli_query($conexao, "INSERT INTO produtos(produto,modelo,tamanho,categoria,valordevenda,estoque,valordecompra,fornecedor,barra,datas,hora) 
VALUES ('$produto','$modelo','$tamanho','$categoria','$valordevenda','$estoque','$valordecompra','$fornecedor','$barra','$datas','$hora')");

header('Location: cadastroProduto.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Loja Liro Matriz</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<br>
<!--
<a href="sistema.php" >
<svg xmlns="http://www.w3.org/2000/svg" id="voltar" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
  <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1z"/>
</svg>
</a>
-->
<fieldset class="boxformularioAdm">
    <form class="row g-3" action="cadastroProduto.php" method="POST">
      <h1>Cadastro de Produto</h1>
    <div class="col-md-5">
    <label for="produto" class="form-label">Produto</label>
    <input type="text" name="produto" id="produto" class="form-control">
  </div>
  <div class="col-md-5">
    <label for="modelo" class="form-label">Modelo</label>
    <input type="text" name="modelo" id="modelo" class="form-control">
  </div>
  <div class="col-md-5">
    <label for="tamanho" class="form-label">Tamanho</label>
    <input type="varchar" name="tamanho" id="tamanho" class="form-control">
  </div>
  <div class="col-md-5">
    <label for="categoria" class="form-label">Categoria</label>
    <input type="text"  name="categoria" id="categoria" class="form-control">
  </div>
  <div class="col-md-5">
    <label for="valordevenda" class="form-label">Valor de Venda</label>
    <input type="decimal"  name="valordevenda" id="valordevenda" class="form-control">
  </div>
  
  <div class="col-3">
    <label for="estoque" class="form-label">Qtd Estoque</label>
    <input type="number" class="form-control" name="estoque" id="estoque" placeholder="">
  </div>
  <div class="col-3">
    <label for="valordecompra" class="form-label">Valor de Compra</label>
    <input type="decimal" class="form-control" name="valordecompra" id="valordecompra" placeholder="Ex: 10.25">
  </div>
  <div class="col-3">
    <label for="fornecedor" class="form-label">Fornecedor</label>
    <input type="varchar" class="form-control" name="fornecedor" id="fornecedor" placeholder="informar nome">
  </div>
  <div class="col-3">
    <label for="barra" class="form-label">Cód.Barra</label>
    <input type="number" class="form-control" name="barra" id="barra" placeholder="codigo de barra">
  </div>

  <div class="col-3">
    <button type="submit" name="submitPro" id="submitPro" class="btn btn-primary">Enviar</button>
  </div>
  
</form>
</fieldset>
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