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
      
  } else {
      header('Location: perfil.php');
      exit();
  }
} else {
  header('Location: perfil.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Membro</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Cropper CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #6f42c1;
            --success-color: #1cc88a;
            --light-bg: #f8f9fc;
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 70px;
        }
        
        .navbar-custom {
            background-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .card-form {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        
        .card-header-custom {
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }
        
        .form-label {
            font-weight: 600;
            color: #4e555b;
            margin-bottom: 5px;
        }
        
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
        
        .image-preview-container {
            width: 100%;
            height: 200px;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-color: #f8f9fa;
            position: relative;
        }
        
        .image-preview-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        
        #no-image-placeholder {
            color: #6c757d;
            text-align: center;
            padding: 20px;
        }
        
        .departamentos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }
        
        .departamentos-grid .form-check {
            background-color: #f8f9fa;
            padding: 8px 12px;
            border-radius: 5px;
            border: 1px solid #e9ecef;
        }
        
        .btn-primary-custom {
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            padding: 10px 20px;
            font-weight: 600;
        }
        
        .btn-primary-custom:hover {
            background: linear-gradient(90deg, #3a5fce 0%, #5a32a9 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .profile-preview {
            width: 100%;
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 15px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            text-align: center;
        }
        
        .profile-preview img {
            width: 100px;
            height: 133px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }
        
        .modal-content {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .modal-header {
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }
        
        .modal-footer-cropper {
            display: flex;
            justify-content: space-between;
            padding: 1rem;
            border-top: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }
        
        .cropper-action-btn {
            display: flex;
            gap: 10px;
        }
        
        .current-photo {
            width: 100px;
            height: 133px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        @media (max-width: 768px) {
            .departamentos-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <?php include("navegacao.php") ?>
    </nav>

    <div class="container">
        <!-- Cabeçalho -->
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <h1 class="h3 text-gray-800"><i class="fas fa-user-edit me-2"></i>Editar Cadastro de Membro</h1>
            <div>
                <?php include("navegacao.php") ?>
            </div>
        </div>
        
        <!-- Mensagem de Boas-Vindas -->
        <div class="alert alert-primary mb-4">
            <i class="fas fa-user me-2"></i> Bem-vindo, <strong><?php echo $_SESSION['usuario']; ?></strong>
        </div>

        <!-- Formulário -->
        <div class="card card-form">
            <div class="card-header card-header-custom">
                <h5 class="card-title mb-0"><i class="fas fa-user-circle me-2"></i>Dados do Membro</h5>
            </div>
            <div class="card-body">
                <form class="row g-3" action="save_editarvol.php" method="POST" enctype="multipart/form-data">
                    <!-- Foto atual -->
                    <div class="col-md-12 mb-4">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <img class="current-photo" src="<?php echo $foto ?>" alt="Foto atual do membro">
                            </div>
                            <div>
                                <h6>Foto atual</h6>
                                <small  class="text-muted"><?php echo htmlspecialchars($foto); ?></small>
                                <input type="hidden" name="imagem_atual" value="<?php echo htmlspecialchars($foto); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="nome" class="form-label required-field">Nome</label>
                        <input type="text" name="nome" id="nome" class="form-control" value="<?php echo $nome?>" required>
                    </div>

                    <div class="col-md-6">
                        <label for="sobrenome" class="form-label required-field">Sobrenome</label>
                        <input type="text" name="sobrenome" id="sobrenome" class="form-control" value="<?php echo $sobrenome?>" required>
                    </div>

                    <div class="col-md-4">
                        <label for="nascimento-text" class="form-label required-field">Data de Nascimento</label>
                        <input type="date" name="nascimento" id="nascimento-date" class="form-control d-none">
                        <input type="text" name="nascimento_text" id="nascimento-text" class="form-control" 
                               placeholder="DD/MM/AAAA" 
                               maxlength="10"
                               oninput="formatarDataAuto(this, 'nascimento-date')"
                               onkeydown="permitirApenasNumeros(event)"
                               onblur="validarDataFinal(this, 'nascimento-date')" 
                               value="<?= htmlspecialchars($nascimento_front) ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label for="batizado" class="form-label required-field">Batizado</label>
                        <select id="batizado" class="form-select" name="batizado" required>
                            <option value="<?php echo $batizado?>" selected><?php echo ucfirst($batizado)?></option>
                            <option value="não">Não</option>
                            <option value="sim">Sim</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="datas-text" class="form-label required-field">Membro desde</label>
                        <input type="date" name="datas" id="datas-date" class="form-control d-none">
                        <input type="text" name="datas_text" id="datas-text" class="form-control" 
                               placeholder="DD/MM/AAAA" 
                               maxlength="10"
                               oninput="formatarDataAuto(this, 'datas-date')"
                               onkeydown="permitirApenasNumeros(event)"
                               onblur="validarDataFinal(this, 'datas-date')" 
                               value="<?= htmlspecialchars($datas_front) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label for="telefone" class="form-label required-field">Telefone</label>
                        <input type="tel" class="form-control" name="telefone" id="telefone" placeholder="(00) 00000-0000" value="<?php echo $telefone?>" required>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?php echo $email?>">
                    </div>

                    <div class="col-md-6">
                        <label for="voluntario" class="form-label required-field">Voluntário</label>
                        <select id="voluntario" class="form-select" name="voluntario" required>
                            <option value="<?php echo $voluntario?>" selected><?php echo ucfirst($voluntario)?></option>
                            <option value="sim">Sim</option>
                            <option value="não">Não</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="lider" class="form-label required-field">Líder</label>
                        <select id="lider" class="form-select" name="lider" required>
                            <option value="<?php echo $lider?>" selected>
                                <?php 
                                $liderLabels = [
                                    'não' => 'Não',
                                    'consagracao' => 'Consagração',
                                    'coral' => 'Coral',
                                    'criativo' => 'Criativo',
                                    'danca' => 'Dança',
                                    'gccasados' => 'GC Casados',
                                    'gcjovens' => 'GC Jovens',
                                    'intercessao' => 'Intercessão',
                                    'Kids' => 'Kids',
                                    'loja' => 'Loja',
                                    'louvor' => 'Louvor',
                                    'midias' => 'Mídias',
                                    'oficiais' => 'Oficiais',
                                    'recepcao' => 'Recepção',
                                    'salavoluntarios' => 'Sala Voluntários',
                                    'som' => 'Mesa de Som',
                                    'teatro' => 'Teatro',
                                    'transito' => 'Trânsito',
                                    'visitas' => 'Visitas'
                                ];
                                echo isset($liderLabels[$lider]) ? $liderLabels[$lider] : ucfirst($lider);
                                ?>
                            </option>
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

                    <div class="col-12">
                        <label class="form-label">Voluntário em qual(is) departamento(s):</label>
                        <div class="departamentos-grid">
                            <?php foreach ($departamentosOptions as $value => $label): ?>
                                <?php $checked = in_array($value, $departamentosArray) ? 'checked' : ''; ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="departamentoum[]" 
                                           value="<?= $value ?>" id="dept<?= $value ?>" <?= $checked ?>>
                                    <label class="form-check-label" for="dept<?= $value ?>"><?= $label ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <small class="text-muted">* Selecione no máximo 3 departamentos</small>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label required-field">Status do Membro</label>
                        <select id="status" class="form-select" name="status" required>
                            <option value="<?php echo $status?>" selected><?php echo ucfirst($status)?></option>
                            <option value="ativo">Ativo</option>
                            <option value="nãoAtivo">Não Ativo</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="responsavel" class="form-label">Responsável</label>
                        <input type="text" name="responsavel" id="responsavel" class="form-control" 
                               placeholder="Nome completo do responsável" value="<?php echo $responsavel?>">
                    </div>

                    <div class="col-md-6">
                        <label for="upload_image" class="form-label">Nova Foto de Perfil</label>
                        <input type="file" name="upload_image" id="upload_image" accept="image/*" class="form-control">
                        <input type="hidden" name="foto_crop" id="foto_crop">
                        <small class="text-muted">Deixe em branco para manter a foto atual. Formatos: JPG, PNG, GIF. Tamanho máximo: 5MB</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Prévia da Nova Foto</label>
                        <div type="hidden"class="image-preview-container">
                            <img id="preview_cropped" src="uploads/<?php echo $foto ?>" alt="Prévia da imagem">
                            <div id="no-image-placeholder" style="display: none;">
                                <i class="fas fa-image fa-2x mb-2"></i>
                                <p>Nenhuma nova imagem selecionada</p>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="id" value="<?php echo $id?>">

                    <div class="col-12 mt-4">
                        <button type="submit" name="submitAdm" id="submitAdm" class="btn btn-primary-custom">
                            <i class="fas fa-save me-2"></i>Salvar Alterações
                        </button>
                        <a href="perfil.php" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Cropper -->
    <div class="modal fade" id="modal_crop" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-crop-alt me-2"></i>Recortar Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <img id="image_crop" src="" alt="Imagem para recorte" style="max-width: 100%;">
                    </div>
                </div>
                <div class="modal-footer-cropper">
                    <div class="cropper-action-btn">
                        <button type="button" class="btn btn-outline-primary" onclick="cropper.rotate(-90)">
                            <i class="fas fa-undo me-1"></i> Girar Esq
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="cropper.rotate(90)">
                            <i class="fas fa-redo me-1"></i> Girar Dir
                        </button>
                    </div>
                    <div>
                        <button type="button" id="cncmodal" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </button>
                        <button type="button" id="crop_button" class="btn btn-primary">
                            <i class="fas fa-check me-1"></i> Confirmar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap & jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Cropper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    
    <script>
        // Configuração do Cropper
        let cropper;
        const modal = new bootstrap.Modal(document.getElementById('modal_crop'));

        document.getElementById('upload_image').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                // Verificar tamanho do arquivo (máximo 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('O arquivo é muito grande. Por favor, selecione uma imagem menor que 5MB.');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function (event) {
                    const image = document.getElementById('image_crop');
                    image.src = event.target.result;

                    image.onload = () => {
                        if (cropper) cropper.destroy();
                        
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
            document.getElementById('no-image-placeholder').style.display = 'none';
            
            const canvas = cropper.getCroppedCanvas({
                width: 600,
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
                const croppedImage = canvas.toDataURL('image/jpeg', 0.9);
                const preview = document.getElementById('preview_cropped');
                preview.src = croppedImage;
                preview.style.display = 'block';
                document.getElementById('foto_crop').value = croppedImage;
                modal.hide();
            } else {
                alert('Erro ao recortar a imagem. Tente novamente.');
            }
        });

        // Limitar a seleção de departamentos a 3
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

        // Funções para formatação de data
        function permitirApenasNumeros(event) {
            if (!/[0-9]|Backspace|Delete|ArrowLeft|ArrowRight|Tab/.test(event.key)) {
                event.preventDefault();
                return false;
            }
            return true;
        }

        function formatarDataAuto(input, dateFieldId) {
            let value = input.value.replace(/\D/g, '');
            
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2);
            }
            if (value.length > 5) {
                value = value.substring(0, 5) + '/' + value.substring(5);
            }
            
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            
            input.value = value;
            
            if (value.length === 10) {
                validarData(input, dateFieldId);
            } else {
                input.classList.remove('is-invalid');
            }
        }

        function validarData(input, dateFieldId) {
            const partes = input.value.split('/');
            if (partes.length !== 3 || partes[0].length !== 2 || partes[1].length !== 2 || partes[2].length !== 4) {
                input.classList.add('is-invalid');
                return false;
            }
            
            const dia = parseInt(partes[0], 10);
            const mes = parseInt(partes[1], 10);
            const ano = parseInt(partes[2], 10);
            
            if (mes < 1 || mes > 12 || dia < 1 || dia > 31 || ano < 1900 || ano > new Date().getFullYear()) {
                input.classList.add('is-invalid');
                return false;
            }
            
            const diasPorMes = [31, (ano % 4 === 0 && (ano % 100 !== 0 || ano % 400 === 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
            if (dia > diasPorMes[mes - 1]) {
                input.classList.add('is-invalid');
                return false;
            }
            
            input.classList.remove('is-invalid');
            document.getElementById(dateFieldId).value = `${ano}-${mes.toString().padStart(2, '0')}-${dia.toString().padStart(2, '0')}`;
            return true;
        }

        function validarDataFinal(input, dateFieldId) {
            if (input.value.length > 0 && input.value.length < 10) {
                input.classList.add('is-invalid');
            } else if (input.value.length === 10) {
                if (!validarData(input, dateFieldId)) {
                    input.value = '';
                }
            }
        }
        
        // Auto logout após 1 hora de inatividade
        const tempoLimite = 3600000;
        setTimeout(() => {
            window.location.href = "sistema.php?timeout=1"; 
        }, tempoLimite);
    </script>
</body>
</html>