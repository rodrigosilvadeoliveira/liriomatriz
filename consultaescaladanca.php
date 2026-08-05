<?php
date_default_timezone_set('America/Sao_Paulo');
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
$sql = "SELECT id, nome, pdf_path FROM escalas_danca ORDER BY id DESC";
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
include('registroslog.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consulta Escala</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styleconsultaescala.css?v=<?=time()?>">
</head>
<body class="container py-4">

    <div class="navegacao">
        <?php include("navegacao.php") ?>
    </div>
<br><br>
<div class="action-buttons">
            <button class="btn-voltar" onclick="voltar()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                </svg>
                Voltar
            </button>
           </div>
        <script>
function voltar() {
    window.history.back();
}
</script>
    <h2 class="mb-4">Consulta de Escalas Dança</h2>

    <?php if(empty($escalas)){ ?>
        <p>Nenhuma escala encontrada.</p>
    <?php } else { ?>
        <?php foreach($escalas as $escala){ ?>
            <div class="escala-nome" data-id="<?php echo $escala['id']; ?>">
                <strong><?php echo htmlspecialchars($escala['nome']); ?></strong>
                <?php if (!empty($escala['pdf_path'])): ?>
                    <a href="<?php echo htmlspecialchars($escala['pdf_path']); ?>" class="btn-download" download>Download PDF</a>
                <?php else: ?>
                    <span class="btn-download" style="background-color: #95a5a6; cursor: not-allowed;">PDF Indisponível</span>
                <?php endif; ?>
                <?php if($pode_excluir): ?>
                <button class="btn-excluir" data-id="<?php echo $escala['id']; ?>">Excluir</button>
             <?php endif; ?>
            </div>
            <div class="detalhes" id="detalhes-<?php echo $escala['id']; ?>"></div>
        <?php } ?>
    <?php } ?>

    <!-- Modal para visualizar PDF -->
    <div class="modal fade" id="modalPdf" tabindex="-1" aria-labelledby="modalPdfLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPdfLabel">Visualizar Escala em PDF</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="pdfPreview" class="pdf-preview" src=""></iframe>
                </div>
                <div class="modal-footer">
                    <a id="downloadPdf" href="#" class="btn btn-success" download>Download PDF</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function(){
        // Abrir modal com visualização do PDF
        $(".btn-download").click(function(e){
            // Se for um link de download, não fazer nada (deixar o download ocorrer naturalmente)
            if ($(this).attr('href')) {
                return;
            }
            
            e.preventDefault();
            e.stopPropagation();
            
            // Verificar se há um PDF associado
            let pdfPath = $(this).data('pdf');
            if (!pdfPath) {
                alert('PDF não disponível para download.');
                return;
            }
            
            // Configurar o iframe e link de download no modal
            $("#pdfPreview").attr('src', pdfPath);
            $("#downloadPdf").attr('href', pdfPath);
            
            // Abrir o modal
            let modal = new bootstrap.Modal(document.getElementById('modalPdf'));
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
                    $.get("carregar_escaladanca.php", {id:id}, function(html){
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
                $.post("excluir_escaladanca.php", {id:id}, function(resposta){
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