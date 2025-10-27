<?php
date_default_timezone_set('America/Sao_Paulo');
include('verificarLogin.php');
verificarLogin();
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

        h3 a {
            color: #4e73df;
            cursor: pointer;
        }

        h3 a:hover {
            text-decoration: underline;
        }

        h3 i {
            transition: transform 0.3s ease;
        }

        h3 a[aria-expanded="true"] i {
            transform: rotate(180deg);
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

        .-field::after {
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
        <?php include("navegacao.php") ?>
    </nav>

    <div class="container">
        <!-- Cabeçalho -->
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <h1 class="h3 text-gray-800"><i class="fas fa-user-plus me-2"></i>Cadastro de Voluntario</h5>
                <div>
                    <?php include("navegacao.php") ?>
                </div>
        </div>

        <!-- Mensagem de Boas-Vindas -->
        <div class="alert alert-primary mb-4">
            <i class="fas fa-user me-2"></i> Bem-vindo, <strong><?php echo $logado; ?></strong>
        </div>

        <!-- Formulário -->
        <div class="card card-form">
            <div class="card-header card-header-custom">
                <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Informações do Voluntário(a)</h5>
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

                <form method="POST" action="salvar_voluntariado_escala.php" enctype="multipart/form-data"
                    class="row g-3">
                    <div class="col-md-6">
                        <label for="nome" class="form-label -field">Nome</label>
                        <input type="text" name="nome" id="nome" class="form-control">
                    </div>

                    <h5>
                        <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#musicos"
                            role="button" aria-expanded="false" aria-controls="musicos">Voluntários Músicos <i
                                class="fas fa-chevron-down ms-2"></i>
                        </a>
                    </h5>
                    <div class="collapse" id="musicos">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="bateria" class="form-label -field">Bateria</label>
                                <input type="text" name="bateria" id="bateria" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label for="violao" class="form-label -field">Violão</label>
                                <input type="text" name="violao" id="violao" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="teclado" class="form-label -field">Teclado</label>
                                <input type="text" name="teclado" id="teclado" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="baixo" class="form-label -field">Baixo</label>
                                <input type="text" name="baixo" id="baixo" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="ministro" class="form-label -field">Ministro</label>
                                <input type="text" name="ministro" id="ministro" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="vocal1" class="form-label -field">Vocal1</label>
                                <input type="text" name="vocal1" id="vocal1" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="vocal2" class="form-label -field">Vocal2</label>
                                <input type="text" name="vocal2" id="vocal2" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="vocal3" class="form-label -field">Vocal3</label>
                                <input type="text" name="vocal3" id="vocal3" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="talckback" class="form-label -field">Talckback</label>
                                <input type="text" name="talckback" id="talckback" class="form-control">
                            </div>
                        </div>
                    </div>
                    <h5>
                        <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#som" role="button"
                            aria-expanded="false" aria-controls="som">
                            Voluntários Som <i class="fas fa-chevron-down ms-2"></i>
                        </a>
                    </h5>
                    <div class="collapse" id="som">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="igreja" class="form-label -field">Igreja</label>
                                <input type="text" name="igreja" id="igreja" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="live" class="form-label -field">Live</label>
                                <input type="text" name="live" id="live" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="somkids" class="form-label -field">Som Kids</label>
                                <input type="text" name="somkids" id="somkids" class="form-control">
                            </div>
                        </div>
                    </div>
                    <h5>
                        <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#midias"
                            role="button" aria-expanded="false" aria-controls="som">Voluntários Midias <i
                                class="fas fa-chevron-down ms-2"></i>
                        </a>
                    </h5>
                    <div class="collapse" id="midias">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="ct" class="form-label -field">CT</label>
                                <input type="text" name="ct" id="ct" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="c1" class="form-label -field">C1</label>
                                <input type="text" name="c1" id="c1" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="c2" class="form-label -field">C2</label>
                                <input type="text" name="c2" id="c2" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="lt" class="form-label -field">LT</label>
                                <input type="text" name="lt" id="lt" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="lz" class="form-label -field">LZ</label>
                                <input type="text" name="lz" id="lz" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="ph" class="form-label -field">PH</label>
                                <input type="text" name="ph" id="ph" class="form-control">
                            </div>
                        </div>
                    </div>
                    <h5>
                        <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#dancas"
                            role="button" aria-expanded="false" aria-controls="som">
                            Voluntários dança <i class="fas fa-chevron-down ms-2"></i>
                        </a>
                    </h5>
                    <div class="collapse" id="dancas">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="danca" class="form-label -field">Dança</label>
                                <input type="text" name="danca" id="danca" class="form-control">
                            </div>
                        </div>
                    </div>
                    <h5>
                        <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#criativo"
                            role="button" aria-expanded="false" aria-controls="som">
                            Voluntários criativo <i class="fas fa-chevron-down ms-2"></i>
                        </a>
                    </h5>
                    <div class="collapse" id="criativo">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="real_time" class="form-label -field">Real Time</label>
                                <input type="text" name="real_time" id="real_time" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="real_time_kids" class="form-label -field">Real Time Kids</label>
                                <input type="text" name="real_time_kids" id="real_time_kids" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="recap" class="form-label -field">Recap</label>
                                <input type="text" name="recap" id="recap" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label for="real_time_treinamento" class="form-label -field">Real Time Treinamento
                                </label>
                                <input type="text" name="real_time_treinamento" id="real_time_treinamento"
                                    class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="recap_treinamento" class="form-label -field">Recap Treinamento</label>
                                <input type="text" name="recap_treinamento" id="recap_treinamento" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="real_time_adolescentes" class="form-label -field">Real Time
                                    Adolescentes</label>
                                <input type="text" name="real_time_adolescentes" id="real_time_adolescentes"
                                    class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="real_time_homens" class="form-label -field">Real Time Homens</label>
                                <input type="text" name="real_time_homens" id="real_time_homens" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="real_time_mulheres" class="form-label -field">Real Time Mulheres</label>
                                <input type="text" name="real_time_mulheres" id="real_time_mulheres"
                                    class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="real_time_jovens" class="form-label -field">Real Time Jovens</label>
                                <input type="text" name="real_time_jovens" id="real_time_jovens" class="form-control">
                            </div>
                            </div>
                        </div>
                        <h5>
                            <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#staff"
                                role="button" aria-expanded="false" aria-controls="som">
                                Voluntários Staff <i class="fas fa-chevron-down ms-2"></i>
                            </a>
                        </h5>
                        <div class="collapse" id="staff">
                            <div class="col-md-6">
                                <label for="staff1" class="form-label -field">Staff 1</label>
                                <input type="text" name="staff1" id="staff1" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="staff2" class="form-label -field">Staff 2</label>
                                <input type="text" name="staff2" id="staff2" class="form-control">
                            </div>
                        </div>
                
                    <h5>
                            <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#kids"
                                role="button" aria-expanded="false" aria-controls="som">
                                Voluntários Kids <i class="fas fa-chevron-down ms-2"></i>
                            </a>
                        </h5>
                        <div class="collapse" id="kids">
                            <div class="col-md-6">
                                <label for="prof" class="form-label -field">Prof</label>
                                <input type="text" name="prof" id="prof" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="apoio" class="form-label -field">Apoio</label>
                                <input type="text" name="apoio" id="apoio" class="form-control">
                            </div>
                        </div>
                    <div class="col-md-6">
                        <label for="upload_image" class="form-label -field">Foto de Perfil</label>
                        <input type="file" name="upload_image" id="upload_image" accept="image/*" class="form-control">
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
                            ready: function () {
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
        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
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