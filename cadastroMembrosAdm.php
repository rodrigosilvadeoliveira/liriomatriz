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
include('verifica_permissao.php');
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
<div class="navegacao">
   <?php include("navegacao.php")?>
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
    <label for="nascimento-text" class="form-label">*Data Nascimento:</label>
    <!-- Input type="date" oculto que será enviado ao servidor -->
    <input type="date" name="nascimento" id="nascimento-date" class="form-control d-none">
    
    <!-- Input type="text" visível para o usuário -->
    <input type="text" name="nascimento_text" id="nascimento-text" class="form-control" 
           placeholder="DD/MM/AAAA" 
           maxlength="10"
           oninput="formatarDataAuto(this, 'nascimento-date')"
           onkeydown="permitirApenasNumeros(event)"
           onblur="validarDataFinal(this, 'nascimento-date')"
           required>
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
    <label for="datas-text" class="form-label">*Membro desde:</label>
    <!-- Input type="date" oculto que será enviado ao servidor -->
    <input type="date" name="datas" id="datas-date" class="form-control d-none">
    
    <!-- Input type="text" visível para o usuário -->
    <input type="text" name="datas_text" id="datas-text" class="form-control" 
           placeholder="DD/MM/AAAA" 
           maxlength="10"
           oninput="formatarDataAuto(this, 'datas-date')"
           onkeydown="permitirApenasNumeros(event)"
           onblur="validarDataFinal(this, 'datas-date')"
           required>
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

  <div class="col-md-10">
  <label class="form-label">Voluntário em qual(is) departamento(s):</label>
  <div id="departamentos-checkboxes">
    <div><input type="checkbox" name="departamentoum[]" value="Criativo"> Criativo</div>
    <div><input type="checkbox" name="departamentoum[]" value="Consagracao"> Consagração</div>
    <div><input type="checkbox" name="departamentoum[]" value="Coral"> Coral</div>
    <div><input type="checkbox" name="departamentoum[]" value="Danca"> Dança</div>
    <div><input type="checkbox" name="departamentoum[]" value="Intercessao"> Intercessão</div>
    <div><input type="checkbox" name="departamentoum[]" value="Kids"> Kids</div>
    <div><input type="checkbox" name="departamentoum[]" value="Loja"> Loja</div>
    <div><input type="checkbox" name="departamentoum[]" value="Louvor"> Louvor</div>
    <div><input type="checkbox" name="departamentoum[]" value="Midias"> Midias</div>
    <div><input type="checkbox" name="departamentoum[]" value="Oficiais"> Oficiais</div>
    <div><input type="checkbox" name="departamentoum[]" value="Recepcao"> Recepção</div>
    <div><input type="checkbox" name="departamentoum[]" value="Staff"> Staff</div>
    <div><input type="checkbox" name="departamentoum[]" value="Sala_voluntarios"> Sala Voluntários</div>
    <div><input type="checkbox" name="departamentoum[]" value="Som"> Mesa de Som</div>
    <div><input type="checkbox" name="departamentoum[]" value="Teatro"> Teatro</div>
    <div><input type="checkbox" name="departamentoum[]" value="Visitas"> Visitas</div>
  </div>
</div>
<br>
    <input type="hidden" id="status" name="status" value="ativo">

  <div class="col-md-3">
    <label for="responsavel" class="form-label">Responsável:</label>
    <input type="text" name="responsavel" id="responsavel" class="form-control" placeholder="Nome completo do responsável">
  </div>

  <!-- Upload e crop da foto de perfil -->
  <div class="col-md-3">
    <label for="upload_image" class="form-label">Foto de Perfil:</label>
    <input type="file" name="upload_image" id="upload_image" accept="image/*" class="form-control" required>
    <input type="hidden" name="foto_crop" id="foto_crop">
    
  </div>

  <div class="col-md-3">
    <button type="submit" name="submitAdm" id="submitAdm" class="btn btn-primary">Enviar</button>
  </div>
  <div class="col-md-3">
  <label class="form-label">Prévia da Foto:</label>
  <div class="image-preview-container">
    <img id="preview_cropped" src="" alt="Prévia da imagem" >
    <div id="no-image-placeholder">
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
        <div class="btn-group me-2" role="group">
        <button type="button" id="cncmodal" class="btn btn-secondary" data-bs-dismiss="modal">
        Cancelar
      </button>
        <button type="button" id="crop_button" class="btn btn-primary">
        Confirmar
      </button>
        
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
<script>
  const checkboxes = document.querySelectorAll('input[name="departamentoum[]"]');

  checkboxes.forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
      const checked = document.querySelectorAll('input[name="departamentoum[]"]:checked');
      if (checked.length > 3) {
        this.checked = false;
        alert('Você pode selecionar no máximo 3 departamentos.');
      }
    });
  });
</script>
<script>
// Permite apenas a digitação de números
function permitirApenasNumeros(event) {
    // Permite: números, teclas de controle (backspace, delete, setas, tab)
    if (!/[0-9]|Backspace|Delete|ArrowLeft|ArrowRight|Tab/.test(event.key)) {
        event.preventDefault();
        return false;
    }
    return true;
}

// Formata automaticamente adicionando as barras
// Formata automaticamente adicionando as barras
function formatarDataAuto(input, dateFieldId) {
    let value = input.value.replace(/\D/g, ''); // Remove tudo que não for dígito
    
    // Insere as barras automaticamente
    if (value.length > 2) {
        value = value.substring(0, 2) + '/' + value.substring(2);
    }
    if (value.length > 5) {
        value = value.substring(0, 5) + '/' + value.substring(5);
    }
    
    // Limita o tamanho (DD/MM/AAAA = 10 caracteres)
    if (value.length > 10) {
        value = value.substring(0, 10);
    }
    
    input.value = value;
    
    // Validação enquanto digita
    if (value.length === 10) {
        validarData(input, dateFieldId);
    } else {
        input.classList.remove('is-invalid');
    }
}

// Valida a data completa
function validarData(input, dateFieldId) {
    const partes = input.value.split('/');
    if (partes.length !== 3 || partes[0].length !== 2 || partes[1].length !== 2 || partes[2].length !== 4) {
        input.classList.add('is-invalid');
        return false;
    }
    
    const dia = parseInt(partes[0], 10);
    const mes = parseInt(partes[1], 10);
    const ano = parseInt(partes[2], 10);
    
    // Validações básicas
    if (mes < 1 || mes > 12 || dia < 1 || dia > 31 || ano < 1900 || ano > new Date().getFullYear()) {
        input.classList.add('is-invalid');
        return false;
    }
    
    // Valida dias por mês
    const diasPorMes = [31, (ano % 4 === 0 && (ano % 100 !== 0 || ano % 400 === 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    if (dia > diasPorMes[mes - 1]) {
        input.classList.add('is-invalid');
        return false;
    }
    
    // Se válido, atualiza o campo date
    input.classList.remove('is-invalid');
    document.getElementById(dateFieldId).value = `${ano}-${mes.toString().padStart(2, '0')}-${dia.toString().padStart(2, '0')}`;
    return true;
}

// Validação final quando sai do campo
function validarDataFinal(input, dateFieldId) {
    if (input.value.length > 0 && input.value.length < 10) {
        input.classList.add('is-invalid');
    } else if (input.value.length === 10) {
        if (!validarData(input, dateFieldId)) {
            input.value = ''; // Limpa se for inválido
        }
    }
}
</script>

<style>
.is-invalid {
    border-color: #dc3545;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}
.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}
</style>
</fieldset>


</body>
</html>
