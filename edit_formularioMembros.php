<?php include("cabecalhoIgreja.php")?>
<?php
include('verificarLogin.php');
verificarLogin();

include_once('config.php');

if(!empty($_GET['id']))
{
    $id = $_GET['id'];
    $sqlSelect = "SELECT * FROM membros WHERE id=$id";
    $result = $conexao->query($sqlSelect);
    if($result->num_rows > 0)
    {
        while($user_data = mysqli_fetch_assoc($result))
        {
            $nome = $user_data['nome'];
            $sobrenome = $user_data['sobrenome'];
            $nascimento = $user_data['nascimento'];
            $data = $user_data['data'];
            $telefone = $user_data['telefone'];
            $email = $user_data['email'];
            $voluntario = $user_data['voluntario'];
            $lider = $user_data['lider'];
            $departamentoum = $user_data['departamentoum'];
            $departamentodois = $user_data['departamentodois'];
            $departamentotres = $user_data['departamentotres'];
            $status = $user_data['status'];
        }
    }
    else
    {
        header('Location: consulta_membros.php');
    }
}
else
{
    header('Location: consulta_membros.php');
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

<fieldset class="boxformularioAdm">
    <form class="row g-3" action="saveEditMembros.php" method="POST">
      <h1>Alteração Cadastro</h1>
    <div class="col-md-5">
    <label for="nome" class="form-label">Nome</label>
    <input type="text" name="nome" id="nome" class="form-control" value="<?php echo $nome?>">
  </div>
  <div class="col-md-5">
    <label for="sobrenome" class="form-label">Sobrenome</label>
    <input type="text" name="sobrenome" id="sobrenome" class="form-control" value="<?php echo $sobrenome?>">
  </div>
  <div class="col-md-5">
    <label for="nascimento" class="form-label">Nascimento</label>
    <input type="date"  name="nascimento" id="nascimento" class="form-control" value="<?php echo $nascimento?>">
  </div>
  
  <div class="col-3">
    <label for="data" class="form-label">Data Inicio</label>
    <input type="date" class="form-control" name="data" id="data" placeholder="" value="<?php echo $data?>">
  </div>
  
  <div class="col-3">
    <label for="telefone" class="form-label">Telefone</label>
    <input type="tel" class="form-control" name="telefone" id="telefone" placeholder="dd numero" value="<?php echo $telefone?>">
  </div>
  
  <div class="col-md-5">
    <label for="email" class="form-label">Email</label>
    <input type="email"  name="email" id="email" class="form-control" value="<?php echo $email?>">
  </div>
  

  <div class="col-md-5">
    <label for="voluntario" class="form-label">*Voluntário:</label>
    <select id="voluntario" class="form-select" name="voluntario" required>
    <option value="<?php echo $voluntario?>"><?php echo $voluntario?></option>    
    <option value="sim">sim</option>
        <option value="não">não</option>
    </select>
</div>

 

  <div class="col-md-5">
    <label for="inputState" class="form-label">*Lider:</label>
    <select id="lider" class="form-select" name="lider">
    <option value="<?php echo $lider?>"><?php echo $lider?></option>
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

  <div class="col-md-5">
    <label for="departamentoum" class="form-label">Departamento1:</label>
    <select id="departamentoum" class="form-select" name="departamentoum">
    <option value="<?php echo $departamentoum?>"><?php echo $departamentoum?></option>
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
<div class="col-md-5">
    <label for="departamentodois" class="form-label">Departamento2:</label>
    <select id="departamentodois" class="form-select" name="departamentodois">
    <option value="<?php echo $departamentodois?>"><?php echo $departamentodois?></option>
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
<div class="col-md-5">
    <label for="departamentotres" class="form-label">Departamento3:</label>
    <select id="departamentotres" class="form-select" name="departamentotres">
    <option value="<?php echo $departamentotres?>"><?php echo $departamentotres?></option>
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

  <div class="col-md-5">
    <label for="status" class="form-label">*Membro(a) Ativo:</label>
    <select id="status" class="form-select" name="status">
        <option value="<?php echo $status?>"><?php echo $status?></option>
        <option value="ativo">Ativo</option>
        <option value="nãoAtivo">Não Ativo</option>
    </select>
</div>
  
  <div class="col-3">
  <input type="hidden" name="id" value="<?php echo $id?>">
    <button type="submit" name="update" id="update" class="btn btn-primary">Atualizar</button>
    <button href="consulta_membros.php" name="update" id="cancelar" class="btn btn-primary">Cancelar</button>
  </div>
</form>
</fieldset>

</body>
</html>