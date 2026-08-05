<?php

include_once('config.php');

// Verifica imagem cortada da sessão
$imagem = isset($_SESSION['imagem_cortada']) ? $_SESSION['imagem_cortada'] : '';

// Verifica login

include('registroslog.php');

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Membros</title>
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
      <?php include('cabecalhoMembros.php') ?>
      
    </nav>

    <div class="container">
        <!-- Cabeçalho -->
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <h1 class="h3 text-gray-800"><i class="fas fa-user-plus me-2"></i>Cadastro de Membros</h1>
            <div>
               
            </div>
        </div>
        
        <!-- Mensagem de Boas-Vindas -->
        <div class="alert alert-primary mb-4">
            
        </div>

        <!-- Formulário -->
        <div class="card card-form">
            <div class="card-header card-header-custom">
                <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Informações do Membro</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($imagem)): ?>
                <div class="row mb-4">
                    <div class="col-md-4 mx-auto">
                        <div class="profile-preview">
                            <h5 class="mb-3">Prévia do Perfil</h5>
                            <img src="<?php echo htmlspecialchars($imagem); ?>" alt="Imagem do usuário">
                            <div class="fw-bold">Nome</div>
                            <div class="text-muted">Sobrenome</div>
                            <div class="text-primary">email@exemplo.com</div>
                            <div class="text-dark">(00) 0000-0000</div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="salvar_membro.php" enctype="multipart/form-data" class="row g-3">
                    <div class="col-md-6">
                        <label for="nome" class="form-label required-field">Nome</label>
                        <input type="text" name="nome" id="nome" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="sobrenome" class="form-label required-field">Sobrenome</label>
                        <input type="text" name="sobrenome" id="sobrenome" class="form-control" required>
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
                               required>
                    </div>

                    <div class="col-md-4">
                        <label for="batizado" class="form-label required-field">Batizado</label>
                        <select id="batizado" class="form-select" name="batizado" required>
                            <option value="">Selecione</option>
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
                               required>
                    </div>

                    <div class="col-md-6">
                        <label for="telefone" class="form-label required-field">Telefone</label>
                        <input type="tel" class="form-control" name="telefone" id="telefone" placeholder="(00) 00000-0000" required>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="instagram" class="form-label">Instagram</label>
                        <input type="instagram" name="instagram" id="instagram" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="voluntario" class="form-label required-field">Voluntário</label>
                        <select id="voluntario" class="form-select" name="voluntario" required>
                            <option value="">Selecione</option>
                            <option value="sim">Sim</option>
                            <option value="não">Não</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="lider" class="form-label required-field">Líder</label>
                        <select id="lider" class="form-select" name="lider" required>
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
                            <option value="louvor">Louvor</div>
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
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="departamentoum[]" value="Criativo" id="deptCriativo">
                                <label class="form-check-label" for="deptCriativo">Criativo</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="departamentoum[]" value="Consagracao" id="deptConsagracao">
                                <label class="form-check-label" for="deptConsagracao">Consagração</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="departamentoum[]" value="Coral" id="deptCoral">
                                <label class="form-check-label" for="deptCoral">Coral</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="departamentoum[]" value="Danca" id="deptDanca">
                                <label class="form-check-label" for="deptDanca">Dança</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="departamentoum[]" value="Intercessao" id="deptIntercessao">
                                <label class="form-check-label" for="deptIntercessao">Intercessão</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="departamentoum[]" value="Kids" id="deptKids">
                                <label class="form-check-label" for="deptKids">Kids</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="departamentoum[]" value="Loja" id="deptLoja">
                                <label class="form-check-label" for="deptLoja">Loja</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="departamentoum[]" value="Louvor" id="deptLouvor">
                                <label class="form-check-label" for="deptLouvor">Louvor</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="departamentoum[]" value="Midias" id="deptMidias">
                                <label class="form-check-label" for="deptMidias">Mídias</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="departamentoum[]" value="Oficiais" id="deptOficiais">
                                <label class="form-check-label" for="deptOficiais">Oficiais</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="departamentoum[]" value="Recepcao" id="deptRecepcao">
                                <label class="form-check-label" for="deptRecepcao">Recepção</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="departamentoum[]" value="Staff" id="deptStaff">
                                <label class="form-check-label" for="deptStaff">Staff</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="departamentoum[]" value="Sala_voluntarios" id="deptSalaVoluntarios">
                                <label class="form-check-label" for="deptSalaVoluntarios">Sala Voluntários</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="departamentoum[]" value="Som" id="deptSom">
                                <label class="form-check-label" for="deptSom">Mesa de Som</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="departamentoum[]" value="Teatro" id="deptTeatro">
                                <label class="form-check-label" for="deptTeatro">Teatro</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="departamentoum[]" value="Visitas" id="deptVisitas">
                                <label class="form-check-label" for="deptVisitas">Visitas</label>
                            </div>
                        </div>
                        <small class="text-muted">* Selecione no máximo 3 departamentos</small>
                    </div>
                    
                    <input type="hidden" id="status" name="status" value="ativo">

                    <div class="col-md-6">
                        <label for="responsavel" class="form-label">Responsável</label>
                        <input type="text" name="responsavel" id="responsavel" class="form-control" placeholder="Nome completo do responsável">
                    </div>

                    <div class="col-md-6">
                        <label for="upload_image" class="form-label required-field">Foto de Perfil</label>
                        <input type="file" name="upload_image" id="upload_image" accept="image/*" class="form-control" required>
                        <input type="hidden" name="foto_crop" id="foto_crop">
                        <small class="text-muted">Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 5MB</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Prévia da Foto</label>
                        <div class="image-preview-container">
                            <img id="preview_cropped" src="" alt="Prévia da imagem" style="display: none;">
                            <div id="no-image-placeholder">
                                <i class="fas fa-image fa-2x mb-2"></i>
                                <p>Nenhuma imagem selecionada</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" name="submitAdm" id="submitAdm" class="btn btn-primary-custom">
                            <i class="fas fa-save me-2"></i>Salvar Cadastro
                        </button>
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
            document.getElementById('preview_cropped').style.display = 'block';
            
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