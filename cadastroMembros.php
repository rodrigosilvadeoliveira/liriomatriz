<?php
if(isset($_POST['submitAdm']))
{
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $nascimento = $_POST['nascimento'];
    $batizado = $_POST['batizado'];
    $datas = $_POST['datas'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $voluntario = $_POST['voluntario'];
    $lider = $_POST['lider'];
    $departamentoum = $_POST['departamentoum'];
    $departamentodois = $_POST['departamentodois'];
    $departamentotres = $_POST['departamentotres'];
    $status = $_POST['status'];


    $result = mysqli_query($conexao, "INSERT INTO membros(nome, sobrenome, nascimento, datas, telefone, email, voluntario, lider, departamentoum, departamentodois,departamentotres, status) 
    VALUES ('$nome', '$sobrenome','$nascimento','$datas', '$telefone', '$email', '$voluntario', '$lider', '$departamentoum', '$departamentodois', '$departamentotres', '$status')");

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
    <?php include('cabecalhoMembros.php');?>
    </div>    

</header>


<br><br><br>
<?php
    echo "<h1 id='BemVindo'>Cadastro de Membros</h1>";
?>

<fieldset class="boxformularioMembrosAdm">
<form class="row g-3" action="cadastroMembros.php" method="post">
    
<h1>Incluir Cadastro</h1>
<div class="col-md-3">
    <label for="nome" class="form-label">*Nome:</label>
    <input type="text" name="nome" id="nome" class="form-control" required>
  </div>
  
  <div class="col-md-3">
    <label for="nome" class="form-label">*Sobrenome:</label>
    <input type="text" name="sobrenome" id="sobrenome" class="form-control" required>
  </div>

  <div class="col-md-3">
    <label for="nome" class="form-label">*Data Nascimento:</label>
    <input type="date" name="nascimento" id="nascimento" class="form-control" required>
  </div>

  <div class="col-md-3">
    <label for="inputState" class="form-label">*Batizado:</label>
    <select id="batizado" class="form-select" name="batizado">
    <option value="">Selecione</option>
    <option value="não">Não</option>
    <option value="sim">Sim</option>
    </select>
</div>

  <div class="col-md-3">
    <label for="nome" class="form-label">*Membro Desde:</label>
    <input type="date" name="data" id="data" class="form-control" >
  </div>
<br>
  <div class="col-md-3">
    <label for="telefone" class="form-label">*Telefone:</label>
    <input type="tel" class="form-control" name="telefone" id="telefone" placeholder="dd numero" required>
  </div>
  
  <div class="col-md-3">
    <label for="email" class="form-label">Email:</label>
    <input type="email"  name="email" id="email" class="form-control" >
  </div>

  <div class="col-md-3">
    <label for="inputState" class="form-label">*Voluntário:</label>
    <select id="voluntario" class="form-select" name="voluntario" required>
        <option value="">Selecione</option>
        <option value="sim">sim</option>
        <option value="não">não</option>
    </select>
</div>


<div class="col-md-3">
    <label for="inputState" class="form-label">*Lider:</label>
    <select id="lider" class="form-select" name="lider">
    <option value="">Selecione</option>
    <option value="não">Não</option>
    <option value="consagracao">Consagração</option>
        <option value="coral">Coral</option>
        <option value="criativo">Criativo</option>
        <option value="danca">Dança</option>
        <option value="gccasados">GC Casados</option>
        <option value="gcjovens">GC Jovens</option>
        <option value="intercessao">Intercessão</option>
        <option value="Kids">Kids</option>
        <option value="loja">Loja</option>
        <option value="louvor">Louvor</option>
        <option value="midias">Midias</option>
        <option value="oficiais">Oficias</option>
        <option value="recepcao">Recepção</option>
        <option value="salavoluntarios">Sala Voluntarios</option>
        <option value="som">Mesa de Som</option>
        <option value="teatro">Teatro</option>
        <option value="visitas">Visitas</option>
        
    </select>
</div>

  
<div class="col-md-3">
    <label for="inputState" class="form-label">Departamento1:</label>
    <select id="departamentoum" class="form-select" name="departamentoum">
    <option value="">Selecione</option>
    <option value="consagracao">Consagração</option>
        <option value="coral">Coral</option>
        <option value="criativo">Criativo</option>
        <option value="danca">Dança</option>
        <option value="gccasados">GC Casados</option>
        <option value="gcjovens">GC Jovens</option>
        <option value="intercessao">Intercessão</option>
        <option value="Kids">Kids</option>
        <option value="loja">Loja</option>
        <option value="louvor">Louvor</option>
        <option value="midias">Midias</option>
        <option value="oficiais">Oficias</option>
        <option value="recepcao">Recepção</option>
        <option value="salavoluntarios">Sala Voluntarios</option>
        <option value="som">Mesa de Som</option>
        <option value="teatro">Teatro</option>
        <option value="visitas">Visitas</option>
    </select>
</div>

<div class="col-md-3">
    <label for="inputState" class="form-label">Departamento2:</label>
    <select id="departamentodois" class="form-select" name="departamentodois">
    <option value="">Selecione</option>
    <option value="consagracao">Consagração</option>
        <option value="coral">Coral</option>
        <option value="criativo">Criativo</option>
        <option value="danca">Dança</option>
        <option value="gccasados">GC Casados</option>
        <option value="gcjovens">GC Jovens</option>
        <option value="intercessao">Intercessão</option>
        <option value="Kids">Kids</option>
        <option value="loja">Loja</option>
        <option value="louvor">Louvor</option>
        <option value="midias">Midias</option>
        <option value="oficiais">Oficias</option>
        <option value="recepcao">Recepção</option>
        <option value="salavoluntarios">Sala Voluntarios</option>
        <option value="som">Mesa de Som</option>
        <option value="teatro">Teatro</option>
        <option value="visitas">Visitas</option>
    </select>
</div>

<div class="col-md-3">
    <label for="inputState" class="form-label">Departamento3:</label>
    <select id="departamentotres" class="form-select" name="departamentotres">
    <option value="">Selecione</option>
    <option value="consagracao">Consagração</option>
        <option value="coral">Coral</option>
        <option value="criativo">Criativo</option>
        <option value="danca">Dança</option>
        <option value="gccasados">GC Casados</option>
        <option value="gcjovens">GC Jovens</option>
        <option value="intercessao">Intercessão</option>
        <option value="Kids">Kids</option>
        <option value="loja">Loja</option>
        <option value="louvor">Louvor</option>
        <option value="midias">Midias</option>
        <option value="oficiais">Oficias</option>
        <option value="recepcao">Recepção</option>
        <option value="salavoluntarios">Sala Voluntarios</option>
        <option value="som">Mesa de Som</option>
        <option value="teatro">Teatro</option>
        <option value="visitas">Visitas</option>
    </select>
</div>
<br>
<div class="col-md-3">
    <label for="inputState" class="form-label">*Membro(a) Ativo:</label>
    <select id="status" class="form-select" name="status">
        <option value="">Selecione</option>
        <option value="ativo">Ativo</option>
        <option value="nãoAtivo">Não Ativo</option>
    </select>
</div>

</label>
   <button type="submit" name="submitAdm" id="submitAdm" class="submitAdm">Enviar</button>
   
   </button>


</form>
</fieldset>


</body>
</html>
