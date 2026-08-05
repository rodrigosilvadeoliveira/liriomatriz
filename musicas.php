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

// Inserir música no banco
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome_musica'])) {
    $nome = mysqli_real_escape_string($conexao, $_POST['nome_musica']);
    $tema = mysqli_real_escape_string($conexao, $_POST['tema_musica']);
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

    $sqlInsert = "INSERT INTO musicas (nome, tema, tipo, arquivo) VALUES ('$nome','$tema', '$tipos', '$arquivo')";
    $conexao->query($sqlInsert);
}

// Consulta músicas cadastradas
$sql = "SELECT * FROM musicas ORDER BY nome DESC";
$resultMusicas = $conexao->query($sql);

include('registroslog.php');

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Músicas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
<style>
    /* Estilo para ocultar a coluna ID */
    .hidden-id {
      display: none;
    }
    #textos{
    margin-top: 1%;
    text-align: center;
    font-family: 'Times New Roman', Times, serif;
    font-size: 24px;
    color: ;
}
  </style>
</head>
<body>
<<<<<<< HEAD

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
=======
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
>>>>>>> 2657b610b256eecd0effa4e3c07a7749882687c8
  <?php include("navegacao.php") ?>
</nav>

<div class="container-fluid" style="margin-top:80px;">
  <h3 class="mb-4">Cadastro de Músicas</h3>
  <div class="alert alert-info">
    <h5>
<<<<<<< HEAD
                        <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#musicos"
                            role="button" aria-expanded="false" aria-controls="musicos">Musicas do Tabernaculo <i
                                class="fas fa-chevron-down ms-2"></i>
                        </a>
                    </h5>
                    <div class="collapse" id="musicos">
=======
                        <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#tabernaculo"
                            role="button" aria-expanded="false" aria-controls="musicos">Tabernaculo <i
                                class="fas fa-chevron-down ms-2"></i>
                        </a>
                    </h5>
                    <div class="collapse" id="tabernaculo">
>>>>>>> 2657b610b256eecd0effa4e3c07a7749882687c8
                        <div class="row">
                            <div class="col-md-6">
                                <h3 id="textos">    
    <p><b>Átrio:</b> é onde a maioria do povo de Deus se reúne – e fica. Lá, cantamos cânticos de júbilo, de guerra, de testemunho e de convite ao louvor.</p>
    <p><b>Santo Lugar:</b> é o lugar do sacerdote, é um aprofundamento. É um lugar de ministração de louvor através de cânticos de comunhão, de Edificação no Espírito Santo, de clamor, de Exaltação.</p>
    <p><b>Santo dos Santos:</b> antes do sacrifício de Jesus, o véu dividia o espaço onde estava a arca da presença do Senhor, e lá, somente o sacerdote poderia entrar uma vez ao ano. Após a cruz, esse véu foi rasgado de alto a baixo, e o acesso a Deus foi aberto a todos os filhos adoradores que foram salvos, lavados e edificados em Cristo. E quais os hinos desta etapa da adoração? Hinos de Contemplação, de Adoração</p>
       </h3>
      </div>
     </div>
    </div>
<<<<<<< HEAD
  <!--<div class="alert alert-info">
     <a href="verificar_repertorios.php" target="_blank" class="btn btn-sm btn-warning">
        🔧 Verificar Sistema
    </a> -->
</div>

=======
    <!-- <a href="verificar_repertorios.php" target="_blank" class="btn btn-sm btn-warning">
        🔧 Verificar Sistema
    </a> -->
</div>
>>>>>>> 2657b610b256eecd0effa4e3c07a7749882687c8
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
          <label class="form-label">Tema</label>
          <input type="text" name="tema_musica" class="form-control">
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
<<<<<<< HEAD
            <label class="form-label"><b>Data do Repertório:</b></label>
            <input type="date" name="data_repertorio" class="form-control" required>
          </div>
          <div class="col-md-6">
                        <label for="periodo" class="form-label required-field"><b>Periodo:</b></label>
                        <select id="periodo" class="form-select" name="periodo">
                            <option value="">Selecione</option>
                            <option value="Quinta Be power">Quinta Be power</option>
                            <option value="Domingo Manhã">Domingo Manhã</option>
                            <option value="Domingo Noite">Domingo Noite</option>
                            <option value="Kids be power">Kids be power</option>
                            <option value="Kids manhã">Kids manhã</option>
                            <option value="Kids noite">Kids noite</option>
                            <option value="GC Adolescentes">GC Adolescentes</option>
                            <option value="GC Jovens">GC Jovens</option>
                            <option value="GC Homens">GC Homens</option>
                            <option value="GC Mulheres">GC Mulheres</option>
                            <option value="Outros">Outros</option>
                        </select>
                    </div>
<div class="col-md-2 d-flex align-items-end">
=======
            <label class="form-label">Data do Repertório</label>
            <input type="date" name="data_repertorio" class="form-control" required>
          </div>
          <div class="col-md-2 d-flex align-items-end">
>>>>>>> 2657b610b256eecd0effa4e3c07a7749882687c8
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
        <th class="hidden-id">ID</th>
        <th>Nome</th>
        <th>Tipo</th>
        <th>Tema</th>
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
          
          echo "<td class='hidden-id'>".$row['id']."</td>";
          echo "<td>".$row['nome']."</td>";
          echo "<td>".$row['tipo']."</td>";
          echo "<td>".$row['tema']."</td>";
          
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
  $('#tabelaMusicas').DataTable({
    language: { url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json' },
<<<<<<< HEAD
=======
    stateSave: true,
>>>>>>> 2657b610b256eecd0effa4e3c07a7749882687c8
    responsive: true,
    order: [[1, 'desc']],
    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
    columnDefs: [
      { targets: 'hidden-id', visible: false } // Oculta a coluna ID
    ]
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

