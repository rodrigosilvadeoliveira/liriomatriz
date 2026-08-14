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
    <link rel="stylesheet" href="sistemastyle.css?t=<?=time()?>">
    
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
                
                <form method="POST" action="enviarcadastroadm.php" enctype="multipart/form-data" class="row g-3">
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
    <option value="admin">Administrador</option>
    <option value="consulta">Consulta</option>
    <option value="lider">Lider</option>
    <option value="live">Live</option>
    <option value="master">Master</option>
    <option value="midia">Midias</option>
    <option value="secretaria">Secretaria</option>
    <option value="voluntario">Voluntário</option>
    
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