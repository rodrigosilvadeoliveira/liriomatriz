<?php
include_once('config.php');

// Verifica imagem cortada da sessão
$imagem = isset($_SESSION['imagem_cortada']) ? $_SESSION['imagem_cortada'] : '';

// Verifica login
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Prestador de Serviços</title>
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
        
        .servicos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }
        
        .servicos-grid .form-check {
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
            .servicos-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <!-- <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
      <?php include('cabecalhoServicos.php') ?>
    </nav> -->

    <div class="container">
        <!-- Cabeçalho -->
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <h1 class="h3 text-gray-800"><i class="fas fa-briefcase me-2"></i>Cadastro de Prestador de Serviços</h1>
            <div>
               
            </div>
        </div>
        
        <!-- Mensagem de Boas-Vindas -->
        <div class="alert alert-primary mb-4">
            <i class="fas fa-info-circle me-2"></i>Preencha os dados abaixo para cadastrar seu serviço em nossa plataforma.
        </div>

        <!-- Formulário -->
        <div class="card card-form">
            <div class="card-header card-header-custom">
                <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Informações do Prestador</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($imagem)): ?>
                <div class="row mb-4">
                    <div class="col-md-4 mx-auto">
                        <div class="profile-preview">
                            <h5 class="mb-3">Prévia do Perfil</h5>
                            <img src="<?php echo htmlspecialchars($imagem); ?>" alt="Imagem do prestador">
                            <div class="fw-bold">Nome do Prestador</div>
                            <div class="text-muted">Serviço Prestado</div>
                            <div class="text-primary">email@exemplo.com</div>
                            <div class="text-dark">(00) 0000-0000</div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="salvar_prestador.php" enctype="multipart/form-data" class="row g-3">
                    <div class="col-md-6">
                        <label for="nome" class="form-label required-field">Nome Completo</label>
                        <input type="text" name="nome" id="nome" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="nome_empresa" class="form-label">Nome da Empresa (se houver)</label>
                        <input type="text" name="nome_empresa" id="nome_empresa" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="cpf_cnpj" class="form-label required-field">CPF/CNPJ</label>
                        <input type="text" name="cpf_cnpj" id="cpf_cnpj" class="form-control" required>
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
                        <label for="instagram" class="form-label">Link instagram</label>
                        <input type="instagram" name="instagram" id="instagram" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="cidade" class="form-label required-field">Cidade</label>
                        <input type="text" name="cidade" id="cidade" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="estado" class="form-label required-field">Estado</label>
                        <select id="estado" class="form-select" name="estado" required>
                            <option value="">Selecione</option>
                            <option value="AC">Acre</option>
                            <option value="AL">Alagoas</option>
                            <option value="AP">Amapá</option>
                            <option value="AM">Amazonas</option>
                            <option value="BA">Bahia</option>
                            <option value="CE">Ceará</option>
                            <option value="DF">Distrito Federal</option>
                            <option value="ES">Espírito Santo</option>
                            <option value="GO">Goiás</option>
                            <option value="MA">Maranhão</option>
                            <option value="MT">Mato Grosso</option>
                            <option value="MS">Mato Grosso do Sul</option>
                            <option value="MG">Minas Gerais</option>
                            <option value="PA">Pará</option>
                            <option value="PB">Paraíba</option>
                            <option value="PR">Paraná</option>
                            <option value="PE">Pernambuco</option>
                            <option value="PI">Piauí</option>
                            <option value="RJ">Rio de Janeiro</option>
                            <option value="RN">Rio Grande do Norte</option>
                            <option value="RS">Rio Grande do Sul</option>
                            <option value="RO">Rondônia</option>
                            <option value="RR">Roraima</option>
                            <option value="SC">Santa Catarina</option>
                            <option value="SP">São Paulo</option>
                            <option value="SE">Sergipe</option>
                            <option value="TO">Tocantins</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="experiencia" class="form-label">Anos de Experiência</label>
                        <input type="number" name="experiencia" id="experiencia" class="form-control" min="0" max="50">
                    </div>

                    <div class="col-md-6">
                        <label for="valor_medio" class="form-label">Valor Médio do Serviço (R$)</label>
                        <input type="text" name="valor_medio" id="valor_medio" class="form-control" placeholder="Ex: 150,00">
                    </div>

                    <div class="col-12">
                        <label class="form-label required-field">Tipo de Serviço Prestado:</label>
                        <div class="servicos-grid">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="servicos[]" value="Eletricista" id="servEletricista">
                                <label class="form-check-label" for="servEletricista">Eletricista</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="servicos[]" value="Encanador" id="servEncanador">
                                <label class="form-check-label" for="servEncanador">Encanador</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="servicos[]" value="Pintor" id="servPintor">
                                <label class="form-check-label" for="servPintor">Pintor</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="servicos[]" value="Pedreiro" id="servPedreiro">
                                <label class="form-check-label" for="servPedreiro">Pedreiro</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="servicos[]" value="Marceneiro" id="servMarceneiro">
                                <label class="form-check-label" for="servMarceneiro">Marceneiro</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="servicos[]" value="Confeiteiro" id="servConfeiteiro">
                                <label class="form-check-label" for="servConfeiteiro">Confeiteiro</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="servicos[]" value="Costureira" id="servCostureira">
                                <label class="form-check-label" for="servCostureira">Costureira</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="servicos[]" value="Motorista" id="servMotorista">
                                <label class="form-check-label" for="servMotorista">Motorista</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="servicos[]" value="Diarista" id="servDiarista">
                                <label class="form-check-label" for="servDiarista">Diarista</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="servicos[]" value="Jardineiro" id="servJardineiro">
                                <label class="form-check-label" for="servJardineiro">Jardineiro</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="servicos[]" value="Técnico Informática" id="servTecnicoInfo">
                                <label class="form-check-label" for="servTecnicoInfo">Técnico Informática</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="servicos[]" value="Designer Gráfico" id="servDesigner">
                                <label class="form-check-label" for="servDesigner">Designer Gráfico</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="servicos[]" value="Fotógrafo" id="servFotografo">
                                <label class="form-check-label" for="servFotografo">Fotógrafo</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="servicos[]" value="Personal Trainer" id="servPersonal">
                                <label class="form-check-label" for="servPersonal">Personal Trainer</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="servicos[]" value="Babá" id="servBaba">
                                <label class="form-check-label" for="servBaba">Babá</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="servicos[]" value="Outros" id="servOutros">
                                <label class="form-check-label" for="servOutros">Outros</label>
                            </div>
                        </div>
                        <small class="text-muted">* Selecione os serviços que você presta</small>
                    </div>

                    <div class="col-12">
                        <label for="descricao" class="form-label required-field">Descrição do Serviço</label>
                        <textarea name="descricao" id="descricao" class="form-control" rows="4" placeholder="Descreva detalhadamente os serviços que você oferece..." required></textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="disponibilidade" class="form-label required-field">Disponibilidade</label>
                        <select id="disponibilidade" class="form-select" name="disponibilidade" required>
                            <option value="">Selecione</option>
                            <option value="Segunda a Sexta">Segunda a Sexta</option>
                            <option value="Finais de Semana">Finais de Semana</option>
                            <option value="Todos os Dias">Todos os Dias</option>
                            <option value="Plantão">Plantão</option>
                            <option value="Horário Comercial">Horário Comercial</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="raio_atendimento" class="form-label">Raio de Atendimento (km)</label>
                        <input type="number" name="raio_atendimento" id="raio_atendimento" class="form-control" min="0" max="100" placeholder="Ex: 20">
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
                        <button type="submit" name="submitPrestador" id="submitPrestador" class="btn btn-primary-custom">
                            <i class="fas fa-save me-2"></i>Cadastrar Serviço
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

        // Formatação do campo de valor
        document.getElementById('valor_medio').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = (value / 100).toFixed(2) + '';
            value = value.replace(".", ",");
            value = value.replace(/(\d)(\d{3})(\d{3}),/g, "$1.$2.$3,");
            value = value.replace(/(\d)(\d{3}),/g, "$1.$2,");
            e.target.value = value;
        });

        // Formatação do CPF/CNPJ
        document.getElementById('cpf_cnpj').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length <= 11) {
                // Formatação para CPF
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            } else {
                // Formatação para CNPJ
                value = value.replace(/(\d{2})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1/$2');
                value = value.replace(/(\d{4})(\d{1,2})$/, '$1-$2');
            }
            
            e.target.value = value;
        });

        // Formatação do telefone
        document.getElementById('telefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length <= 10) {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{4})(\d)/, '$1-$2');
            } else {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            
            e.target.value = value;
        });

        // Auto logout após 1 hora de inatividade
        const tempoLimite = 3600000;
        setTimeout(() => {
            window.location.href = "sistema.php?timeout=1"; 
        }, tempoLimite);
    </script>
</body>
</html>