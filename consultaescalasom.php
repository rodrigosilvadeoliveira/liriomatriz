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
$sql = "SELECT id, nome, imagem FROM escalas_som ORDER BY id DESC";
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
        .escala-data { font-size:18px; font-weight:bold; color:#2c3e50; margin-bottom:12px; }
        .escala-funcao { display:flex; align-items:center; margin:6px 0; padding:8px; background:#ecf0f1; border-radius:8px; }
        .escala-funcao img { width:40px; height:40px; border-radius:50%; object-fit:cover; margin-right:10px; border:2px solid #fff; box-shadow:0 2px 4px rgba(0,0,0,0.2); }
        .escala-funcao strong { color:#2980b9; margin-right:5px; }
        .btn-excluir { background:#e74c3c; border:none; color:#fff; padding:5px 10px; border-radius:6px; font-size:14px; cursor:pointer; }
        .btn-excluir:hover { background:#c0392b; }
        .btn-visualizar { background:#3498db; border:none; color:#fff; padding:5px 10px; border-radius:6px; font-size:14px; cursor:pointer; margin-right: 8px; }
        .btn-visualizar:hover { background:#2980b9; }
        .modal-img { max-width: 100%; height: auto; }
    </style>
</head>
<body class="container py-4">

    <div class="navegacao">
        <?php include("navegacao.php") ?>
    </div>
<br><br>
    <h2 class="mb-4">Consulta de Escalas Som</h2>

    <?php if(empty($escalas)){ ?>
        <p>Nenhuma escala encontrada.</p>
    <?php } else { ?>
        <?php foreach($escalas as $escala){ ?>
            <div class="escala-nome" data-id="<?php echo $escala['id']; ?>">
                <strong><?php echo htmlspecialchars($escala['nome']); ?></strong>
                <button class="btn-visualizar" data-id="<?php echo $escala['id']; ?>" data-imagem="<?php echo htmlspecialchars($escala['imagem']); ?>">Visualizar</button>
                <button class="btn-excluir" data-id="<?php echo $escala['id']; ?>">Excluir</button>
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
                    });
                } else {
                    detalhesDiv.slideDown();
                }
            }
        });

        // Excluir escala
        $(".btn-excluir").click(function(e){
            e.stopPropagation(); // Impede que o evento propague para o elemento pai
            let id = $(this).data("id");
            if(confirm("Tem certeza que deseja excluir esta escala?")){
                $.post("excluir_escalasom.php", {id:id}, function(resposta){
                    if(resposta.trim() === "ok"){
                        location.reload(); // força atualização da página
                    } else {
                        alert("Erro ao excluir escala: " + resposta);
                    }
                });
            }
        });
    });
    </script>
</body>
</html>