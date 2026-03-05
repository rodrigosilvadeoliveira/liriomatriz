<?php
date_default_timezone_set('America/Sao_Paulo');
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');
include_once('config.php');
include_once('config_language.php');

// Verificar se foi passado um ID pela URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: perfil.php');
    exit();
}

$id_voluntario = $_GET['id'];

// Verifica login
if ((!isset($_SESSION['usuario']) == true) and ($_SESSION['senha']) == true) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
}
$logado = $_SESSION['usuario'];

// Consultar dados do voluntário no banco de dados
$sql = "SELECT * FROM musicos WHERE cadastroadm_id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_voluntario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: perfil.php?erro=1');
    exit();
}

 $voluntario= $result->fetch_assoc();

// Verifica imagem cortada da sessão ou usa a existente do banco
$imagem = isset($_SESSION['imagem_cortada']) ? $_SESSION['imagem_cortada'] : (isset($voluntario['foto']) ? $voluntario['foto'] : '');
include('registroslog.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição de Voluntário</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Cropper CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">

    <style>
        /* ... (seus estilos CSS permanecem os mesmos) ... */

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
            <h1 class="h3 text-gray-800"><i class="fas fa-user-edit me-2"></i>Edição de Foto</h1>
            
        </div>

        <!-- Mensagem de Boas-Vindas -->
        <div class="alert alert-primary mb-4">
            <i class="fas fa-user me-2"></i> <?php echo __('bem_vindo') ?> <strong><?php echo $logado; ?></strong>
        </div>

        <!-- Formulário -->
        <div class="card card-form">
            <div class="card-header card-header-custom">
                <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i><?php echo __('alterar_foto') ?></h5>
            </div>
            <div class="card-body">
                <?php if (!empty($imagem)): ?>
                    <div class="row mb-4">
                        <div class="col-md-4 mx-auto">
                            <div class="profile-preview">
                                <h5 class="mb-3"><?php echo __('previa_foto') ?></h5>
                                <img src="<?php echo htmlspecialchars($imagem); ?>" alt="Imagem do usuário">
                                <div class="fw-bold"><?php echo htmlspecialchars($voluntario['nome']); ?></div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="atualizar_foto.php" enctype="multipart/form-data"
                    class="row g-3">
                    <!-- Campo oculto para ID do voluntário -->
                    <input type="hidden" name="cadastroadm_id" value="<?php echo $id_voluntario; ?>">

                    <div class="col-md-6">
                        <label for="upload_image" class="form-label">Alterar Foto de Perfil</label>
                        <input type="file" name="upload_image" id="upload_image" accept="image/*" class="form-control">
                        <input type="hidden" name="foto_crop" id="foto_crop" value="">
                        <small class="text-muted">Deixe em branco para manter a foto atual</small>
                    </div>

                    <!-- Campo hidden para foto atual -->
                    <input type="hidden" name="foto_atual"
                        value="<?php echo htmlspecialchars($voluntario['foto'] ?? ''); ?>">

                    <div class="col-md-6">
                        <label class="form-label"><?php echo __('previa_foto') ?></label>
                        <div class="image-preview-container">
                            <?php if (!empty($voluntario['foto'])): ?>
                                <img id="preview_cropped" src="<?php echo htmlspecialchars($voluntario['foto']); ?>"
                                    alt="Prévia da imagem">
                                <div id="no-image-placeholder" style="display: none;">
                                    <i class="fas fa-image fa-2x mb-2"></i>
                                    <p>Nenhuma imagem selecionada</p>
                                </div>
                            <?php else: ?>
                                <img id="preview_cropped" src="" alt="Prévia da imagem" style="display: none;">
                                <div id="no-image-placeholder">
                                    <i class="fas fa-image fa-2x mb-2"></i>
                                    <p>Nenhuma imagem selecionada</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" name="submitAdm" id="submitAdm" class="btn btn-primary-custom">
                            <i class="fas fa-save me-2"></i><?php echo __('alterar_foto') ?>
                        </button>
                        <a href="consulta_voluntariado.php" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i><?php echo __('cancelar') ?>
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
                        <button type="button" class="btn btn-outline-primary" id="rotate-left">
                            <i class="fas fa-undo me-1"></i> Girar Esq
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="rotate-right">
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

        // Event listener para o input de arquivo
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

                    // Limpar cropper anterior se existir
                    if (cropper) {
                        cropper.destroy();
                    }

                    // Mostrar o modal
                    modal.show();

                    // Inicializar o cropper após o modal ser mostrado completamente
                    setTimeout(function () {
                        cropper = new Cropper(image, {
                            aspectRatio: 3 / 4,
                            viewMode: 1,
                            autoCropArea: 0.8,
                            responsive: true,
                            movable: true,
                            zoomable: true,
                            rotatable: true,
                            scalable: true,
                            ready: function () {
                                cropper.crop();
                            }
                        });
                    }, 300);
                };
                reader.readAsDataURL(file);
            }
        });

        // Botões de rotação
        document.getElementById('rotate-left').addEventListener('click', function () {
            if (cropper) {
                cropper.rotate(-90);
            }
        });

        document.getElementById('rotate-right').addEventListener('click', function () {
            if (cropper) {
                cropper.rotate(90);
            }
        });

        // Botão de confirmar recorte
        // No evento de clique do botão de confirmar recorte
document.getElementById('crop_button').addEventListener('click', function () {
    if (cropper) {
        // Obter canvas recortado
        const canvas = cropper.getCroppedCanvas({
            width: 300,
            height: 400,
            fillColor: '#fff',
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });

        if (canvas) {
            // Converter para data URL (usar JPEG para melhor compatibilidade)
            const croppedImage = canvas.toDataURL('image/jpeg', 0.9);
            
            // Atualizar preview
            const preview = document.getElementById('preview_cropped');
            preview.src = croppedImage;
            preview.style.display = 'block';
            
            // Esconder placeholder
            document.getElementById('no-image-placeholder').style.display = 'none';
            
            // IMPORTANTE: Atualizar o campo hidden
            document.getElementById('foto_crop').value = croppedImage;
            
            // DEBUG
            console.log("Imagem cortada salva no campo hidden");
            
            // Fechar modal
            modal.hide();
        }
    }
});
        // Limpar cropper quando o modal for fechado
        document.getElementById('modal_crop').addEventListener('hidden.bs.modal', function () {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });

        // Auto logout após 1 hora de inatividade
        const tempoLimite = 3600000;
        setTimeout(() => {
            window.location.href = "sistema.php?timeout=1";
        }, tempoLimite);
    </script>
</body>

</html>