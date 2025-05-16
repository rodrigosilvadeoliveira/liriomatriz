<?php include("cabecalhoigreja.php")?>
<?php
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');


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
<?php
    echo "<h1 id='BemVindo'>Acesso Master</h1>";
?>
<div class="navegacao">
   <?php include("navegacao.php")?>
   </div>
   
<fieldset class="boxformularioMembrosAdm">
    <form class="row g-3" action="enviarcadastroadm.php" method="POST">
      <h1>Cadastro Usuarios</h1>
    <div class="col-md-5">
    <label for="nome" class="form-label">*Nome completo</label>
    <input type="text" name="nome" id="nome" class="form-control" required>
  </div>
  <div class="col-md-5">
    <label for="usuario" class="form-label">*Login</label>
    <input type="text" name="usuario" id="usuario" class="form-control" required>
  </div>
  <div class="col-md-5">
    <label for="senha" class="form-label">*Senha</label>
    <input type="password"  name="senha" id="senha" class="form-control" required>
  </div>
  <div class="col-md-5">
    <label for="email" class="form-label">Email</label>
    <input type="email"  name="email" id="email" class="form-control">
  </div>
  
  <div class="col-3">
    <label for="telefone" class="form-label">*Telefone</label>
    <input type="tel" class="form-control" name="telefone" id="telefone" placeholder="dd numero">
  </div>
  <div class="col-3">
    <label for="celular" class="form-label">Celular</label>
    <input type="tel" class="form-control" name="celular" id="celular" placeholder="dd numero" required>
  </div>
  <div class="col-md-3">
    <label for="inputState" class="form-label">*Perfil:</label>
    <select id="nivel_acesso" class="form-select" name="nivel_acesso">
    <option value="">Selecione</option>
    <option value="admin">Administrador</option>
    <option value="live">Live</option>
    <option value="master">Master</option>
    <option value="midia">Midias</option>
    <option value="secretaria">Secretaria</option>
    <option value="voluntario">Voluntário</option>
    </select>
</div>

  <div class="col-3">
    <button type="submit" name="submitAdm" id="submitAdm" class="btn btn-primary">Enviar</button>
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