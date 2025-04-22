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
            $batizado = $user_data['batizado'];
            $datas = $user_data['datas'];
            $telefone = $user_data['telefone'];
            $email = $user_data['email'];
            $voluntario = $user_data['voluntario'];
            $lider = $user_data['lider'];
            $departamentoum = $user_data['departamentoum'];
            $departamentodois = $user_data['departamentodois'];
            $departamentotres = $user_data['departamentotres'];
            $status = $user_data['status'];
            $foto = $user_data['foto'];

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
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <script src="bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/cropperjs/dist/cropper.min.css" />
    <link href="https://unpkg.com/cropperjs/dist/cropper.min.css" rel="stylesheet"/>
    <script src="https://unpkg.com/cropperjs/dist/cropper.min.js"></script>
    <script src="https://unpkg.com/cropperjs"></script>

    <title>Liro Matriz</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<br>
<br><br><br>
<?php
    echo "<h1 id='BemVindo'>Alterar Cadastro</h1>";
?>
<div class="produtos-container">
<a id="incluirCadastro" value="Novo Volutario" href="cadastroMembrosAdm.php">Novo Membro(a)</a>
<a id="incluirCadastro" value="Novo Volutario" href="consulta_membros.php">Consultar Membros</a>
<a id="incluirCadastro" href="cadastroEvento.php" value="Novo Cadastro">Eventos</a>
<a id="incluirCadastro" href="cadastroForm.php" value="Novo Cadastro">Inscrições</a>
</div>
<fieldset class="boxformularioEditMembrosAdm">
<form class="row g-3" action="saveEditMembros.php" method="POST" enctype="multipart/form-data">

      <h1>Alteração Cadastro</h1>
<!--       <label for="nova_foto">Alterar Foto de Perfil:</label>
<input type="file" id="nova_foto" name="nova_foto" accept="image/*" class="form-control">

Imagem recortada prévia -->
<img id="preview_foto" src="uploads/<?php echo $foto ?>" class="imagensevento mt-2 mb-2" />


<!-- Campo oculto com base64 do recorte -->
<input type="hidden" id="imagem_crop" name="imagem_crop">

<!-- Modal para recorte -->
<div class="modal fade" id="modalCrop" tabindex="-1" aria-labelledby="modalCropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Recortar Imagem</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <img id="imagemCropper" style="max-width: 100%;" />
      </div>
      <div class="modal-footer">
        <button type="button" id="btnCrop" class="btn btn-primary">OK</button>
      </div>
    </div>
  </div>
</div>

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
  
  <div class="col-md-3">
    <label for="inputState" class="form-label">*Batizado:</label>
    <select id="batizado" class="form-select" name="batizado">
    <option value="<?php echo $batizado?>"><?php echo $batizado?></option>
    <option value="não">Não</option>
    <option value="sim">Sim</option>
    </select>
</div>

  <div class="col-3">
    <label for="data" class="form-label">Membro desde:</label>
    <input type="date" class="form-control" name="datas" id="datas" placeholder="" value="<?php echo $datas?>">
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

  <div class="col-md-5">
    <label for="departamentoum" class="form-label">Departamento1:</label>
    <select id="departamentoum" class="form-select" name="departamentoum">
    <option value="<?php echo $departamentoum?>"><?php echo $departamentoum?></option>
    <option value="">Selecione</option> 
      <option value="Criativo">Criativo</option>
      <option value="Consagracao">Consagração</option>
        <option value="Coral">Coral</option>
        <option value="Criativo">Criativo</option>
        <option value="Danca">Dança</option>
        <option value="GC_Casados">GC Casados</option>
        <option value="GC_Homens">GC GC_Homens</option>
        <option value="GC_Jovens">GC Jovens</option>
        <option value="Intercessao">Intercessão</option>
        <option value="Kids">Kids</option>
        <option value="Loja">Loja</option>
        <option value="Louvor">Louvor</option>
        <option value="Midias">Midias</option>
        <option value="Oficiais">Oficias</option>
        <option value="Recepcao">Recepção</option>
        <option value="Staff">Staff</option>
        <option value="Sala_voluntarios">Sala Voluntarios</option>
        <option value="Som">Mesa de Som</option>
        <option value="Teatro">Teatro</option>
        <option value="Visitas">Visitas</option>
    </select>
</div>
<div class="col-md-5">
    <label for="departamentodois" class="form-label">Departamento2:</label>
    <select id="departamentodois" class="form-select" name="departamentodois">
    <option value="<?php echo $departamentodois?>"><?php echo $departamentodois?></option>
    <option value="">Selecione</option>
    <option value="Criativo">Criativo</option>
      <option value="Consagracao">Consagração</option>
        <option value="Coral">Coral</option>
        <option value="Criativo">Criativo</option>
        <option value="Danca">Dança</option>
        <option value="GC_Casados">GC Casados</option>
        <option value="GC_Homens">GC GC_Homens</option>
        <option value="GC_Jovens">GC Jovens</option>
        <option value="Intercessao">Intercessão</option>
        <option value="Kids">Kids</option>
        <option value="Loja">Loja</option>
        <option value="Louvor">Louvor</option>
        <option value="Midias">Midias</option>
        <option value="Oficiais">Oficias</option>
        <option value="Recepcao">Recepção</option>
        <option value="Staff">Staff</option>
        <option value="Sala_voluntarios">Sala Voluntarios</option>
        <option value="Som">Mesa de Som</option>
        <option value="Teatro">Teatro</option>
        <option value="Visitas">Visitas</option>
      </select>
</div>
<div class="col-md-5">
    <label for="departamentotres" class="form-label">Departamento3:</label>
    <select id="departamentotres" class="form-select" name="departamentotres">
    <option value="<?php echo $departamentotres?>"><?php echo $departamentotres?></option>
    <option value="">Selecione</option>
    <option value="Criativo">Criativo</option>
      <option value="Consagracao">Consagração</option>
        <option value="Coral">Coral</option>
        <option value="Criativo">Criativo</option>
        <option value="Danca">Dança</option>
        <option value="GC_Casados">GC Casados</option>
        <option value="GC_Homens">GC GC_Homens</option>
        <option value="GC_Jovens">GC Jovens</option>
        <option value="Intercessao">Intercessão</option>
        <option value="Kids">Kids</option>
        <option value="Loja">Loja</option>
        <option value="Louvor">Louvor</option>
        <option value="Midias">Midias</option>
        <option value="Oficiais">Oficias</option>
        <option value="Recepcao">Recepção</option>
        <option value="Staff">Staff</option>
        <option value="Sala_voluntarios">Sala Voluntarios</option>
        <option value="Som">Mesa de Som</option>
        <option value="Teatro">Teatro</option>
        <option value="Visitas">Visitas</option>
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
    <button type="submit" name="update" id="updateMenbros" class="btn btn-primary">Atualizar</button>
    <button href="consulta_membros.php" name="updateMembros" id="cancelarMembros" class="btn btn-primary">Cancelar</button>
  </div>
</form>
</fieldset>

<script>
  let cropper;
  const inputFoto = document.getElementById('nova_foto');
  const imagemCropper = document.getElementById('imagemCropper');
  const modal = new bootstrap.Modal(document.getElementById('modalCrop'));
  const previewFoto = document.getElementById('preview_foto');

  // Corrigido: cria o Cropper com segurança quando a imagem terminar de carregar
  imagemCropper.addEventListener('load', () => {
    if (cropper) cropper.destroy();
    cropper = new Cropper(imagemCropper, {
      aspectRatio: 3 / 4,
      viewMode: 1,
    });
  });

  inputFoto.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
      const url = URL.createObjectURL(file);
      imagemCropper.src = url;
      modal.show();
    }
  });

  document.getElementById('btnCrop').addEventListener('click', () => {
    if (cropper) {
      const canvas = cropper.getCroppedCanvas({ width: 300, height: 400 });
      if (canvas) {
        const base64 = canvas.toDataURL('image/jpeg');
        document.getElementById('imagem_crop').value = base64;
        previewFoto.src = base64;
        modal.hide();
      } else {
        alert('Erro ao recortar a imagem. Tente novamente.');
      }
    } else {
      alert('Imagem não carregada corretamente.');
    }
  });
</script>

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