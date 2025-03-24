<?php include("cabecalhoAdm.php")?>
<?php
include('verificarLogin.php');
verificarLogin();



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
    <form class="row g-3" action="enviarcadastroAdm.php" method="POST">
      <h1>Cadastro ADM</h1>
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
        <option value="voluntario">Voluntário</option>
        </select>
</div>

  <div class="col-3">
    <button type="submit" name="submitAdm" id="submitAdm" class="btn btn-primary">Enviar</button>
  </div>
</form>
</fieldset>
<!-- 
<fieldset class="boxformularioVol">
    <form class="row g-3" action="enviarcadastroVol.php" method="POST">
      <h1>Cadastro Voluntario</h1>
    <div class="col-md-5">
    <label for="nome" class="form-label">Nome completo</label>
    <input type="text" name="nome" id="nome" class="form-control">
  </div>
  <div class="col-md-5">
    <label for="usuario" class="form-label">Login</label>
    <input type="text" name="usuario" id="usuario" class="form-control">
  </div>
  <div class="col-md-5">
    <label for="senha" class="form-label">Senha</label>
    <input type="password"  name="senha" id="senha" class="form-control">
  </div>
  <div class="col-md-5">
    <label for="email" class="form-label">Email</label>
    <input type="email"  name="email" id="email" class="form-control">
  </div>
  
  <div class="col-3">
    <label for="telefone" class="form-label">Telefone</label>
    <input type="tel" class="form-control" name="telefone" id="telefone" placeholder="dd numero">
  </div>
  <div class="col-3">
    <label for="celular" class="form-label">Celular</label>
    <input type="tel" class="form-control" name="celular" id="celular" placeholder="dd numero">
  </div>

  <div class="col-3">
    <button type="submit" name="submitAdm" id="submitAdm" class="btn btn-primary">Enviar</button>
  </div>
</form>
</fieldset> -->

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