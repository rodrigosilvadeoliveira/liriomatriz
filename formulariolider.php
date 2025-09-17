<?php
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
            <h1 class="h3 text-gray-800"><i class="fas fa-user-plus me-2"></i>Cadastro de Voluntario</h1>
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
                <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Informações do Volintario(a)</h5>
            </div>
                
                <form method="POST" action="enviarcadastrolider.php" enctype="multipart/form-data" class="row g-3">
                    <div class="col-md-6">
                        <label for="nome" class="form-label -field">*Nome completo</label>
                        <input type="text" name="nome" id="nome" class="form-control" >
                    </div>
                
                    <div class="col-md-6">
                        <label for="usuario" class="form-label -field">*Login</label>
                        <input type="text" name="usuario" id="usuario" class="form-control" >
                    </div>

                    <div class="col-md-6">
                        <label for="senha" class="form-label -field">*Senha</label>
                        <input type="text" name="senha" id="senha" class="form-control" >
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label -field">Email</label>
                        <input type="text" name="email" id="email" class="form-control" >
                    </div>
                    <div class="col-md-6">
                        <label for="telefone" class="form-label -field">*Telefone</label>
                        <input type="text" name="telefone" id="telefone" class="form-control" >
                    </div>
                    <div class="col-md-6">
                        <label for="celular" class="form-label -field">Celular</label>
                        <input type="text" name="celular" id="celular" class="form-control" >
                    </div>
  <div class="col-md-3">
    <label for="inputState" class="form-label">*Perfil:</label>
    <select id="nivel_acesso" class="form-select" name="nivel_acesso">
    <option value="">Selecione</option>
    <option value="consulta">Consulta</option>
    <option value="lider">Lider</option>
    
    </select>
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