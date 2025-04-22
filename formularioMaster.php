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
<br><br><br>
<?php
    echo "<h1 id='BemVindo'>Acesso Master</h1>";
?>
<div class="produtos-container">
<a id="incluirCadastro" value="Novo Volutario" href="cadastroMembrosAdm.php">Novo Membro(a)</a>
<a id="incluirCadastro" value="Novo Volutario" href="consulta_membros.php">Consultar Membros</a>
<a id="incluirCadastro" value="Novo Volutario" href="consulta_membros_busca.php">Pesquisa Membro(a)</a>
<a id="incluirCadastro" href="cadastroForm.php" value="Novo Cadastro">Inscrições</a>
<a id="incluirCadastro" href="consulta_logs.php" value="Novo Cadastro">Log</a>
<a id="incluirCadastro" href="formularioMaster.php" value="Novo Cadastro">Cadastro Acesso</a>
</div>
<fieldset class="boxformularioAdm">
    <form class="row g-3" action="enviarcadastroAdm.php" method="POST">
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
    <option value="voluntario">Voluntário</option>
    <option value="midia">Midias</option>
    <option value="secretaria">Secretaria</option>
    <option value="master">Master</option>
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