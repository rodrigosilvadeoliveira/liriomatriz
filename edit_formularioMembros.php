<?php include("cabecalhoIgreja.php")?>
<?php
include('verificarLogin.php');
verificarLogin();

include_once('config.php');

if(!empty($_GET['id'])) {
  $id = $_GET['id'];
  
  // Prevenção contra SQL Injection (use prepared statements)
  $sqlSelect = "SELECT * FROM membros WHERE id = ?";
  $stmt = $conexao->prepare($sqlSelect);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if($result->num_rows > 0) {
      $user_data = $result->fetch_assoc();
      
      // Dados básicos
      $id = $user_data['id'];
      $nome = $user_data['nome'];
      $sobrenome = $user_data['sobrenome'];
      $telefone = $user_data['telefone'];
      $email = $user_data['email'];
      $batizado = $user_data['batizado'];
      $voluntario = $user_data['voluntario'];
      $lider = $user_data['lider'];
      $status = $user_data['status'];
      $responsavel = $user_data['responsavel'];
      $foto = $user_data['foto'];
      
      // ===== TRATAMENTO DE DATAS =====
      // Função para converter YYYY-MM-DD → DD-MM-YYYY
      function formatarDataParaFront($data) {
          if(empty($data) || $data == '0000-00-00') return '';
          return date('d-m-Y', strtotime($data));
      }
      
      // Convertendo datas para exibição
      $nascimento_front = formatarDataParaFront($user_data['nascimento']);
      
      $datas_front = formatarDataParaFront($user_data['datas']);
      
      // ===== TRATAMENTO DE DEPARTAMENTOS =====
      $departamentosCadastrados = $user_data['departamentos'];
      $departamentosArray = !empty($departamentosCadastrados) ? explode(',', $departamentosCadastrados) : [];
      
      $departamentosOptions = [
          'Criativo' => 'Criativo',
          'Consagracao' => 'Consagração',
          'Coral' => 'Coral',
          'Danca' => 'Dança',
          'Intercessao' => 'Intercessão',
          'Kids' => 'Kids',
          'Loja' => 'Loja',
          'Louvor' => 'Louvor',
          'Midias' => 'Midias',
          'Oficiais' => 'Oficiais',
          'Recepcao' => 'Recepção',
          'Staff' => 'Staff',
          'Sala_voluntarios' => 'Sala Voluntários',
          'Som' => 'Mesa de Som',
          'Teatro' => 'Teatro',
          'Visitas' => 'Visitas'
      ];
      
      // ===== DADOS PARA O FORMULÁRIO =====
      // (Agora você pode usar as variáveis no seu HTML)
      
  } else {
      header('Location: consulta_Membros_busca.php');
      exit();
  }
} else {
  header('Location: consulta_Membros_busca.php');
  exit();
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
<input type="hidden" name="foto" id="foto" value="<?php echo $foto?>">


      <div class="col-md-5">
    <label for="nome" class="form-label">Nome</label>
    <input type="text" name="nome" id="nome" class="form-control" value="<?php echo $nome?>">
  </div>
  <div class="col-md-5">
    <label for="sobrenome" class="form-label">Sobrenome</label>
    <input type="text" name="sobrenome" id="sobrenome" class="form-control" value="<?php echo $sobrenome?>">
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
           value="<?= htmlspecialchars($nascimento_front) ?>">
</div>
  
  <div class="col-md-3">
    <label for="inputState" class="form-label">*Batizado:</label>
    <select id="batizado" class="form-select" name="batizado">
    <option value="<?php echo $batizado?>"><?php echo $batizado?></option>
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
           value="<?= htmlspecialchars($datas_front) ?>" >
</div>
  
  <div class="col-3">
    <label for="telefone" class="form-label">Telefone</label>
    <input type="tel" class="form-control" name="telefone" id="telefone" placeholder="dd numero" value="<?php echo $telefone?>" required>
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

<div class="col-md-10">
  <label class="form-label">Voluntário em qual(is) departamento(s):</label>
  <div id="departamentos-checkboxes">
  <?php foreach ($departamentosOptions as $value => $label): ?>
            <?php $checked = in_array($value, $departamentosArray) ? 'checked' : ''; ?>
            <div>
                <input type="checkbox" name="departamentoum[]" value="<?= $value ?>" <?= $checked ?>>
                <?= $label ?>
            </div>
        <?php endforeach; ?>
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