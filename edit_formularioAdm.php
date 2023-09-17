<?php include("cabecalhoAdm.php")?>
<?php
include('verificarLogin.php');
verificarLogin();

include_once('config.php');

if(!empty($_GET['id']))
{
    $id = $_GET['id'];
    $sqlSelect = "SELECT * FROM cadastroadm WHERE id=$id";
    $result = $conexao->query($sqlSelect);
    if($result->num_rows > 0)
    {
        while($user_data = mysqli_fetch_assoc($result))
        {
            $nome = $user_data['nome'];
            $usuario = $user_data['usuario'];
            $senha = $user_data['senha'];
            $email = $user_data['email'];
            $telefone = $user_data['telefone'];
            $celular = $user_data['celular'];
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
    <form class="row g-3" action="saveEditAdm.php" method="POST">
      <h1>Alteração Cadastro</h1>
    <div class="col-md-5">
    <label for="nome" class="form-label">Nome completo</label>
    <input type="text" name="nome" id="nome" class="form-control" id="nome" value="<?php echo $nome ?>">
  </div>
  <div class="col-md-5">
    <label for="usuario" class="form-label">Login</label>
    <input type="text" name="usuario" id="usuario" class="form-control" id="usuario" value="<?php echo $usuario ?>">
  </div>
  <div class="col-md-5">
    <label for="senha" class="form-label">Senha</label>
    <input type="password"  name="senha" id="senha" class="form-control" value="<?php echo $senha ?>">
  </div>
  <div class="col-md-5">
    <label for="email" class="form-label">Email</label>
    <input type="email"  name="email" id="email" class="form-control" value="<?php echo $email ?>">
  </div>
  
  <div class="col-3">
    <label for="telefone" class="form-label">Telefone</label>
    <input type="tel" class="form-control" name="telefone" id="telefone" placeholder="dd numero" value="<?php echo $telefone ?>">
  </div>
  <div class="col-3">
    <label for="celular" class="form-label">Celular</label>
    <input type="tel" class="form-control" name="celular" id="celular" placeholder="dd numero" value="<?php echo $celular ?>">
  </div>

  <div class="col-3">
  <input type="hidden" name="id" value="<?php echo $id ?>">
    <button type="submit" name="update" id="update" class="btn btn-primary">Atualizar</button>
    <button href="consulta_adm.php" name="cancelar" id="cancelar" class="btn btn-primary">Cancelar</button>
  </div>
</form>
</fieldset>

</body>
</html>