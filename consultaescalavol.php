<?php
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');
include_once('config.php');

if((!isset($_SESSION['usuario']) == true) and ($_SESSION['senha']) == true) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
}
$logado = $_SESSION['usuario'];

// Buscar lista de escalas salvas
$sql = "SELECT id, nome, imagem FROM escalas_salvas ORDER BY id DESC";
$res = $conexao->query($sql);
$escalas = [];
while($row = $res->fetch_assoc()){
    $escalas[] = $row;
}

// Função PHP para traduzir dia da semana
function diaSemana($dataIso) {
    $dias = ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'];
    $time = strtotime($dataIso);
    return $dias[date('w', $time)];
}

// Processar adição de repertório
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar_repertorio'])) {
    $data_escala = $_POST['data_escala'];
    $musica1 = $_POST['musica1'];
    $musica2 = $_POST['musica2'];
    $musica3 = $_POST['musica3'];
    $musica4 = $_POST['musica4'];
    $escala_id = $_POST['escala_id'];
    
    // Verificar se já existe repertório para esta data
    $check_sql = "SELECT id FROM repertorios WHERE data_escala = '$data_escala' AND escala_id = '$escala_id'";
    $check_result = $conexao->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        // Atualizar repertório existente
        $update_sql = "UPDATE repertorios SET musica1 = '$musica1', musica2 = '$musica2', 
                      musica3 = '$musica3', musica4 = '$musica4' 
                      WHERE data_escala = '$data_escala' AND escala_id = '$escala_id'";
        $conexao->query($update_sql);
    } else {
        // Inserir novo repertório
        $insert_sql = "INSERT INTO repertorios (escala_id, data_escala, musica1, musica2, musica3, musica4) 
                      VALUES ('$escala_id', '$data_escala', '$musica1', '$musica2', '$musica3', '$musica4')";
        $conexao->query($insert_sql);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consulta Escala</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: Arial, sans-serif; }
        .escala-nome { cursor:pointer; padding:12px; background:#fff; margin-bottom:10px; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.1); }
        .escala-nome:hover { background:#eaf2fb; }
        .detalhes { display:none; margin-top:10px; }
        .escala-card { background:#fff; border-radius:12px; padding:15px; margin-bottom:15px; box-shadow:0 4px 6px rgba(0,0,0,0.1); }
        .escala-data { font-size:18px; font-weight:bold; color:#2c3e50; margin-bottom:12px; display: flex; justify-content: space-between; align-items: center; }
        .escala-funcao { display:flex; align-items:center; margin:6px 0; padding:8px; background:#ecf0f1; border-radius:8px; }
        .escala-funcao img { width:40px; height:40px; border-radius:50%; object-fit:cover; margin-right:10px; border:2px solid #fff; box-shadow:0 2px 4px rgba(0,0,0,0.2); }
        .escala-funcao strong { color:#2980b9; margin-right:5px; }
        .btn-visualizar { background:#3498db; border:none; color:#fff; padding:5px 10px; border-radius:6px; font-size:14px; cursor:pointer; margin-right: 8px; }
        .btn-visualizar:hover { background:#2980b9; }
        .btn-repertorio { background:#27ae60; border:none; color:#fff; padding:5px 10px; border-radius:6px; font-size:14px; cursor:pointer; }
        .btn-repertorio:hover { background:#219653; }
        .modal-img { max-width: 100%; height: auto; }
        .repertorio-info { background: #f8f9fa; padding: 10px; border-radius: 8px; margin-top: 10px; }
        .repertorio-info h6 { color: #27ae60; margin-bottom: 5px; }
        .repertorio-list { list-style-type: none; padding-left: 0; }
        .repertorio-list li { padding: 3px 0; }
    </style>
</head>
<body class="container py-4">

    <div class="navegacao">
        <?php include("navegacao.php") ?>
    </div>
    <br><br>

    <h2 class="mb-4">Consulta de Escalas Louvor</h2>

    <?php if(empty($escalas)){ ?>
        <p>Nenhuma escala encontrada.</p>
    <?php } else { ?>
        <?php foreach($escalas as $escala){ ?>
            <div class="escala-nome" data-id="<?php echo $escala['id']; ?>">
                <strong><?php echo htmlspecialchars($escala['nome']); ?></strong>
                <button class="btn-visualizar" data-id="<?php echo $escala['id']; ?>" data-imagem="<?php echo htmlspecialchars($escala['imagem']); ?>">Visualizar</button>
            </div>
            <div class="detalhes" id="detalhes-<?php echo $escala['id']; ?>"></div>
        <?php } ?>
    <?php } ?>

    <!-- Modal para visualizar imagem -->
    <div class="modal fade" id="modalImagem" tabindex="-1" aria-labelledby="modalImagemLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImagemLabel">Visualizar Escala</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="imagemEscala" src="" class="modal-img" alt="Imagem da Escala">
                </div>
                <div class="modal-footer">
                    <a id="downloadImagem" href="#" class="btn btn-success" download>Download</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para adicionar repertório -->
    <div class="modal fade" id="modalRepertorio" tabindex="-1" aria-labelledby="modalRepertorioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRepertorioLabel">Adicionar Repertório</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <input type="hidden" name="escala_id" id="escala_id_repertorio">
                        <input type="hidden" name="data_escala" id="data_escala_repertorio">
                        
                        <div class="mb-3">
                            <label for="musica1" class="form-label">Música 1</label>
                            <input type="text" class="form-control" id="musica1" name="musica1" required>
                        </div>
                        <div class="mb-3">
                            <label for="musica2" class="form-label">Música 2</label>
                            <input type="text" class="form-control" id="musica2" name="musica2" required>
                        </div>
                        <div class="mb-3">
                            <label for="musica3" class="form-label">Música 3</label>
                            <input type="text" class="form-control" id="musica3" name="musica3" required>
                        </div>
                        <div class="mb-3">
                            <label for="musica4" class="form-label">Música 4</label>
                            <input type="text" class="form-control" id="musica4" name="musica4" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="adicionar_repertorio" class="btn btn-primary">Salvar Repertório</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function(){
        // Abrir modal com a imagem da escala
        $(".btn-visualizar").click(function(e){
            e.stopPropagation(); // Impede que o evento propague para o elemento pai
            let imagemSrc = $(this).data('imagem');
            let escalaId = $(this).data('id');
            
            // Configurar a imagem e o link de download no modal
            $("#imagemEscala").attr('src', imagemSrc);
            $("#downloadImagem").attr('href', imagemSrc);
            
            // Abrir o modal
            let modal = new bootstrap.Modal(document.getElementById('modalImagem'));
            modal.show();
        });

        $(".escala-nome").click(function(){
            let id = $(this).data("id");
            let detalhesDiv = $("#detalhes-"+id);

            if(detalhesDiv.is(":visible")){
                detalhesDiv.slideUp();
            } else {
                if(detalhesDiv.is(":empty")){
                    // Requisição AJAX para carregar dados da escala
                    $.get("carregar_escala.php", {id:id}, function(html){
                        detalhesDiv.html(html).slideDown();
                        
                        // Adicionar eventos aos botões de repertório após carregar o conteúdo
                        $(".btn-repertorio").click(function(e){
                            e.stopPropagation();
                            let dataEscala = $(this).data('data');
                            let escalaId = $(this).data('escala-id');
                            
                            // Preencher os campos do modal de repertório
                            $("#escala_id_repertorio").val(escalaId);
                            $("#data_escala_repertorio").val(dataEscala);
                            
                            // Verificar se já existe repertório para esta data
                            $.get("carregar_repertorio.php", {
                                escala_id: escalaId, 
                                data_escala: dataEscala
                            }, function(data){
                                if(data) {
                                    const repertorio = JSON.parse(data);
                                    $("#musica1").val(repertorio.musica1 || '');
                                    $("#musica2").val(repertorio.musica2 || '');
                                    $("#musica3").val(repertorio.musica3 || '');
                                    $("#musica4").val(repertorio.musica4 || '');
                                } else {
                                    $("#musica1").val('');
                                    $("#musica2").val('');
                                    $("#musica3").val('');
                                    $("#musica4").val('');
                                }
                                
                                // Abrir o modal
                                let modal = new bootstrap.Modal(document.getElementById('modalRepertorio'));
                                modal.show();
                            });
                        });
                    });
                } else {
                    detalhesDiv.slideDown();
                }
            }
        });
    });
    </script>
</body>
</html>