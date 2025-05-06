<?php include("cabecalhoAdm.php")?>
<?php
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');
include_once('config.php');

if(!empty($_GET['id']))
{
    $id = $_GET['id'];
    $sqlSelect = "SELECT * FROM produtos WHERE id=$id";
    $result = $conexao->query($sqlSelect);
    if($result->num_rows > 0)
    {
        while($user_data = mysqli_fetch_assoc($result))
        {
            $produto = $user_data['produto'];
            $modelo = $user_data['modelo'];
            $tamanho = $user_data['tamanho'];
            $categoria = $user_data['categoria'];
            $valordevenda = $user_data['valordevenda'];
            $estoque = $user_data['estoque'];
            $valordecompra = $user_data['valordecompra'];
            $fornecedor = $user_data['fornecedor'];
            $barra = $user_data['barra'];
            
        }
    }
    else
    {
        header('Location: consulta_adm.php');
    }
}
else
{
    header('Location: consulta_adm.php');
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
    <form class="row g-3" action="saveEditProduto.php" method="POST">
      <h1>Atualizar Cadastro de Produto</h1>
    <div class="col-md-5">
    <label for="produto" class="form-label">Produto</label>
    <input type="text" name="produto" id="produto" class="form-control" value="<?php echo $produto ?>">
  </div>
  <div class="col-md-5">
    <label for="modelo" class="form-label">Modelo</label>
    <input type="text" name="modelo" id="modelo" class="form-control" value="<?php echo $modelo ?>">
  </div>
  <div class="col-md-5">
    <label for="tamanho" class="form-label">Tamanho</label>
    <input type="varchar" name="tamanho" id="tamanho" class="form-control" value="<?php echo $tamanho ?>">
  </div>
  <div class="col-md-5">
    <label for="categoria" class="form-label">Categoria</label>
    <input type="text"  name="categoria" id="categoria" class="form-control" value="<?php echo $categoria ?>">
  </div>
  <div class="col-md-5">
    <label for="valordevenda" class="form-label">Valor de Venda</label>
    <input type="decimal"  name="valordevenda" id="valordevenda" class="form-control" value="<?php echo $valordevenda ?>">
  </div>
  
  <div class="col-3">
    <label for="estoque" class="form-label">Qtd Estoque</label>
    <input type="number" class="form-control" name="estoque" id="estoque" placeholder="" value="<?php echo $estoque ?>">
  </div>
  <div class="col-3">
    <label for="valordecompra" class="form-label">Valor de Compra</label>
    <input type="decimal" class="form-control" name="valordecompra" id="valordecompra" placeholder="exemplo 10.25" value="<?php echo $valordecompra ?>">
  </div>
  <div class="col-3">
    <label for="fornecedor" class="form-label">Fornecedor</label>
    <input type="varchar" class="form-control" name="fornecedor" id="fornecedor" placeholder="informar nome" value="<?php echo $fornecedor ?>">
  </div>
  <div class="col-3">
    <label for="barra" class="form-label">Cód.Barra</label>
    <input type="number" class="form-control" name="barra" id="barra" placeholder="codigo de barra" value="<?php echo $barra ?>">
  </div>

  <div class="col-3">
  <input type="hidden" name="id" value="<?php echo $id ?>">
    <button type="submit" name="update" id="update" class="btn btn-primary">Enviar</button>
    <button href="consultaProdutosAdm.php" name="cancelar" id="cancelar" class="btn btn-primary">Cancelar</button>
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