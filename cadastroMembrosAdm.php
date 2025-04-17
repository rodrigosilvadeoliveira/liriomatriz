<?php if (!empty($imagem)): ?>
    <div style="
        width: 250px;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 15px;
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        text-align: center;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    ">
        <h3 style="margin: 0 0 10px; font-size: 16px;">Prévia do Perfil</h3>
        <img src="<?php echo htmlspecialchars($imagem); ?>" alt="Imagem do usuário" style="
            width: 100px;
            height: 133px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #aaa;
            margin-bottom: 10px;
        ">
        <div style="font-weight: bold;">Nome</div>
        <div style="color: #555;">Sobrenome</div>
        <div style="color: #0066cc;">email@exemplo.com</div>
        <div style="color: #333;">(00) 0000-0000</div>
    </div>
<?php endif; ?>
<?php
include('verificarLogin.php');
verificarLogin();
//session_start();
include_once('config.php');

// Verifica imagem cortada da sessão
$imagem = isset($_SESSION['imagem_cortada']) ? $_SESSION['imagem_cortada'] : '';

// Verifica login
if ((!isset($_SESSION['usuario']) == true) and ($_SESSION['senha']) == true) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
}
$logado = $_SESSION['usuario'];

?>

<!-- Adicione este script antes do fechamento da tag <script>
    // Tempo de inatividade em milissegundos (1 hora = 3600000 ms)
    const tempoLimite = 3600000;

    // Redireciona para logout após o tempo limite
    setTimeout(() => {
        window.location.href = "sistema.php?timeout=1"; 
    }, tempoLimite);
</script>

</body> -->


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
    <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

</head>
<body>
<header>
    <div class="cabecalho" id="cabecalho">
    <?php include('cabecalhoIgreja.php');?>
    </div>    

</header>


<br><br><br>
<?php
    echo "<h1 id='BemVindo'>Cadastro de Membros</h1>";
?>
<br>
<div class="produtos-container">
<a id="incluirCadastro" value="Novo Volutario" href="cadastroMembrosAdm.php">Novo Membro(a)</a>
<a id="incluirCadastro" value="Novo Volutario" href="consulta_membros.php">Consultar Membros</a>
<a id="incluirCadastro" value="Novo Volutario" href="consulta_membros_busca.php">Pesquisa Membro(a)</a>
<a id="incluirCadastro" href="cadastroForm.php" value="Novo Cadastro">Inscrições</a>
</div>
<br>

<fieldset class="boxformularioMembrosAdm">
<form method="POST" action="salvar_membro_adm.php" enctype="multipart/form-data" class="row g-3">
<div class="col-md-3">
    <label for="nome" class="form-label">*Nome:</label>
    <input type="text" name="nome" id="nome" class="form-control" required>
  </div>

  <div class="col-md-3">
    <label for="sobrenome" class="form-label">*Sobrenome:</label>
    <input type="text" name="sobrenome" id="sobrenome" class="form-control" required>
  </div>

  <div class="col-md-3">
    <label for="nascimento" class="form-label">*Data Nascimento:</label>
    <input type="date" name="nascimento" id="nascimento" class="form-control" required>
  </div>

  <div class="col-md-3">
    <label for="batizado" class="form-label">*Batizado:</label>
    <select id="batizado" class="form-select" name="batizado">
      <option value="">Selecione</option>
      <option value="não">Não</option>
      <option value="sim">Sim</option>
    </select>
  </div>

  <div class="col-md-3">
    <label for="datas" class="form-label">*Membro Desde:</label>
    <input type="date" name="datas" id="datas" class="form-control">
  </div>

  <div class="col-md-3">
    <label for="telefone" class="form-label">*Telefone:</label>
    <input type="tel" class="form-control" name="telefone" id="telefone" placeholder="dd numero" required>
  </div>

  <div class="col-md-3">
    <label for="email" class="form-label">Email:</label>
    <input type="email" name="email" id="email" class="form-control">
  </div>

  <div class="col-md-3">
    <label for="voluntario" class="form-label">*Voluntário:</label>
    <select id="voluntario" class="form-select" name="voluntario" required>
      <option value="">Selecione</option>
      <option value="sim">Sim</option>
      <option value="não">Não</option>
    </select>
  </div>

  <div class="col-md-3">
    <label for="lider" class="form-label">*Líder:</label>
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
      <option value="midias">Mídias</option>
      <option value="oficiais">Oficiais</option>
      <option value="recepcao">Recepção</option>
      <option value="salavoluntarios">Sala Voluntários</option>
      <option value="som">Mesa de Som</option>
      <option value="teatro">Teatro</option>
      <option value="transito">Trânsito</option>
      <option value="visitas">Visitas</option>
    </select>
  </div>

  <div class="col-md-3">
    <label for="departamentoum" class="form-label">Departamento 1:</label>
    <select id="departamentoum" class="form-select" name="departamentoum">
      <option value="">Selecione</option>
      <option value="Criativo">Criativo</option>
      <option value="Consagracao">Consagração</option>
        <option value="Coral">Coral</option>
        <option value="Criativo">Criativo</option>
        <option value="Danca">Dança</option>
        <option value="GC_Casados">GC Casados</option>
        <option value="GC_Homens">GC Homens</option>
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

  <div class="col-md-3">
    <label for="departamentodois" class="form-label">Departamento 2:</label>
    <select id="departamentodois" class="form-select" name="departamentodois">
      <option value="">Selecione</option>
      <option value="Criativo">Criativo</option>
      <option value="Consagracao">Consagração</option>
        <option value="Coral">Coral</option>
        <option value="Criativo">Criativo</option>
        <option value="Danca">Dança</option>
        <option value="GC_Casados">GC Casados</option>
        <option value="GC_Homens">GC Homens</option>
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

  <div class="col-md-3">
    <label for="departamentotres" class="form-label">Departamento 3:</label>
    <select id="departamentotres" class="form-select" name="departamentotres">
      <option value="">Selecione</option>
      <option value="Criativo">Criativo</option>
      <option value="Consagracao">Consagração</option>
        <option value="Coral">Coral</option>
        <option value="Criativo">Criativo</option>
        <option value="Danca">Dança</option>
        <option value="GC_Casados">GC Casados</option>
        <option value="GC_Homens">GC Homens</option>
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

  <div class="col-md-3">
    <label for="status" class="form-label">*Membro(a) Ativo:</label>
    <select id="status" class="form-select" name="status">
      <option value="">Selecione</option>
      <option value="ativo">Ativo</option>
      <option value="naoAtivo">Não Ativo</option>
    </select>
  </div>

  <div class="col-md-3">
    <label for="responsavel" class="form-label">Responsável:</label>
    <input type="text" name="responsavel" id="responsavel" class="form-control" placeholder="Nome completo do responsável">
  </div>

  <!-- Upload e crop da foto de perfil -->
  <div class="col-md-3">
    <label for="upload_image" class="form-label">Foto de Perfil:</label>
    <input type="file" name="upload_image" id="upload_image" accept="image/*" class="form-control">
    <input type="hidden" name="foto_crop" id="foto_crop">
    
  </div>

  <div class="col-12">
    <button type="submit" name="submitAdm" id="submitAdm" class="btn btn-primary">Enviar</button>
  </div>
  <div class="col-md-3">
  <label class="form-label">Prévia da Foto:</label>
  <div class="image-preview-container">
    <img id="preview_cropped" src="" alt="Prévia da imagem" 
         style="max-width: 100%; height: auto; display: none; border-radius: 4px; border: 1px solid #ddd;">
    <div id="no-image-placeholder" style="text-align: center; padding: 20px; border: 1px dashed #ccc; border-radius: 4px;">
      Nenhuma imagem selecionada
    </div>
  </div>
</div>
</form>

<!-- Modal de Cropper -->
<div class="modal fade" id="modal_crop" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Recortar Foto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="img-container">
          <img id="image_crop" src="" alt="Imagem para recorte">
        </div>
      </div>
      <div class="modal-footer-cropper">
        <div class="btn-group me-2" role="group">
          <button type="button" class="btn btn-outline-primary" onclick="cropper.rotate(-90)">
            <i class="bi bi-arrow-counterclockwise"></i> Girar Esq
          </button>
          <button type="button" class="btn btn-outline-primary" onclick="cropper.rotate(90)">
            <i class="bi bi-arrow-clockwise"></i> Girar Dir
          </button>
        </div>
        <div class="botaodomodal">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" id="crop_button" class="btn btn-primary">Confirmar Corte</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Scripts do Cropper -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
  let cropper;
  const modal = new bootstrap.Modal(document.getElementById('modal_crop'));

  document.getElementById('upload_image').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (event) {
        const image = document.getElementById('image_crop');
        image.src = event.target.result;

        image.onload = () => {
          if (cropper) cropper.destroy();
          
          // Configurações melhoradas do Cropper
          cropper = new Cropper(image, {
            aspectRatio: 3 / 4,
            viewMode: 1,
            autoCropArea: 0.8,
            responsive: true,
            movable: true,
            zoomable: true,
            rotatable: true,
            scalable: true,
            minContainerWidth: 300,
            minContainerHeight: 400,
            ready: function() {
              // Ajusta o zoom para mostrar a imagem inteira inicialmente
              cropper.crop();
            }
          });
          
          modal.show();
        };
      };
      reader.readAsDataURL(file);
    }
  });

  document.getElementById('crop_button').addEventListener('click', function () {
    // Tamanho maior para melhor qualidade
    // No evento de clique do crop_button, adicione:
document.getElementById('no-image-placeholder').style.display = 'none';
document.getElementById('preview_cropped').style.display = 'block';
    const canvas = cropper.getCroppedCanvas({
      width: 600,  // Dobrando o tamanho para melhor qualidade
      height: 800,
      minWidth: 300,
      minHeight: 400,
      maxWidth: 1200,
      maxHeight: 1600,
      fillColor: '#fff',
      imageSmoothingEnabled: true,
      imageSmoothingQuality: 'high'
    });
    
    if (canvas) {
      const croppedImage = canvas.toDataURL('image/jpeg', 0.9); // 90% de qualidade
      const preview = document.getElementById('preview_cropped');
      preview.src = croppedImage;
      preview.style.display = 'block';
      document.getElementById('foto_crop').value = croppedImage;
      modal.hide();
    } else {
      alert('Erro ao recortar a imagem. Tente novamente.');
    }
  });
</script>

</fieldset>


</body>
</html>
