<?php
if(isset($_POST['submitAdm']))
{
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $nascimento = $_POST['nascimento'];
    $data = $_POST['data'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $voluntario = $_POST['voluntario'];
    $lider = $_POST['lider'];
    $departamentoum = $_POST['departamentoum'];
    $departamentodois = $_POST['departamentodois'];
    $departamentotres = $_POST['departamentotres'];
    $status = $_POST['status'];


    $result = mysqli_query($conexao, "INSERT INTO membros(nome, sobrenome, nascimento, data, telefone, email, voluntario, lider, departamentoum, departamentodois,departamentotres, status) 
    VALUES ('$nome', '$sobrenome','$nascimento','$data', '$telefone', '$email', '$voluntario', '$lider', '$departamentoum', '$departamentodois', '$departamentotres', '$status')");

    header('Location: cadastroMembros.php');
}
}
?>
<!-- Adicione este script antes do fechamento da tag </body> -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Membros</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <script src="bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

</head>
<body>
<header>
    <div class="cabecalho" id="cabecalho">
    <?php include('cabecalhoAdm.php');?>
    </div>    

</header>

<form action="cadastroMembros.php" method="post">
<br><br>
<?php
    echo "<h1 id='BemVindo'>Cadastro de Membros</h1>";
?>
<a id="incluirCadastro" href="cadastroEvento.php" value="Novo Cadastro">Eventos</a>
<a id="incluirCadastro" href="cadastroMembrosAdm.php" value="Novo Cadastro">Membros</a>
<br><br><br>
<fieldset class="boxformularioRelatorio">
<form id="dataRelatorio" method="POST" action="relatorio_membros.php">
    <label for="data_inicio"><b>Exportar em planilha Membros da Lirio Matriz:</b></label>
    <input type="submit" value="Exportar" id="Exportar"/>
</form>
</fieldset>
<br>
<div class="dadoscliente">    
<div class="col-md-5">
    <label for="nome" class="form-label">*Nome:</label>
    <input type="text" name="nome" id="nome" class="form-control" required>
  </div><br>
  
  <div class="col-md-5">
    <label for="nome" class="form-label">*Sobrenome:</label>
    <input type="text" name="sobrenome" id="sobrenome" class="form-control" required>
  </div><br>

  <div class="col-md-5">
    <label for="nome" class="form-label">*Data Nascimento:</label>
    <input type="date" name="nascimento" id="nascimento" class="form-control" required>
  </div><br>

  <div class="col-md-5">
    <label for="nome" class="form-label">*Membro Desde:</label>
    <input type="date" name="data" id="data" class="form-control" >
  </div><br>

  <div class="col-2">
    <label for="telefone" class="form-label">*Telefone:</label>
    <input type="tel" class="form-control" name="telefone" id="telefone" placeholder="dd numero" required>
  </div><br>
  
  <div class="col-md-5">
    <label for="email" class="form-label">Email:</label>
    <input type="email"  name="email" id="email" class="form-control" >
  </div><br>

  <div class="col-md-5">
    <label for="inputState" class="form-label">*Voluntário:</label>
    <select id="voluntario" class="form-select" name="voluntario" required>
        <option value="">Selecione</option>
        <option value="sim">sim</option>
        <option value="não">não</option>
    </select>
</div>
<br>

<div class="col-md-5">
    <label for="inputState" class="form-label">*Lider:</label>
    <select id="lider" class="form-select" name="lider">
    <option value="">Selecione</option>
    <option value="não">Não</option>
    <option value="criativo">Criativo</option>
    <option value="homens">GC Homens</option>
    <option value="jovens">GC Jovens</option>
    <option value="mulheres">GCMulheres</option>
    <option value="kids">Kids</option>
    <option value="loja">Loja</option>
    <option value="louvor">Louvor</option>
    <option value="midias">Midias</option>
    <option value="recepcao">Recepção</option>
    <option value="staff">Staff</option>
    <option value="transito">Transito</option>
        
    </select>
</div>
<br>
  
<div class="col-md-5">
    <label for="inputState" class="form-label">Departamento1:</label>
    <select id="departamentoum" class="form-select" name="departamentoum">
    <option value="">Selecione</option>
    <option value="criativo">Criativo</option>
    <option value="kids">Kids</option>
    <option value="loja">Loja</option>
    <option value="louvor">Louvor</option>
    <option value="midias">Midias</option>
    <option value="recepcao">Recepção</option>
    <option value="staff">Staff</option>
    <option value="sonoplastia">Sonoplastia</option>
    <option value="transito">Transito</option>
    </select>
</div>
<br>
<div class="col-md-5">
    <label for="inputState" class="form-label">Departamento2:</label>
    <select id="departamentodois" class="form-select" name="departamentodois">
    <option value="">Selecione</option>
    <option value="criativo">Criativo</option>
    <option value="kids">Kids</option>
    <option value="loja">Loja</option>
    <option value="louvor">Louvor</option>
    <option value="midias">Midias</option>
    <option value="recepcao">Recepção</option>
    <option value="staff">Staff</option>
    <option value="sonoplastia">Sonoplastia</option>
    <option value="transito">Transito</option>
    </select>
</div>
<br>
<div class="col-md-5">
    <label for="inputState" class="form-label">Departamento3:</label>
    <select id="departamentotres" class="form-select" name="departamentotres">
    <option value="">Selecione</option>
    <option value="criativo">Criativo</option>
    <option value="kids">Kids</option>
    <option value="loja">Loja</option>
    <option value="louvor">Louvor</option>
    <option value="midias">Midias</option>
    <option value="recepcao">Recepção</option>
    <option value="staff">Staff</option>
    <option value="sonoplastia">Sonoplastia</option>
    <option value="transito">Transito</option>
    </select>
</div>
<br>
<div class="col-md-5">
    <label for="inputState" class="form-label">*Membro(a) Ativo:</label>
    <select id="status" class="form-select" name="status">
        <option value="">Selecione</option>
        <option value="ativo">Ativo</option>
        <option value="nãoAtivo">Não Ativo</option>
    </select>
</div>
<br>
   </label>
   <button type="submit" name="submitAdm" id="submitAdm" class="submitAdm">Enviar</button>
   
   </button>

</div>
</div>
</div>
</form>
<br>
<!-- Modal de confirmação -->
<div class="modal fade" id="confirmacaoModal" tabindex="-1" aria-labelledby="confirmacaoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmacaoModalLabel">Confirmação de Envio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Seu formulário foi enviado com sucesso! Entraremos em contato em breve.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"  action="cadastroMenbros.php">Fechar</button>
      </div>
    </div>
  </div>
</div>


</body>
</html>
