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

// Definir tabelas disponíveis
$tabelas_escalas = [
    'escalas_midias' => 'Escala Midias'
];

// Processar parâmetro de tabela selecionada
$tabela_selecionada = $_GET['tabela'] ?? 'escalas_midias';
if (!array_key_exists($tabela_selecionada, $tabelas_escalas)) {
    $tabela_selecionada = 'escalas_midias';
}

// Buscar lista de escalas da tabela selecionada
$sql = "SELECT id, nome, pdf_path, data_criacao FROM $tabela_selecionada ORDER BY id DESC";
$res = $conexao->query($sql);
$escalas = [];
if($res) {
    while($row = $res->fetch_assoc()){
        $escalas[] = $row;
    }
}

// Função para obter estatísticas (opcional)
function obterEstatisticasTabela($conexao, $tabela) {
    $sql = "SELECT COUNT(*) as total FROM $tabela";
    $result = $conexao->query($sql);
    return $result ? $result->fetch_assoc()['total'] : 0;
}

include('registroslog.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consulta Escalas - <?php echo htmlspecialchars($tabelas_escalas[$tabela_selecionada]); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', Arial, sans-serif; }
        
        /* Card de navegação entre tabelas */
        .tabela-card {
            background: #fff;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        .tabela-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }
        .tabela-card.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .tabela-card.active .tabela-nome {
            color: white;
        }
        .tabela-nome {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .tabela-count {
            font-size: 12px;
            color: #7f8c8d;
        }
        .tabela-card.active .tabela-count {
            color: rgba(255,255,255,0.9);
        }
        
        /* Card de escala */
        .escala-card {
            background: #fff;
            border-radius: 12px;
            padding: 0;
            margin-bottom: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            overflow: hidden;
            border-left: 4px solid #3498db;
            transition: all 0.3s ease;
        }
        .escala-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .escala-header {
            background: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .escala-header:hover {
            background: #f0f7ff;
        }
        
        .escala-titulo {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }
        
        .escala-data {
            font-size: 24px;
            color: #black;
            margin-top: 5px;
            background-color: azure;
        }
        
        .escala-acoes {
            display: flex;
            gap: 8px;
        }
        
        .btn-download {
            background: #2ecc71;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
        }
        
        .btn-download:hover {
            background: #27ae60;
            transform: scale(1.05);
        }
        
        .btn-download:disabled {
            background: #95a5a6;
            cursor: not-allowed;
        }
        
        .btn-excluir {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
        }
        
        .btn-excluir:hover {
            background: #c0392b;
            transform: scale(1.05);
        }
        
        .escala-detalhes {
            padding: 20px;
            display: none;
        }
        
        .badge-tabela {
            background: #3498db;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            margin-left: 10px;
        }
        
        .modal-img { max-width: 100%; height: auto; }
        .pdf-preview { width: 100%; height: 500px; border: none; }
        
        .btn-consulta-repertorio {
            background: black;
            border: none;
            color: #fff;
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin-left: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-consulta-repertorio:hover {
            background: #44ada9ff;
            text-decoration: none;
        }
        /* Adicione ao CSS existente */
.escala-funcao {
    display: flex;
    align-items: center;
    margin: 8px 0;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    
}

.escala-funcao img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 15px;
    border: 3px solid #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.escala-funcao strong {
    color: #2c3e50;
    font-weight: 600;
}

/* Animação para expandir */
.escala-detalhes {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
        
        /* Responsividade */
        @media (max-width: 768px) {
            .escala-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .escala-acoes {
                width: 100%;
                justify-content: flex-end;
            }
            
            .tabela-card {
                padding: 10px;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body class="container py-4">

    <div class="navegacao">
        <?php include("navegacao.php") ?>
    </div>
    
    <br><br>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-music-note-list"></i> Consulta de Escalas</h2>
        <a href="consultarepertorio.php" class="btn-consulta-repertorio">
            <i class="bi bi-music-note-beamed"></i> Consulta Repertório
        </a>
    </div>
    
    <!-- Seletor de Tabelas -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3">Selecione o tipo de escala:</h5>
            <div class="row">
                <?php foreach($tabelas_escalas as $tabela_id => $tabela_nome): 
                    $total = obterEstatisticasTabela($conexao, $tabela_id);
                ?>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="tabela-card <?php echo $tabela_selecionada == $tabela_id ? 'active' : ''; ?>" 
                         onclick="window.location.href='?tabela=<?php echo $tabela_id; ?>'"
                         style="cursor: pointer;">
                        <div class="tabela-nome"><?php echo htmlspecialchars($tabela_nome); ?></div>
                        <div class="tabela-count"><?php echo $total; ?> escala(s)</div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <!-- Lista de Escalas -->
    <div class="row">
        <div class="col-12">
            <h4 class="mb-3"><?php echo htmlspecialchars($tabelas_escalas[$tabela_selecionada]); ?> 
                <span class="badge bg-primary"><?php echo count($escalas); ?> itens</span>
            </h4>
            
            <?php if(empty($escalas)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Nenhuma escala encontrada para esta categoria.
                </div>
            <?php else: ?>
                <?php foreach($escalas as $escala): ?>
                    <div class="escala-card">
                        <div class="escala-header" data-id="<?php echo $escala['id']; ?>" data-tabela="<?php echo $tabela_selecionada; ?>">
                            <div>
                                <div class="escala-titulo">
                                    <?php echo htmlspecialchars($escala['nome']); ?>
                                    <span class="badge-tabela"><?php echo $tabelas_escalas[$tabela_selecionada]; ?></span>
                                </div>
                                <?php if(isset($escala['data_criacao'])): ?>
                                    <div class="escala-data">
                                        <i class="bi bi-calendar"></i> 
                                        <?php echo date('d/m/Y H:i', strtotime($escala['data_criacao'])); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="escala-acoes">
                                <?php if (!empty($escala['pdf_path'])): ?>
                                    <button class="btn-download" 
                                            data-pdf="<?php echo htmlspecialchars($escala['pdf_path']); ?>"
                                            onclick="visualizarPDF('<?php echo htmlspecialchars($escala['pdf_path']); ?>')">
                                        <i class="bi bi-download"></i> PDF
                                    </button>
                                <?php else: ?>
                                    <button class="btn-download" disabled>
                                        <i class="bi bi-file-earmark-x"></i> Sem PDF
                                    </button>
                                <?php endif; ?>
                                <?php if($pode_excluir): ?>
                                <button class="btn-excluir" 
                                        onclick="excluirEscala(event, <?php echo $escala['id']; ?>, '<?php echo $tabela_selecionada; ?>')">
                                    <i class="bi bi-trash"></i> Excluir
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="escala-detalhes" 
     id="detalhes-<?php echo $tabela_selecionada; ?>-<?php echo $escala['id']; ?>"
     data-carregado="false">
</div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

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
                    <a id="downloadPdf" href="#" class="btn btn-success" download>
                        <i class="bi bi-download"></i> Download PDF
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
$(document).ready(function(){
    // Abrir/fechar detalhes da escala
    $(".escala-header").click(function(e){
        // Não abrir detalhes se clicar nos botões de ação
        if ($(e.target).closest('.btn-download, .btn-excluir').length) {
            return;
        }
        
        let id = $(this).data("id");
        let tabela = $(this).data("tabela");
        let detalhesDiv = $("#detalhes-" + tabela + "-" + id);
        let carregado = detalhesDiv.data("carregado");

        if(detalhesDiv.is(":visible")){
            detalhesDiv.slideUp();
        } else {
            if(carregado === false || carregado === "false"){
                // Marcar como carregando
                detalhesDiv.html('<div class="text-center p-3"><i class="bi bi-hourglass-split"></i> Carregando...</div>').slideDown();
                
                // Requisição AJAX
                $.get("carregar_escalamidias.php", {
                    id: id,
                    tabela: tabela
                }, function(html){
                    detalhesDiv.html(html).data("carregado", true);
                }).fail(function(){
                    detalhesDiv.html('<div class="alert alert-danger">Erro ao carregar detalhes</div>');
                });
            } else {
                detalhesDiv.slideDown();
            }
        }
    });
});
    function visualizarPDF(pdfPath) {
        event.stopPropagation();
        
        if (!pdfPath) {
            alert('PDF não disponível para visualização.');
            return;
        }
        
        // Configurar o iframe e link de download no modal
        $("#pdfPreview").attr('src', pdfPath);
        $("#downloadPdf").attr('href', pdfPath);
        
        // Abrir o modal
        let modal = new bootstrap.Modal(document.getElementById('modalPdf'));
        modal.show();
    }

    function excluirEscala(event, id, tabela) {
        event.stopPropagation();
        
        if(confirm("Tem certeza que deseja excluir esta escala?\nEsta ação não pode ser desfeita.")){
            $.post("excluir_escalamidias.php", {
                id: id,
                tabela: tabela
            }, function(resposta){
                if(resposta.trim() === "ok"){
                    // Recarregar apenas a seção de escalas
                    location.reload();
                } else {
                    alert("Erro ao excluir escala: " + resposta);
                }
            }).fail(function() {
                alert("Erro na comunicação com o servidor.");
            });
        }
    }
    </script>
</body>
</html>