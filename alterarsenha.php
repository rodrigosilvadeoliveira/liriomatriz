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
$sql = "SELECT * FROM cadastroadm WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_voluntario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: perfil.php?erro=1');
    exit();
}

$voluntario = $result->fetch_assoc();
include('registroslog.php');
// Verifica imagem cortada da sessão ou usa a existente do banco
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Senha</title>
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
            <h1 class="h3 text-gray-800"><i class="fas fa-user-plus me-2"></i>Alteração de Cadastro</h1>
        </div>

        <!-- Mensagem de Boas-Vindas -->
        <div class="alert alert-primary mb-4">
            <i class="fas fa-user me-2"></i> Bem-vindo, <strong><?php echo $logado; ?></strong>
        </div>

        <!-- Formulário -->
        <div class="card card-form">
            <div class="card-header card-header-custom">
                <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Informações de Acesso</h5>
            </div>

            <form method="POST" action="salvaralteraracesso.php" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-6">
                    <label for="nome" class="form-label -field">*<?php echo __('nome_completo') ?></label>
                    <input type="text" name="nome" class="form-control"
                        value="<?php echo htmlspecialchars($voluntario['nome']); ?>">

                </div>

                <div class="col-md-6">
                    <label for="usuario" class="form-label -field">*<?php echo __('usuario') ?></label>
                    <input type="text" name="usuario" class="form-control"
                        value="<?php echo htmlspecialchars($voluntario['usuario']); ?>" readonly>
                </div>

                <div class="col-md-6">
                    <label for="senha" class="form-label -field">*<?php echo __('nova_senha') ?></label>
                    <input type="text" name="senha" id="senha" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label -field">Email</label>
                    <input type="email" name="email" class="form-control"
                        value="<?php echo htmlspecialchars($voluntario['email']); ?>">
                </div>
                <div class="col-md-6">
                    <label for="telefone" class="form-label -field">*Telefone</label>
                    <input type="text" name="telefone" id="telefone" class="form-control"
                        value="<?php echo htmlspecialchars($voluntario['telefone']); ?>">

                </div>
                <div class="col-md-6">
                    <label for="celular" class="form-label -field">Celular</label>
                    <input type="text" name="celular" class="form-control"
                        value="<?php echo htmlspecialchars($voluntario['celular']); ?>">
                </div>

                <div class="col-md-6">
                    <label for="nivel_acesso" class="form-label required-field">Perfil</label>
                    <input type="text" name="nivel_acesso" class="form-control"
                        value="<?php echo htmlspecialchars($voluntario['nivel_acesso']); ?>" readonly>
            <div class="col-3">
            <input type="hidden" name="id" value="<?php echo $id_voluntario?>">
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


</body>

</html>