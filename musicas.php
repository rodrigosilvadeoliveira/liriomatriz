<?php
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

// Inserir música no banco
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome_musica'])) {
    $nome = mysqli_real_escape_string($conexao, $_POST['nome_musica']);
    $tipos = isset($_POST['tipo']) ? implode(',', $_POST['tipo']) : '';
    $arquivo = null;

    // Upload PDF
    if (!empty($_FILES['arquivo_pdf']['name'])) {
        $pasta = "uploads/musicas/";
        if (!is_dir($pasta)) mkdir($pasta, 0777, true);

        $ext = pathinfo($_FILES['arquivo_pdf']['name'], PATHINFO_EXTENSION);
        if (strtolower($ext) == "pdf") {
            $novoNome = uniqid().".".$ext;
            if (move_uploaded_file($_FILES['arquivo_pdf']['tmp_name'], $pasta.$novoNome)) {
                $arquivo = $novoNome;
            }
        }
    }

    $sqlInsert = "INSERT INTO musicas (nome, tipo, arquivo) VALUES ('$nome', '$tipos', '$arquivo')";
    $conexao->query($sqlInsert);
}

// Consulta músicas cadastradas
$sql = "SELECT * FROM musicas ORDER BY nome DESC";
$resultMusicas = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Músicas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
  <?php include("navegacao.php") ?>
</nav>

<div class="container-fluid" style="margin-top:80px;">
  <h3 class="mb-4">Cadastro de Músicas</h3>
  <div class="alert alert-info">
    <a href="verificar_repertorios.php" target="_blank" class="btn btn-sm btn-warning">
        🔧 Verificar Sistema
    </a>
</div>
  <!-- Mensagens de feedback -->
<?php
if (isset($_GET['success']) && $_GET['success'] == 'repertorio_created') {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Repertório criado com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
}
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    $message = '';
    
    switch ($error) {
        case 'empty_selection':
            $message = 'Selecione pelo menos uma música.';
            break;
        case 'no_pdfs':
            $message = 'Nenhum PDF encontrado para as músicas selecionadas.';
            break;
        case 'db_error':
            $message = 'Erro ao salvar no banco de dados.';
            break;
        case 'pdf_error':
            $message = 'Erro ao processar os PDFs.';
            break;
        default:
            $message = 'Erro desconhecido.';
    }
    
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            '.$message.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
}
?>

  <!-- Formulário de Cadastro -->
  <div class="card mb-4">
    <div class="card-body">
      <form method="POST" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Nome da Música</label>
          <input type="text" name="nome_musica" class="form-control" required>
        </div>

        <div class="col-md-4">
          <label class="form-label d-block">Tipo</label>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="tipo[]" value="Atrio">
            <label class="form-check-label">Átrio</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="tipo[]" value="Santo lugar">
            <label class="form-check-label">Santo Lugar</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="tipo[]" value="Santo dos santos">
            <label class="form-check-label">Santo dos Santos</label>
          </div>
        </div>

        <div class="col-md-3">
          <label class="form-label">Arquivo PDF</label>
          <input type="file" name="arquivo_pdf" accept="application/pdf" class="form-control">
        </div>

        <div class="col-md-1 d-flex align-items-end">
          <button type="submit" class="btn btn-success w-100">
            <i class="fas fa-plus"></i> Incluir
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Montar Repertório -->
  <div class="card mt-4">
    <div class="card-header">
      <h5>Montar Repertório</h5>
    </div>
    <div class="card-body">
      <form id="formRepertorio" method="POST" action="incluir_repertorio.php">
        <div class="row mb-3">
          <div class="col-md-3">
            <label class="form-label">Data do Repertório</label>
            <input type="date" name="data_repertorio" class="form-control" required>
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-success">
              <i class="fas fa-plus"></i> Incluir Repertório
            </button>
          </div>
        </div>

      <!-- Lista de Músicas com SELECT de ordem -->
<div class="table-responsive">
  <table id="tabelaMusicas" class="table table-hover">
    <thead>
      <tr>
        <th>Ordem</th>
        <th>ID</th>
        <th>Nome</th>
        <th>Tipo</th>
        <th>Arquivo</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      if ($resultMusicas->num_rows > 0) {
        while ($row = $resultMusicas->fetch_assoc()) {
          echo "<tr>";
          
          // Select numérico de ordem
          echo "<td>
                  <select name='ordemMusica[".$row['id']."]' class='form-select form-select-sm'>
                    <option value=''>--</option>";
          for ($i=1; $i<=8; $i++) {
            echo "<option value='$i'>$i</option>";
          }
          echo "</select>
                </td>";
          
          echo "<td>".$row['id']."</td>";
          echo "<td>".$row['nome']."</td>";
          echo "<td>".$row['tipo']."</td>";
          
          if ($row['arquivo']) {
            echo "<td><a href='uploads/musicas/".$row['arquivo']."' target='_blank'>Abrir PDF</a></td>";
          } else {
            echo "<td>--</td>";
          }

$arquivoUrl = $row['arquivo'] ? $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/uploads/musicas/" . $row['arquivo'] : '';

echo "<td>
        <a href='edit_musica.php?id=".$row['id']."' class='btn btn-sm btn-primary'>
            <i class='fas fa-edit'></i>
        </a>
        <a href='delete_musica.php?id=".$row['id']."' class='btn btn-sm btn-danger' onclick=\"return confirm('Excluir música?')\">
            <i class='fas fa-trash'></i>
        </a>";

if ($arquivoUrl) {
    $msg = urlencode("Confira esta música: ".$row['nome']."\n".$arquivoUrl);
    echo " <a href='https://api.whatsapp.com/send?text=$msg' target='_blank' class='btn btn-sm btn-success'>
              <i class='fas fa-share-alt'></i>
           </a>";
}

echo "</td>";

          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='6' class='text-center'>Nenhuma música cadastrada.</td></tr>";
      } 
      ?>
    </tbody>
  </table>
</div>

<!-- Campo escondido -->
<input type="hidden" name="musicasSelecionadasFinal" id="musicasSelecionadasFinal">
</form>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
  $('#tabelaMusicas').DataTable({
    language: { url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json' },
    responsive: true,
    order: [[1, 'desc']],
    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]]
  });

  // Captura músicas selecionadas com ordem
  $("#formRepertorio").on("submit", function(e){
    let selecionadas = [];

    $("select[name^='ordemMusica']").each(function(){
      let ordem = $(this).val();
      let musicaId = $(this).attr("name").match(/\d+/)[0]; // pega o ID
      if (ordem) {
        selecionadas.push({id: musicaId, ordem: parseInt(ordem)});
      }
    });

    if (selecionadas.length === 0) {
      e.preventDefault();
      alert('Selecione pelo menos uma música com ordem!');
      return false;
    }

    // Ordena pelo número do select
    selecionadas.sort((a, b) => a.ordem - b.ordem);

    // Monta string "id:ordem,id:ordem"
    let finalStr = selecionadas.map(m => m.id + ':' + m.ordem).join(',');
    $("#musicasSelecionadasFinal").val(finalStr);
  });
});
</script>
</body>
</html>

