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
$igreja = $_SESSION['igreja_id'];

// Verificar parâmetros
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$tabela = isset($_GET['tabela']) ? $_GET['tabela'] : '';

// Lista de tabelas permitidas
// ==========================================
// NOVO MODELO UNIFICADO
// ==========================================

$tabela = 'escalas_louvor';

// validar apenas tabela oficial
if ($tabela !== 'escalas_louvor') {
    die("Tabela inválida.");
}
// Buscar dados da escala
$sql = "SELECT * FROM escalas_louvor WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Escala não encontrada.");
}

$escala = $result->fetch_assoc();
$stmt->close();

// Decodificar dados JSON da coluna dados_escala
$dados_json = [];

if (!empty($escala['dados_escala'])) {
    $dados_json = json_decode($escala['dados_escala'], true);
}

// ==============================
// NORMALIZAR JSON
// ==============================

$escalaNormalizada = [];
$datasNormalizadas = [];

if (isset($dados_json['escala']) && is_array($dados_json['escala'])) {
    
    foreach ($dados_json['escala'] as $dataIso => $funcoes) {
        if (!is_array($funcoes)) continue;
        
        // Adicionar data
        $dataExistente = false;
        foreach ($datasNormalizadas as $d) {
            if ($d['iso'] === $dataIso) {
                $dataExistente = true;
                break;
            }
        }
        
        if (!$dataExistente) {
            $datasNormalizadas[] = [
                'iso' => $dataIso,
                'formatada' => date('d/m/Y', strtotime($dataIso))
            ];
        }
        
        foreach ($funcoes as $funcao => $registro) {
            if (!is_array($registro)) continue;
            
            // Capturar ID corretamente
            $voluntario_id = $registro['voluntario_id'] ?? $registro['cadastroadm_id'] ?? '';
            
            $escalaNormalizada[$funcao][$dataIso] = [
                'id'   => $voluntario_id,
                'nome' => $registro['usuario'] ?? ''
            ];
        }
    }
}
elseif (isset($dados_json['escalas']) && is_array($dados_json['escalas'])) {
    
    foreach ($dados_json['escalas'] as $funcao => $datas) {
        if (!is_array($datas)) continue;
        
        foreach ($datas as $dataIso => $valor) {
            $escalaNormalizada[$funcao][$dataIso] = [
                'id'   => $valor,
                'nome' => ''
            ];
            
            $dataExistente = false;
            foreach ($datasNormalizadas as $d) {
                if ($d['iso'] === $dataIso) {
                    $dataExistente = true;
                    break;
                }
            }
            
            if (!$dataExistente) {
                $datasNormalizadas[] = [
                    'iso' => $dataIso,
                    'formatada' => date('d/m/Y', strtotime($dataIso))
                ];
            }
        }
    }
}

// Mapear nomes das tabelas
$nomes_tabelas = [
    'escalas_louvor' => 'Escala Louvor (Quinta e Domingo manhã)',
    'escalas_louvornoite' => 'Escala Louvor (Domingo Noite)',
    'escalas_gcs' => 'Escala GCs',
    'escalas_louvorkids' => 'Escala Louvor Kids',
    'escalas_criativo' => 'Escala Criativo',
    'escalas_som' => 'Escala Som',
    'escalas_midias' => 'Escala Midias',
    'escalas_kids' => 'Escala Kids',
    'escalas_danca' => 'Escala Dança',
    'escalas_staff' => 'Escala Staff',
    'escalas_recepcao' => 'Escala Recepção',
    'escalas_recepcaonoite' => 'Escala Recepção Noite'
];

// Configurar funções específicas
// ==========================================
// CONFIGURAÇÃO DOS TIPOS
// ==========================================

$tipoEscala = $escala['tipo'] ?? 'louvor';

$mapaNomes = [
    'louvor'        => 'Louvor Igreja',
    'louvorkids'    => 'Louvor Kids',
    'criativo'      => 'Escala Criativo',
    'som'           => 'Escala Som',
    'midias'        => 'Escala Mídias',
    'kids'          => 'Escala Kids',
    'danca'         => 'Escala Dança',
    'staff'         => 'Escala Staff',
    'recepcao'      => 'Escala Recepção',
    'gcs'           => 'Escala GCs'
];

// Nome amigável
$nome_tabela = $mapaNomes[$tipoEscala] ?? 'Escala';

// ==========================================
// BUSCAR FUNÇÕES DINAMICAMENTE
// ==========================================

$funcoes = [];

$sqlFuncoes = "
    SELECT nome
    FROM funcoes
    WHERE tipo = ?
    AND igreja_id = ?
    ORDER BY id ASC
";

$stmtFuncoes = $conexao->prepare($sqlFuncoes);

$stmtFuncoes->bind_param(
    "si",
    $tipoEscala,
    $igreja
);

$stmtFuncoes->execute();

$resultFuncoes = $stmtFuncoes->get_result();

while ($row = $resultFuncoes->fetch_assoc()) {
    $funcoes[] = $row['nome'];
}

$stmtFuncoes->close();
// Buscar nomes dos voluntários por função
$nomesPorFuncao = [];

foreach ($funcoes as $funcao) {
    $sql = "
        SELECT v.id, v.usuario
        FROM voluntarios v
        JOIN voluntario_funcoes vf ON vf.voluntario_id = v.id
        JOIN funcoes f ON f.id = vf.funcao_id
        WHERE v.igreja_id = ? AND f.nome = ?
        ORDER BY v.usuario ASC
    ";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("is", $igreja, $funcao);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $nomes = [];
    while ($row = $resultado->fetch_assoc()) {
        $nomes[] = [
            'id' => $row['id'],
            'nome' => $row['usuario']
        ];
    }
    $nomesPorFuncao[$funcao] = $nomes;
    $stmt->close();
}

// Mapear fotos por ID
$fotosPorId = [];
$resFotos = $conexao->query("SELECT id, foto FROM voluntarios");
if ($resFotos && $resFotos->num_rows > 0) {
    while ($r = $resFotos->fetch_assoc()) {
        $fotosPorId[$r['id']] = $r['foto'];
    }
}

// Preparar dados para JavaScript
$dadosEscalaArray = [
    'nome' => $dados_json['nome'] ?? $escala['nome'] ?? '',
    'descricao' => $dados_json['descricao'] ?? $escala['descricao'] ?? '',
    'datas' => $datasNormalizadas,
    'escalas' => $escalaNormalizada
];

// Converter para JSON
$nomesJSON = json_encode($nomesPorFuncao, JSON_UNESCAPED_UNICODE);
$funcoesJSON = json_encode($funcoes, JSON_UNESCAPED_UNICODE);
$fotosJSON = json_encode($fotosPorId, JSON_UNESCAPED_UNICODE);
$dadosEscalaJSON = json_encode($dadosEscalaArray, JSON_UNESCAPED_UNICODE);

include('registroslog.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Editar Escala - <?php echo htmlspecialchars($escala['nome']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styledaescala.css?v=<?=time()?>">
    <style>
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .header-edicao {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header-edicao h1 { margin: 0; font-size: 28px; }
        .info-edicao {
            display: flex;
            gap: 20px;
            margin-top: 15px;
            font-size: 14px;
            flex-wrap: wrap;
        }
        .info-item {
            background: rgba(255,255,255,0.2);
            padding: 8px 15px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-voltar {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        .btn-voltar:hover { background: #5a6268; color: white; text-decoration: none; transform: translateY(-2px); }
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            flex-wrap: wrap;
        }
        .btn-atualizar {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-atualizar:hover { background: #218838; transform: translateY(-2px); }
        .btn-cancelar {
            background: #dc3545;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-cancelar:hover { background: #c82333; transform: translateY(-2px); }
        .btn-warning {
            background: #ffc107;
            color: #333;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-warning:hover { background: #e0a800; transform: translateY(-2px); }
        .btn-success {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-success:hover { background: #218838; transform: translateY(-2px); }
        .btn-primary {
            background: #007bff;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-primary:hover { background: #0056b3; transform: translateY(-2px); }
        .btn-auto {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-auto:hover { background: #5a6268; transform: translateY(-2px); }
        .form-group-edicao {
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        .form-group-edicao label { font-weight: bold; display: block; margin-bottom: 8px; color: #333; }
        .form-control-edicao {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 16px;
        }
        .alert-info-edicao {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .escala-status {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        .status-item {
            background: white;
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            flex: 1;
            text-align: center;
        }
        .status-item .label { font-size: 12px; color: #6c757d; margin-bottom: 5px; }
        .status-item .value { font-weight: bold; font-size: 16px; color: #333; }
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .controls {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        #escalaTable {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            overflow-x: auto;
            display: block;
        }
        #escalaTable th, #escalaTable td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            vertical-align: middle;
        }
        #escalaTable th {
            background: #f8f9fa;
            font-weight: bold;
            position: sticky;
            top: 0;
        }
        .remove-col {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            cursor: pointer;
            margin-left: 8px;
            font-size: 12px;
        }
        .remove-col:hover { background: #c82333; }
        .select-avatar {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 150px;
        }
        .avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
        }
        .footnote {
            font-size: 12px;
            color: #6c757d;
            margin-top: 20px;
            text-align: center;
        }
        .image-preview {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .image-preview.show { display: flex; }
        .image-preview-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 90%;
            max-height: 90%;
            overflow: auto;
            position: relative;
        }
        .close-preview {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 30px;
            cursor: pointer;
        }
        .preview-actions { margin-top: 20px; text-align: center; }
        .pill {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            background: #e9ecef;
        }
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 9999;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .toast.show { transform: translateX(0); }
        .progress-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            color: white;
        }
        @media (max-width: 768px) {
            .action-buttons { flex-direction: column; }
            .info-edicao { flex-direction: column; gap: 10px; }
            .escala-status { flex-direction: column; }
            .controls { flex-direction: column; align-items: stretch; }
            .select-avatar { min-width: auto; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="navegacao">
        <?php include("navegacao.php")?>
    </div>
    <br><br>
    
    <a href="consultaescala" class="btn-voltar">
        <i class="fas fa-arrow-left"></i> Voltar para Consulta
    </a>
    
    <div class="header-edicao">
        <h1><i class="fas fa-edit"></i> Editar Escala</h1>
        <div class="info-edicao">
            <div class="info-item"><i class="fas fa-table"></i> <?php echo htmlspecialchars($nomes_tabelas[$tabela]); ?></div>
            <div class="info-item"><i class="fas fa-tag"></i> ID: <?php echo $id; ?></div>
            <div class="info-item"><i class="fas fa-calendar"></i> Criada em: <?php echo date('d/m/Y H:i', strtotime($escala['data_criacao'])); ?></div>
        </div>
    </div>
    
    <div class="escala-status">
        <div class="status-item"><div class="label">Datas na Escala</div><div class="value" id="contador-datas">0</div></div>
        <div class="status-item"><div class="label">Músicos Selecionados</div><div class="value" id="contador-musicos">0</div></div>
        <div class="status-item"><div class="label">Status</div><div class="value"><span id="status-geral">Carregando...</span></div></div>
    </div>
    
    <div class="alert-info-edicao">
        <i class="fas fa-info-circle"></i> 
        <div><strong>Atenção:</strong> Editando escala <strong>"<?php echo htmlspecialchars($escala['nome']); ?>"</strong>. <br><small>Para remover uma data, clique no botão ✖ ao lado dela.</small></div>
    </div>
    
    <div class="content">
        <div class="form-group-edicao">
            <label for="escalaName"><i class="fas fa-heading"></i> Nome da Escala:</label>
            <input type="text" id="escalaName" class="form-control-edicao" 
                   value="<?php echo htmlspecialchars($dadosEscalaArray['nome']); ?>"
                   placeholder="Ex: Escala Janeiro 2024">
        </div>
        
        <div class="form-group-edicao">
            <label for="escalaDescricao"><i class="fas fa-align-left"></i> Descrição (opcional):</label>
            <textarea id="escalaDescricao" class="form-control-edicao" 
                      rows="3" placeholder="Descrição da escala..."><?php echo htmlspecialchars($dadosEscalaArray['descricao']); ?></textarea>
        </div>
        
        <div class="card">
            <div class="card-title"><i class="fas fa-calendar-plus"></i> Adicionar Nova Data</div>
            <div class="controls">
                <label for="datePicker">Selecione uma data:</label>
                <input type="date" id="datePicker" />
                <button type="button" id="btnAdd" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Adicionar Data</button>
                <button type="button" id="btnAutoFill" class="btn btn-auto"><i class="fas fa-magic"></i> Preenchimento Automático</button>
                <span id="status" class="pill"></span>
            </div>
        </div>
        
        <div style="overflow-x: auto;">
            <table id="escalaTable">
                <thead><tr id="headerRow"><th>Escala <?php echo htmlspecialchars($nome_tabela); ?></th></tr></thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>
        
        <div class="action-buttons">
            <button type="button" id="btnValidate" class="btn-warning"><i class="fas fa-check"></i> Validar Escala</button>
            <button type="button" id="btnExport" class="btn-success"><i class="fas fa-download"></i> Exportar PDF</button>
            <button type="button" id="btnPreview" class="btn-primary"><i class="fas fa-eye"></i> Visualizar</button>
            <button type="button" id="btnUpdate" class="btn-atualizar"><i class="fas fa-save"></i> Salvar Alterações</button>
            <button type="button" id="btnCancel" class="btn-cancelar"><i class="fas fa-times"></i> Cancelar</button>
        </div>
        
        <p class="footnote"><i class="fas fa-lightbulb"></i> Dica: Você pode adicionar novas datas ou alterar os músicos nas datas existentes.</p>
    </div>
</div>

<!-- Modal para visualizar -->
<div id="imagePreview" class="image-preview">
    <div class="image-preview-content">
        <div class="close-preview">&times;</div>
        <h3>Prévia da Escala</h3>
        <div id="previewContainer"></div>
        <div class="preview-actions">
            <button id="btnDownloadPreview" class="btn-success"><i class="fas fa-download"></i> Baixar Imagem</button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
// ============================================
// DADOS DO PHP
// ============================================
const nomesPorFuncao = <?= $nomesJSON ?>;
const funcoes = <?= $funcoesJSON ?>;
const fotosPorId = <?= $fotosJSON ?>;
const dadosEscala = <?= $dadosEscalaJSON ?>;
const tabelaAtual = '<?php echo $escala['tipo']; ?>';
const idEscala = <?php echo $id; ?>;

console.log('=== DADOS CARREGADOS ===');
console.log('Funções:', funcoes);
console.log('Dados da Escala:', dadosEscala);

// ============================================
// VARIÁVEIS GLOBAIS
// ============================================
const tbody = document.getElementById('tableBody');
const headerRow = document.getElementById('headerRow');
const statusEl = document.getElementById('status');
const contadorDatas = document.getElementById('contador-datas');
const contadorMusicos = document.getElementById('contador-musicos');
const statusGeral = document.getElementById('status-geral');
const previewEl = document.getElementById('imagePreview');
const previewContainer = document.getElementById('previewContainer');
let addedDates = new Set();
let modificado = false;
let currentBlob = null;
let currentFileName = '';

// ============================================
// FUNÇÕES AUXILIARES
// ============================================
function showStatus(msg, isError = false) {
    statusEl.textContent = msg;
    statusEl.style.display = 'inline-block';
    statusEl.style.background = isError ? 'rgba(247, 37, 133, 0.2)' : 'rgba(76, 201, 240, 0.2)';
    setTimeout(() => statusEl.style.display = 'none', 3000);
}

function showToast(msg, type = 'success') {
    let toastEl = document.getElementById('toast');
    if (!toastEl) {
        toastEl = document.createElement('div');
        toastEl.id = 'toast';
        toastEl.className = 'toast';
        toastEl.innerHTML = '<i class="fas fa-check-circle"></i><span id="toastMessage"></span>';
        document.body.appendChild(toastEl);
    }
    const toastMsg = document.getElementById('toastMessage');
    const icon = toastEl.querySelector('i');
    toastMsg.textContent = msg;
    if (type === 'error') {
        icon.className = 'fas fa-exclamation-circle';
        toastEl.style.background = 'linear-gradient(135deg, #f72585 0%, #b5179e 100%)';
    } else if (type === 'warning') {
        icon.className = 'fas fa-exclamation-triangle';
        toastEl.style.background = 'linear-gradient(135deg, #f39c12 0%, #e67e22 100%)';
    } else {
        icon.className = 'fas fa-check-circle';
        toastEl.style.background = 'linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%)';
    }
    toastEl.classList.add('show');
    setTimeout(() => toastEl.classList.remove('show'), 3000);
}

function showProgress(message, percent) {
    let overlay = document.getElementById('progressOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'progressOverlay';
        overlay.className = 'progress-overlay';
        overlay.innerHTML = `
            <div class="progress-message" style="margin-bottom:20px;font-size:18px;">${message}</div>
            <div class="progress-bar" style="width:300px;height:20px;background:#444;border-radius:10px;overflow:hidden;">
                <div class="progress-fill" style="width:${percent}%;height:100%;background:linear-gradient(90deg,#3498db,#2ecc71);transition:width 0.3s;"></div>
            </div>
            <div class="progress-percent" style="margin-top:10px;font-size:14px;">${percent}%</div>
        `;
        document.body.appendChild(overlay);
    } else {
        overlay.querySelector('.progress-message').textContent = message;
        overlay.querySelector('.progress-fill').style.width = percent + '%';
        overlay.querySelector('.progress-percent').textContent = percent + '%';
    }
}

function hideProgress() {
    const overlay = document.getElementById('progressOverlay');
    if (overlay) {
        overlay.style.opacity = '0';
        setTimeout(() => { if (overlay.parentNode) overlay.parentNode.removeChild(overlay); }, 300);
    }
}

function obterIniciais(nome) {
    if (!nome) return '?';
    return nome.trim().split(/\s+/).map(p => p.charAt(0).toUpperCase()).join('');
}

function getFoto(id) {
    if (!id) return 'assets/avatar-default.png';
    return fotosPorId[id] || 'assets/avatar-default.png';
}

function gerarAvatarPlaceholder(nome) {
    const iniciais = obterIniciais(nome);
    const canvas = document.createElement('canvas');
    canvas.width = 40;
    canvas.height = 40;
    const ctx = canvas.getContext('2d');
    ctx.fillStyle = '#4f46e5';
    ctx.fillRect(0, 0, 40, 40);
    ctx.fillStyle = '#ffffff';
    ctx.font = 'bold 16px Arial';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(iniciais, 20, 20);
    return canvas.toDataURL('image/png');
}

function formatISOToBRcomSemana(isoDate) {
    if (!isoDate) return '';
    const diasSemana = ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"];
    const partes = isoDate.split('-');
    if (partes.length !== 3) return isoDate;
    const data = new Date(partes[0], parseInt(partes[1])-1, partes[2]);
    if (isNaN(data.getTime())) return isoDate;
    return `${String(data.getDate()).padStart(2,'0')}/${String(data.getMonth()+1).padStart(2,'0')}/${data.getFullYear()} (${diasSemana[data.getDay()]})`;
}

function atualizarContadores() {
    const dates = headerRow.querySelectorAll('th[data-iso]');
    contadorDatas.textContent = dates.length;
    const selects = document.querySelectorAll('#escalaTable select');
    let musicosSelecionados = 0;
    selects.forEach(select => { if (select.value) musicosSelecionados++; });
    contadorMusicos.textContent = musicosSelecionados;
    statusGeral.textContent = modificado ? "Modificado (não salvo)" : "Salvo";
    statusGeral.style.color = modificado ? "#f39c12" : "#27ae60";
}

function getPdfFileName() {
    const nomeEscala = document.getElementById('escalaName').value;
    if (!nomeEscala.trim()) {
        const ts = new Date();
        return `escalas_${tabelaAtual}_${idEscala}_${ts.getFullYear()}${String(ts.getMonth()+1).padStart(2,'0')}${String(ts.getDate()).padStart(2,'0')}.pdf`;
    }
    let fileName = nomeEscala.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-zA-Z0-9\s_-]/g, '').replace(/\s+/g, '_').toLowerCase();
    return fileName + '.pdf';
}

// ============================================
// CRIAÇÃO DE SELECT COM AVATAR
// ============================================
function makeSelectWithAvatar(valorInicial = null) {
    const wrap = document.createElement('div');
    wrap.className = 'select-avatar';
    const img = document.createElement('img');
    img.className = 'avatar';
    img.alt = 'foto';
    img.onerror = function() { this.src = 'assets/avatar-default.png'; };
    const sel = document.createElement('select');
    wrap.appendChild(img);
    wrap.appendChild(sel);
    sel.addEventListener('change', () => {
        img.src = getFoto(sel.value);
        modificado = true;
        atualizarContadores();
    });
    wrap._select = sel;
    wrap._img = img;
    if (valorInicial) {
        sel.value = valorInicial;
        img.src = getFoto(valorInicial);
    }
    return wrap;
}

function createSelect(funcao, isoDate, valorSalvoId = null) {
    const wrap = makeSelectWithAvatar(valorSalvoId);
    const sel = wrap._select;
    sel.name = `escala[${funcao}][${isoDate}]`;
    const opt0 = document.createElement('option');
    opt0.value = '';
    opt0.textContent = '-- Selecione --';
    sel.appendChild(opt0);
    const lista = nomesPorFuncao[funcao] || [];
    lista.forEach(pessoa => {
        const opt = document.createElement('option');
        opt.value = pessoa.id;
        opt.textContent = pessoa.nome;
        sel.appendChild(opt);
    });
    if (valorSalvoId && sel.querySelector(`option[value="${valorSalvoId}"]`)) {
        sel.value = valorSalvoId;
        wrap._img.src = getFoto(valorSalvoId);
    }
    return wrap;
}

// ============================================
// CRIAÇÃO DAS LINHAS DA TABELA
// ============================================
funcoes.forEach(funcao => {
    const tr = document.createElement('tr');
    tr.dataset.funcao = funcao;
    const td = document.createElement('td');
    td.innerHTML = `<strong>${funcao}</strong>`;
    tr.appendChild(td);
    tbody.appendChild(tr);
});

// ============================================
// CARREGAR ESCALA EXISTENTE
// ============================================
function carregarEscalaExistente() {
    console.log('Carregando escala existente...');
    if (!dadosEscala || !dadosEscala.datas || dadosEscala.datas.length === 0) {
        console.warn('Nenhuma escala para carregar');
        return;
    }
    dadosEscala.datas.forEach(dataObj => {
        let iso = dataObj.iso || dataObj;
        let dataFormatada = dataObj.formatada || formatISOToBRcomSemana(iso);
        if (!iso || addedDates.has(iso)) return;
        
        const th = document.createElement('th');
        th.dataset.iso = iso;
        const span = document.createElement('span');
        span.textContent = dataFormatada;
        const btn = document.createElement('button');
        btn.textContent = "✖";
        btn.className = "remove-col";
        btn.onclick = () => { if (confirm('Remover esta data?')) removeColumn(iso); };
        th.appendChild(span);
        th.appendChild(btn);
        headerRow.appendChild(th);
        
        [...tbody.rows].forEach(tr => {
            const funcao = tr.dataset.funcao;
            const td = document.createElement('td');
            let valorSalvoId = null;
            if (dadosEscala.escalas && dadosEscala.escalas[funcao] && dadosEscala.escalas[funcao][iso]) {
                const registro = dadosEscala.escalas[funcao][iso];
                valorSalvoId = typeof registro === 'object' ? (registro.id || null) : registro;
            }
            td.appendChild(createSelect(funcao, iso, valorSalvoId));
            tr.appendChild(td);
        });
        addedDates.add(iso);
    });
    atualizarContadores();
    console.log('Escala carregada! Datas:', Array.from(addedDates));
}

// ============================================
// ADICIONAR/REMOVER COLUNA
// ============================================
function addColumn() {
    const iso = document.getElementById('datePicker').value;
    if (!iso) { showStatus('Escolha uma data.', true); return; }
    if (addedDates.has(iso)) { showStatus('Data já existe.', true); return; }
    
    const th = document.createElement('th');
    th.dataset.iso = iso;
    const span = document.createElement('span');
    span.textContent = formatISOToBRcomSemana(iso);
    const btn = document.createElement('button');
    btn.textContent = "✖";
    btn.className = "remove-col";
    btn.onclick = () => { if (confirm('Remover?')) removeColumn(iso); };
    th.appendChild(span);
    th.appendChild(btn);
    headerRow.appendChild(th);
    
    [...tbody.rows].forEach(tr => {
        const funcao = tr.dataset.funcao;
        const td = document.createElement('td');
        td.appendChild(createSelect(funcao, iso));
        tr.appendChild(td);
    });
    
    addedDates.add(iso);
    modificado = true;
    atualizarContadores();
    document.getElementById('datePicker').value = '';
    showStatus(`Data ${formatISOToBRcomSemana(iso)} adicionada.`);
}

function removeColumn(iso) {
    const ths = [...headerRow.children];
    const index = ths.findIndex(th => th.dataset.iso === iso);
    if (index === -1) return;
    ths[index].remove();
    [...tbody.rows].forEach(tr => { tr.cells[index].remove(); });
    addedDates.delete(iso);
    modificado = true;
    atualizarContadores();
    showStatus(`Data removida.`);
}

function autoFillTable() {
    if (addedDates.size === 0) { showStatus('Adicione pelo menos uma data.', true); return; }
    const selects = document.querySelectorAll('#escalaTable select');
    let preenchidos = 0;
    selects.forEach(select => {
        if (select.options.length > 1 && !select.value) {
            const randomIndex = Math.floor(Math.random() * (select.options.length - 1)) + 1;
            select.selectedIndex = randomIndex;
            select.dispatchEvent(new Event("change"));
            preenchidos++;
        }
    });
    if (preenchidos > 0) {
        modificado = true;
        atualizarContadores();
        showStatus(`${preenchidos} campos preenchidos!`);
    } else {
        showStatus('Todos já preenchidos.', true);
    }
}

// ============================================
// COLETAR DADOS DA ESCALA
// ============================================
function getEscalaData() {
    const data = {
        nome: document.getElementById('escalaName').value,
        descricao: document.getElementById('escalaDescricao').value,
        escala: {}
    };
    const headerCells = document.querySelectorAll('#headerRow th[data-iso]');
    const linhas = document.querySelectorAll('#tableBody tr');
    
    headerCells.forEach(th => {
        const iso = th.dataset.iso;
        data.escala[iso] = {};
        linhas.forEach(tr => {
            const funcao = tr.dataset.funcao;
            const index = Array.from(headerCells).indexOf(th);
            const td = tr.cells[index + 1];
            if (td) {
                const select = td.querySelector('select');
                if (select && select.value) {
                    const voluntarioId = parseInt(select.value);
                    const voluntario = (nomesPorFuncao[funcao] || []).find(v => v.id === voluntarioId);
                    data.escala[iso][funcao] = {
                        voluntario_id: voluntarioId,
                        nome: voluntario ? voluntario.nome : '',
                        cadastroadm_id: 0
                    };
                } else {
                    data.escala[iso][funcao] = null;
                }
            }
        });
        if (Object.keys(data.escala[iso]).length === 0) delete data.escala[iso];
    });
    return data;
}

// ============================================
// FUNÇÕES DE PDF E VISUALIZAÇÃO
// ============================================
function replaceSelectsWithText(clonedTable) {
    clonedTable.querySelectorAll('.avatar').forEach(img => img.remove());
    const selectsOrig = [...document.querySelectorAll('#escalaTable select')];
    const selectsClone = [...clonedTable.querySelectorAll('select')];
    selectsClone.forEach((selClone, i) => {
        const real = selectsOrig[i];
        if (!real) return;
        const valor = real.value;
        const nome = valor ? (real.options[real.selectedIndex]?.text || "-- Selecione --") : "-- Selecione --";
        let foto = getFoto(valor);
        if (!foto || foto === 'assets/avatar-default.png') foto = gerarAvatarPlaceholder(nome);
        const wrap = document.createElement("div");
        wrap.style.display = "flex";
        wrap.style.alignItems = "center";
        wrap.style.gap = "8px";
        const img = document.createElement("img");
        img.src = foto;
        img.style.width = "32px";
        img.style.height = "32px";
        img.style.borderRadius = "50%";
        img.style.objectFit = "cover";
        const span = document.createElement("span");
        span.textContent = nome;
        wrap.appendChild(img);
        wrap.appendChild(span);
        selClone.replaceWith(wrap);
    });
}

async function gerarImagemBlob() {
    return new Promise(resolve => {
        if (document.activeElement) document.activeElement.blur();
        const original = document.getElementById('escalaTable');
        const clone = original.cloneNode(true);
        replaceSelectsWithText(clone);
        const container = document.createElement("div");
        container.style.position = "fixed";
        container.style.left = "-9999px";
        container.style.top = "0";
        container.style.background = "#fff";
        container.appendChild(clone);
        document.body.appendChild(container);
        setTimeout(() => {
            html2canvas(container, { scale: 2, useCORS: true }).then(canvas => {
                canvas.toBlob(blob => {
                    document.body.removeChild(container);
                    resolve({ canvas, blob });
                });
            });
        }, 200);
    });
}

async function gerarPdfSimples() {
    const { canvas } = await gerarImagemBlob();
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF("l", "mm", "a4");
    const pageWidth = pdf.internal.pageSize.getWidth();
    const margin = 10;
    const usable = pageWidth - (margin * 2);
    const img = canvas.toDataURL("image/png", 1.0);
    const imgH = (canvas.height * usable) / canvas.width;
    pdf.addImage(img, "PNG", margin, 20, usable, imgH);
    return { pdf, blob: pdf.output("blob"), fileName: getPdfFileName() };
}

async function previewImage() {
    const btn = document.getElementById('btnPreview');
    const originalText = btn.innerHTML;
    try {
        btn.innerHTML = '<span class="spinner"></span> Gerando...';
        btn.disabled = true;
        const { canvas, blob } = await gerarImagemBlob();
        currentBlob = blob;
        previewContainer.innerHTML = '';
        const img = document.createElement('img');
        img.src = canvas.toDataURL('image/png');
        img.style.maxWidth = '100%';
        previewContainer.appendChild(img);
        previewEl.classList.add('show');
        showStatus('Prévia gerada!');
    } catch (e) {
        console.error('Erro:', e);
        alert('Erro ao gerar prévia: ' + e.message);
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

async function exportPdf() {
    const btn = document.getElementById('btnExport');
    const originalText = btn.innerHTML;
    try {
        btn.innerHTML = '<span class="spinner"></span> Exportando...';
        btn.disabled = true;
        const { blob, fileName } = await gerarPdfSimples();
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = fileName;
        document.body.appendChild(a);
        a.click();
        a.remove();
        URL.revokeObjectURL(url);
        showStatus('PDF exportado!');
    } catch (e) {
        console.error('Erro:', e);
        alert('Erro ao exportar PDF: ' + e.message);
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

// ============================================
// VALIDAR ESCALA
// ============================================
function validarEscala() {
    const escalaData = getEscalaData();
    if (Object.keys(escalaData.escala).length === 0) {
        showToast('Adicione ao menos uma data para validar.', 'error');
        return;
    }
    
    const validacaoPorTabela = {
        'escalas_louvor': 'validar_escalalouvor.php'
    };
    
    const arquivoValidacao = 'validar_escalalouvor.php';
    if (!arquivoValidacao) {
        showToast('Validação não disponível para esta tabela.', 'warning');
        return;
    }
    
    const btn = document.getElementById('btnValidate');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner"></span> Validando...';
    btn.disabled = true;
    
    const formData = new FormData();
    formData.append('acao', 'validar_escala');
    formData.append('tabela', tabelaAtual);
    formData.append('dados', JSON.stringify(escalaData));
    
    fetch(arquivoValidacao, { method: 'POST', body: formData })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                showToast(res.message || 'Escala validada com sucesso!', 'success');
                let mensagem = '✅ Escala validada!\n\n';
                mensagem += `📅 Total de datas: ${Object.keys(escalaData.escala).length}\n`;
                mensagem += `🎵 Funções: ${funcoes.length}\n`;
                if (res.estatisticas) {
                    mensagem += '\n📈 Estatísticas:\n';
                    Object.entries(res.estatisticas).forEach(([data, qtd]) => {
                        mensagem += `  • ${data}: ${qtd} pessoa(s)\n`;
                    });
                }
                alert(mensagem);
            } else {
                showToast('Conflitos encontrados!', 'error');
                let mensagem = '⚠️ CONFLITOS:\n\n';
                mensagem += res.message || 'Verifique a escala.';
                if (res.conflitos && res.conflitos.length > 0) {
                    mensagem += '\n\n🔴 Conflitos:\n' + res.conflitos.map((c, i) => `${i+1}. ${c}`).join('\n');
                }
                alert(mensagem);
            }
        })
        .catch(err => {
            console.error('Erro:', err);
            showToast('Erro ao validar escala.', 'error');
            alert('Erro ao validar: ' + err.message);
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
}

// ============================================
// SALVAR ESCALA
async function atualizarEscala() {
    const escalaData = getEscalaData();

    if (!escalaData.nome.trim()) {
        alert('Informe um nome para a escala.');
        return;
    }

    if (Object.keys(escalaData.escala).length === 0) {
        alert('Adicione pelo menos uma data.');
        return;
    }

    if (!confirm('Salvar alterações?')) return;

    const btn = document.getElementById('btnUpdate');
    const originalText = btn.innerHTML;

    btn.innerHTML = '<span class="spinner"></span> Salvando...';
    btn.disabled = true;

    try {
        // 🔥 GERAR PDF AUTOMÁTICO
        showProgress("Gerando PDF...", 30);

        const { blob, fileName } = await gerarPdfSimples();

        // 🔥 ENVIAR COM PDF
        const formData = new FormData();
        formData.append('acao', 'atualizar_escala');
        formData.append('tabela', tabelaAtual);
        formData.append('id', idEscala);
        formData.append('dados', JSON.stringify(escalaData));
        formData.append('pdf', blob, fileName);

        showProgress("Enviando dados...", 70);

        const response = await fetch('salvar_escala_editada.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        showProgress("Finalizando...", 100);

        if (result.success) {
            modificado = false;
            showToast('Escala salva com PDF atualizado!');
            setTimeout(() => {
                window.history.back();
            }, 1000);
        } else {
            alert('❌ Erro: ' + (result.message || 'Erro desconhecido'));
        }

    } catch (error) {
        console.error('Erro:', error);
        alert('❌ Erro ao salvar: ' + error.message);
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
        setTimeout(() => hideProgress(), 500);
    }
}
// ============================================

// ============================================
// EVENT LISTENERS
// ============================================
document.getElementById('btnAdd').addEventListener('click', addColumn);
document.getElementById('btnAutoFill').addEventListener('click', autoFillTable);
document.getElementById('btnUpdate').addEventListener('click', atualizarEscala);
document.getElementById('btnValidate').addEventListener('click', validarEscala);
document.getElementById('btnExport').addEventListener('click', exportPdf);
document.getElementById('btnPreview').addEventListener('click', previewImage);
document.getElementById('btnCancel').addEventListener('click', () => {
    if (modificado && !confirm('Alterações não salvas. Cancelar?')) return;
    window.history.back();
});
document.getElementById('datePicker').addEventListener('keydown', e => { if (e.key === 'Enter') addColumn(); });
document.getElementById('escalaName').addEventListener('input', () => { modificado = true; atualizarContadores(); });
document.getElementById('escalaDescricao').addEventListener('input', () => { modificado = true; atualizarContadores(); });
document.querySelector('.close-preview').addEventListener('click', () => previewEl.classList.remove('show'));
previewEl.addEventListener('click', (e) => { if (e.target === previewEl) previewEl.classList.remove('show'); });
document.getElementById('btnDownloadPreview').addEventListener('click', () => {
    if (currentBlob) {
        const url = URL.createObjectURL(currentBlob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'preview_escala.png';
        a.click();
        URL.revokeObjectURL(url);
    }
});

window.addEventListener('beforeunload', e => { if (modificado) { e.preventDefault(); e.returnValue = ''; } });
window.addEventListener('DOMContentLoaded', () => { carregarEscalaExistente(); });
</script>

</body>
</html>