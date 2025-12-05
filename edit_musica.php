<?php
date_default_timezone_set('America/Sao_Paulo');
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');
include_once('config.php');

// Verifica sessão
if((!isset($_SESSION['usuario']) == true) && ($_SESSION['senha']) == true) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
}
$logado = $_SESSION['usuario'];

// Verifica se o ID foi passado
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: musicas.php');
    exit;
}

$id = mysqli_real_escape_string($conexao, $_GET['id']);

// Busca os dados da música
$sql = "SELECT * FROM musicas WHERE id = '$id'";
$result = $conexao->query($sql);

if($result->num_rows == 0) {
    header('Location: musicas.php');
    exit;
}

$musica = $result->fetch_assoc();

// Processa o formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome_musica'])) {
    $nome = mysqli_real_escape_string($conexao, $_POST['nome_musica']);
    $tema = mysqli_real_escape_string($conexao, $_POST['tema_musica']);
    $tipos = isset($_POST['tipo']) ? implode(',', $_POST['tipo']) : '';
    $arquivo = $musica['arquivo']; // Mantém o arquivo atual por padrão

    // Upload de novo PDF, se fornecido
    if (!empty($_FILES['arquivo_pdf']['name'])) {
        $pasta = "uploads/musicas/";
        if (!is_dir($pasta)) mkdir($pasta, 0777, true);

        $nome_arquivo_original = $_FILES['arquivo_pdf']['name'];
        $ext = pathinfo($nome_arquivo_original, PATHINFO_EXTENSION);
        
        if (strtolower($ext) == "pdf") {
            // Remove o arquivo anterior, se existir
            if($arquivo && file_exists($pasta.$arquivo)) {
                unlink($pasta.$arquivo);
            }
            
            // Verificar se arquivo já existe e adicionar sufixo se necessário
            $caminho_completo = $pasta . $nome_arquivo_original;
            $contador = 1;
            $nome_base = pathinfo($nome_arquivo_original, PATHINFO_FILENAME);
            
            while (file_exists($caminho_completo)) {
                $nome_arquivo_original = $nome_base . '_' . $contador . '.' . $ext;
                $caminho_completo = $pasta . $nome_arquivo_original;
                $contador++;
            }
            
            if (move_uploaded_file($_FILES['arquivo_pdf']['tmp_name'], $caminho_completo)) {
                $arquivo = $nome_arquivo_original; // Salva o nome original no banco
            }
        }
    }

    // Remove arquivo se solicitado
    $remover_arquivo = isset($_POST['remover_arquivo']) ? true : false;
    if($remover_arquivo && $arquivo) {
        if(file_exists($pasta.$arquivo)) {
            unlink($pasta.$arquivo);
        }
        $arquivo = null;
    }

    $sqlUpdate = "UPDATE musicas SET nome = '$nome', tema = '$tema', tipo = '$tipos', arquivo = '$arquivo' WHERE id = '$id'";
    if($conexao->query($sqlUpdate)) {
        header('Location: musicas.php?success=musica_updated');
        exit;
    } else {
        $erro = "Erro ao atualizar a música: " . $conexao->error;
    }
}
include('registroslog.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Editar Música</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
  <?php include("navegacao.php") ?>
</nav>

<div class="container-fluid" style="margin-top:80px;">
  <h3 class="mb-4">Editar Música</h3>
  
  <!-- Mensagens de feedback -->
  <?php if(isset($erro)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo $erro; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <!-- Formulário de Edição -->
  <div class="card">
    <div class="card-body">
      <form method="POST" enctype="multipart/form-data">
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Nome da Música</label>
            <input type="text" name="nome_musica" class="form-control" value="<?php echo htmlspecialchars($musica['nome']); ?>" required>
          </div>
        </div>
<div class="col-md-6">
            <label class="form-label">Tema</label>
            <input type="text" name="tema_musica" class="form-control" value="<?php echo htmlspecialchars($musica['tema']); ?>" required>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label d-block">Tipo</label>
            <?php
            $tiposArray = explode(',', $musica['tipo']);
            ?>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="tipo[]" value="Atrio" <?php echo in_array('Atrio', $tiposArray) ? 'checked' : ''; ?>>
              <label class="form-check-label">Átrio</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="tipo[]" value="Santo lugar" <?php echo in_array('Santo lugar', $tiposArray) ? 'checked' : ''; ?>>
              <label class="form-check-label">Santo Lugar</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="tipo[]" value="Santo dos santos" <?php echo in_array('Santo dos santos', $tiposArray) ? 'checked' : ''; ?>>
              <label class="form-check-label">Santo dos Santos</label>
            </div>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Arquivo PDF</label>
            
            <?php if($musica['arquivo']): ?>
              <div class="mb-2">
                <a href="uploads/musicas/<?php echo $musica['arquivo']; ?>" target="_blank" class="btn btn-sm btn-info">
                  <i class="fas fa-file-pdf"></i> Visualizar PDF atual
                </a>
                
                <div class="form-check mt-2">
                  <input class="form-check-input" type="checkbox" name="remover_arquivo" id="remover_arquivo">
                  <label class="form-check-label" for="remover_arquivo">
                    Remover arquivo atual
                  </label>
                </div>
              </div>
              
              <p class="text-muted small">Ou envie um novo arquivo para substituir o atual:</p>
            <?php endif; ?>
            
            <input type="file" name="arquivo_pdf" accept="application/pdf" class="form-control">
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Salvar Alterações
            </button>
            <a href="musicas.php" class="btn btn-secondary">
              <i class="fas fa-arrow-left"></i> Voltar
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>