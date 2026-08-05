<?php
date_default_timezone_set('America/Sao_Paulo');
include('verificarLogin.php');
verificarLogin();

// Exibir erros (apenas para dev)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('config.php'); // espera $conexao (mysqli)

// 1) Buscar dados do membro (para preencher o formulário e pegar a foto original)
if (!empty($_GET['id'])) {
    $id_get = intval($_GET['id']);
    $sqlSelect = "SELECT * FROM membros WHERE id = ?";
    $stmt = $conexao->prepare($sqlSelect);
    $stmt->bind_param("i", $id_get);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        // Variáveis usadas no HTML abaixo
        $id = $user_data['id'];
        $nome = $user_data['nome'];
        // foto do membro (pode ser só o nome do arquivo em uploads/ ou um path relativo)
        $foto = $user_data['foto'];
    } else {
        header('Location: consulta_Membros_busca.php');
        exit();
    }
    $stmt->close();
} else {
    header('Location: consulta_Membros_busca.php');
    exit();
}

// Helper: monta o caminho completo do arquivo de origem do membro
function caminho_origem_membro($imagem_atual) {
    // Se já vier com ./ ou / assume caminho relativo/absoluto
    if (strpos($imagem_atual, './') === 0 || strpos($imagem_atual, '/') === 0) {
        // remove "./" do começo se houver e monta path absoluto
        $trim = ltrim($imagem_atual, './');
        return __DIR__ . '/' . $trim;
    }
    // caso padrão: arquivo está em uploads/<nome>
    return __DIR__ . '/uploads/' . $imagem_atual;
}

// 2) Processar o POST (envio do formulário)
if (isset($_POST['submitAdm'])) {

    // Coletar dados do formulário
    $id_membro     = intval($_POST['id'] ?? 0);
    $nome_form     = $_POST['nome'] ?? '';
    $bateria       = $_POST['bateria'] ?? '';
    $violao        = $_POST['violao'] ?? '';
    $teclado       = $_POST['teclado'] ?? '';
    $baixo         = $_POST['baixo'] ?? '';
    $ministro      = $_POST['ministro'] ?? '';
    $vocal1        = $_POST['vocal1'] ?? '';
    $vocal2        = $_POST['vocal2'] ?? '';
    $vocal3        = $_POST['vocal3'] ?? '';
    $talckback     = $_POST['talckback'] ?? '';
    $igreja        = $_POST['igreja'] ?? '';
    $live          = $_POST['live'] ?? '';
    $somkids       = $_POST['somkids'] ?? '';
    $ct            = $_POST['ct'] ?? '';
    $c1            = $_POST['c1'] ?? '';
    $c2            = $_POST['c2'] ?? '';
    $lt            = $_POST['lt'] ?? '';
    $lz            = $_POST['lz'] ?? '';
    $ph            = $_POST['ph'] ?? '';
    $danca         = $_POST['danca'] ?? '';
    $realtime      = $_POST['real_time'] ?? '';
    $realtimekids  = $_POST['real_time_kids'] ?? '';
    $recap         = $_POST['recap'] ?? '';
    // imagem_atual vem do campo hidden no formulário (valor que veio da tabela membros)
    $imagem_atual  = $_POST['imagem_atual'] ?? $foto; // $foto do SELECT acima

    // Pasta destino onde vamos salvar PNGs
    $pasta_destino = __DIR__ . '/img/fotoescala/';
    if (!file_exists($pasta_destino)) {
        mkdir($pasta_destino, 0755, true);
    }

    $foto_para_salvar = $imagem_atual; // valor final que irá para a coluna foto da tabela musicos

    // 2a) Se o usuário recortou e enviou nova imagem (base64), gravamos direto em PNG
    if (!empty($_POST['foto_crop'])) {
        $foto_crop = $_POST['foto_crop'];
        $parts = explode(',', $foto_crop);
        if (count($parts) === 2) {
            $imagem_base64 = base64_decode($parts[1]);
            $nome_arquivo = 'musico_' . $id_membro . '_' . time() . '.png';
            $caminho_completo = $pasta_destino . $nome_arquivo;
            $save_ok = file_put_contents($caminho_completo, $imagem_base64);
            if ($save_ok === false) {
                // erro ao gravar
                $foto_para_salvar = $imagem_atual; // mantém a antiga
                error_log("Erro ao salvar imagem base64 em $caminho_completo");
            } else {
                // sucesso
                $foto_para_salvar = './img/fotoescala/' . $nome_arquivo;
            }
        } else {
            // formato inesperado
            $foto_para_salvar = $imagem_atual;
        }
    } else {
        // 2b) NÃO houve nova imagem: pegar a imagem que está na tabela membros (campo imagem_atual)
        // e criar uma cópia convertida para PNG em img/fotoescala/
        $src_full = caminho_origem_membro($imagem_atual);
        if (file_exists($src_full) && is_readable($src_full)) {
            $nome_arquivo = 'musico_' . $id_membro . '_' . time() . '.png';
            $dest_full = $pasta_destino . $nome_arquivo;

            // Tenta converter com GD (mais confiável)
            $converted = false;
            // tenta detectar mime
            if (function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $src_full);
                finfo_close($finfo);
            } else {
                $mime = mime_content_type($src_full) ?? '';
            }

            // Tenta funções específicas primeiro
            if ($mime === 'image/jpeg' && function_exists('imagecreatefromjpeg')) {
                $img = @imagecreatefromjpeg($src_full);
            } elseif ($mime === 'image/png' && function_exists('imagecreatefrompng')) {
                $img = @imagecreatefrompng($src_full);
            } elseif ($mime === 'image/gif' && function_exists('imagecreatefromgif')) {
                $img = @imagecreatefromgif($src_full);
            } else {
                // fallback genérico
                if (function_exists('imagecreatefromstring')) {
                    $bytes = @file_get_contents($src_full);
                    $img = ($bytes !== false) ? @imagecreatefromstring($bytes) : false;
                } else {
                    $img = false;
                }
            }

            if (!empty($img) && $img !== false) {
                // grava PNG
                if (imagepng($img, $dest_full)) {
                    imagedestroy($img);
                    $foto_para_salvar = './img/fotoescala/' . $nome_arquivo;
                    $converted = true;
                } else {
                    imagedestroy($img);
                    $converted = false;
                    error_log("Falha ao gravar PNG em $dest_full");
                }
            } else {
                // Tentativa de conversão falhou (GD pode não estar ativo ou arquivo inválido)
                // NÃO sobrescrevemos a foto; mantemos a imagem atual (para não quebrar)
                $foto_para_salvar = $imagem_atual;
                error_log("Falha ao criar resource de imagem para $src_full. Verifique GD/imagick e o arquivo.");
            }
        } else {
            // não existe arquivo de origem: manter imagem atual (valor do DB membros)
            $foto_para_salvar = $imagem_atual;
            error_log("Arquivo de origem não encontrado: $src_full");
        }
    }

    // 3) Inserir ou atualizar na tabela musicos
    // Verifica se já existe registro para esse id_membro
    $sqlCheck = "SELECT id FROM membros WHERE id_membro = ? LIMIT 1";
    $stmtCheck = $conexao->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $id_membro);
    $stmtCheck->execute();
    $resCheck = $stmtCheck->get_result();

    if ($resCheck->num_rows > 0) {
        // existe -> UPDATE
        $sqlUpdate = "UPDATE musicos SET 
            nome = ?, bateria = ?, violao = ?, teclado = ?, baixo = ?, ministro = ?,
            vocal1 = ?, vocal2 = ?, vocal3 = ?, talckback = ?, igreja = ?, live = ?, somkids = ?,
            ct = ?, c1 = ?, c2 = ?, lt = ?, lz = ?, ph = ?, danca = ?,
            real_time = ?, real_time_kids = ?, recap = ?, foto = ?
            WHERE id_membro = ?";

        $stmt = $conexao->prepare($sqlUpdate);
        if (!$stmt) {
            die("Erro prepare UPDATE: " . $conexao->error);
        }

        // 24 strings + 1 int (id_membro)
        $types = str_repeat('s', 24) . 'i';
        $stmt->bind_param(
            $types,
            $nome_form, $bateria, $violao, $teclado, $baixo, $ministro,
            $vocal1, $vocal2, $vocal3, $talckback, $igreja, $live, $somkids,
            $ct, $c1, $c2, $lt, $lz, $ph, $danca,
            $realtime, $realtimekids, $recap, $foto_para_salvar,
            $id_membro
        );

        if ($stmt->execute()) {
            echo "<script>alert('Cadastro atualizado com sucesso!'); window.location.href='consultaMusicos.php';</script>";
            exit();
        } else {
            echo "Erro ao atualizar: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // não existe -> INSERT (cria novo registro em musicos)
        $sqlInsert = "INSERT INTO musicos (
            id_membro, nome, foto, bateria, violao, teclado, baixo, ministro, vocal1, vocal2, vocal3, talckback,
            igreja, live, somkids, ct, c1, c2, lt, lz, ph, danca, real_time, real_time_kids, recap
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conexao->prepare($sqlInsert);
        if (!$stmt) {
            die("Erro prepare INSERT: " . $conexao->error);
        }

        // types: i + 24 strings
        $types = 'i' . str_repeat('s', 24);
        $stmt->bind_param(
            $types,
            $id_membro, $nome_form, $foto_para_salvar, $bateria, $violao, $teclado, $baixo, $ministro,
            $vocal1, $vocal2, $vocal3, $talckback, $igreja, $live, $somkids, $ct, $c1, $c2, $lt, $lz, $ph, $danca,
            $realtime, $realtimekids, $recap
        );

        if ($stmt->execute()) {
            echo "<script>alert('Músico cadastrado com sucesso!'); window.location.href='consultaMusicos.php';</script>";
            exit();
        } else {
            echo "Erro ao cadastrar: " . $stmt->error;
        }
        $stmt->close();
    }

    $stmtCheck->close();
    $conexao->close();
}
include('registroslog.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Músico</title>
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
        
        .current-photo {
            width: 100px;
            height: 133px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
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
        
        .section-header {
            background-color: #e9ecef;
            padding: 10px 15px;
            border-radius: 5px;
            margin: 20px 0 15px 0;
            font-weight: bold;
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
            <h1 class="h3 text-gray-800"><i class="fas fa-music me-2"></i>Cadastrar Músico</h1>
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
            <div class="card-header card-header-custom"><h5 class="card-title mb-0"><i class="fas fa-user-circle me-2"></i>Dados do Músico</h5></div>
            <div class="card-body">
                <!-- formulário -->
                <form class="row g-3" action="" method="POST" enctype="multipart/form-data">
                    <div class="col-md-12 mb-4">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <?php
                                // Preparar src para exibir a foto atual no formulário
                                $display_src = '';
                                if (!empty($foto)) {
                                    if (strpos($foto, './') === 0 || strpos($foto, '/') === 0) {
                                        $display_src = $foto;
                                    } else {
                                        $display_src = 'uploads/' . $foto;
                                    }
                                } else {
                                    $display_src = 'images/no-photo.png';
                                }
                                ?>
                                <img class="current-photo" src="<?php echo htmlspecialchars($display_src); ?>" alt="Foto atual do membro">
                            </div>
                            <div>
                                <h6>Foto atual</h6>
                                <small class="text-muted"><?php echo htmlspecialchars($foto); ?></small>
                                <input type="hidden" name="imagem_atual" value="<?php echo htmlspecialchars($foto); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="nome" class="form-label required-field">Nome</label>
                        <input type="text" name="nome" id="nome" class="form-control" value="<?php echo $nome?>" required readonly>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="id" class="form-label required-field">ID do Membro</label>
                        <input type="text" name="id" id="id" class="form-control" value="<?php echo $id?>" required readonly>
                    </div>

                    <div class="section-header col-12">Voluntários Músicos</div>

                    <div class="col-md-6">
                        <label for="bateria" class="form-label">Bateria</label>
                        <input type="text" name="bateria" id="bateria" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="violao" class="form-label">Violão</label>
                        <input type="text" name="violao" id="violao" class="form-control">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="teclado" class="form-label">Teclado</label>
                        <input type="text" name="teclado" id="teclado" class="form-control">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="baixo" class="form-label">Baixo</label>
                        <input type="text" name="baixo" id="baixo" class="form-control">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="ministro" class="form-label">Ministro</label>
                        <input type="text" name="ministro" id="ministro" class="form-control">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="vocal1" class="form-label">Vocal 1</label>
                        <input type="text" name="vocal1" id="vocal1" class="form-control">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="vocal2" class="form-label">Vocal 2</label>
                        <input type="text" name="vocal2" id="vocal2" class="form-control">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="vocal3" class="form-label">Vocal 3</label>
                        <input type="text" name="vocal3" id="vocal3" class="form-control">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="talckback" class="form-label">Talkback</label>
                        <input type="text" name="talckback" id="talckback" class="form-control">
                    </div>

                    <div class="section-header col-12">Voluntários Som</div>

                    <div class="col-md-6">
                        <label for="igreja" class="form-label">Igreja</label>
                        <input type="text" name="igreja" id="igreja" class="form-control">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="live" class="form-label">Live</label>
                        <input type="text" name="live" id="live" class="form-control">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="somkids" class="form-label">Som Kids</label>
                        <input type="text" name="somkids" id="somkids" class="form-control">
                    </div>

                    <div class="section-header col-12">Voluntários Mídias</div>

                    <div class="col-md-6">
                        <label for="ct" class="form-label">CT</label>
                        <input type="text" name="ct" id="ct" class="form-control">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="c1" class="form-label">C1</label>
                        <input type="text" name="c1" id="c1" class="form-control">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="c2" class="form-label">C2</label>
                        <input type="text" name="c2" id="c2" class="form-control">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="lt" class="form-label">LT</label>
                        <input type="text" name="lt" id="lt" class="form-control">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="lz" class="form-label">LZ</label>
                        <input type="text" name="lz" id="lz" class="form-control">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="ph" class="form-label">PH</label>
                        <input type="text" name="ph" id="ph" class="form-control">
                    </div>

                    <div class="section-header col-12">Voluntários Dança</div>

                    <div class="col-md-6">
                        <label for="danca" class="form-label">Dança</label>
                        <input type="text" name="danca" id="danca" class="form-control">
                    </div>

                    <div class="section-header col-12">Voluntários Criativo</div>

                    <div class="col-md-6">
                        <label for="real_time" class="form-label">Real Time</label>
                        <input type="text" name="real_time" id="real_time" class="form-control">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="real_time_kids" class="form-label">Real Time Kids</label>
                        <input type="text" name="real_time_kids" id="real_time_kids" class="form-control">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="recap" class="form-label">Recap</label>
                        <input type="text" name="recap" id="recap" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="upload_image" class="form-label">Nova Foto de Perfil</label>
                        <input type="file" name="upload_image" id="upload_image" accept="image/*" class="form-control">
                        <input type="hidden" name="foto_crop" id="foto_crop">
                        <small class="text-muted">Deixe em branco para manter a foto atual. Formatos: JPG, PNG, GIF. Tamanho máximo: 5MB</small>
                    </div>
<div class="col-md-6">
                        <label class="form-label">Prévia da Nova Foto</label>
                        <div class="image-preview-container">
                            <img id="preview_cropped" src="<?php echo htmlspecialchars($display_src); ?>" alt="Prévia da imagem">
                            <div id="no-image-placeholder" style="display: none;">
                                <i class="fas fa-image fa-2x mb-2"></i>
                                <p>Nenhuma nova imagem selecionada</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" name="submitAdm" id="submitAdm" class="btn btn-primary-custom"><i class="fas fa-save me-2"></i>Cadastrar Músico</button>
                        <a href="consulta_Membros_busca.php" class="btn btn-secondary"><i class="fas fa-times me-2"></i>Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal cropper (mantive igual) -->
    <div class="modal fade" id="modal_crop" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-crop-alt me-2"></i>Recortar Foto</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
            <div class="modal-body"><div class="img-container"><img id="image_crop" src="" alt="Imagem para recorte" style="max-width: 100%;"></div></div>
            <div class="modal-footer-cropper">
                <div class="cropper-action-btn">
                    <button type="button" class="btn btn-outline-primary" onclick="cropper.rotate(-90)"><i class="fas fa-undo me-1"></i> Girar Esq</button>
                    <button type="button" class="btn btn-outline-primary" onclick="cropper.rotate(90)"><i class="fas fa-redo me-1"></i> Girar Dir</button>
                </div>
                <div>
                    <button type="button" id="cncmodal" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i> Cancelar</button>
                    <button type="button" id="crop_button" class="btn btn-primary"><i class="fas fa-check me-1"></i> Confirmar</button>
                </div>
            </div>
        </div></div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <script>
        // Configuração do Cropper (igual ao seu código)
        let cropper;
        const modal = new bootstrap.Modal(document.getElementById('modal_crop'));

        document.getElementById('upload_image').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
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
                            ready: function() { cropper.crop(); }
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
                width: 600, height: 800, minWidth: 300, minHeight: 400, maxWidth: 1200, maxHeight: 1600,
                fillColor: '#fff', imageSmoothingEnabled: true, imageSmoothingQuality: 'high'
            });
            if (canvas) {
                const croppedImage = canvas.toDataURL('image/png');
                const preview = document.getElementById('preview_cropped');
                preview.src = croppedImage;
                preview.style.display = 'block';
                document.getElementById('foto_crop').value = croppedImage;
                modal.hide();
            } else {
                alert('Erro ao recortar a imagem. Tente novamente.');
            }
        });

        // Auto logout após 1 hora de inatividade
        const tempoLimite = 3600000;
        setTimeout(() => { window.location.href = "sistema.php?timeout=1"; }, tempoLimite);
    </script>
    </body>
</html>