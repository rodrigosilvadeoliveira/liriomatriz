<?php
// =====================================================
// TRANSACOES.PHP - VERSÃO CORRIGIDA (COMPETÊNCIA)
// =====================================================

ob_start();
date_default_timezone_set('America/Sao_Paulo');

include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');

include_once('config.php');
include_once('config_language.php');

if ((!isset($_SESSION['usuario']) == true) && ($_SESSION['senha']) == true) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
    ob_end_flush();
    exit;
}

$nome_usuario= $_SESSION['nome'];
$logado = $_SESSION['usuario'];
$igreja = $_SESSION['igreja_id'];

// =====================================================
// CORREÇÃO: Atualizar competências inválidas
// =====================================================
$sqlCorrigir = "UPDATE transacoes_financeiras 
                SET competencia = data 
                WHERE competencia IS NULL OR competencia = '0000-00-00'";
$conexao->query($sqlCorrigir);

// =====================================================
// SALVAR TRANSAÇÃO
// =====================================================

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = $_POST['data'] ?? null;
    $descricao = trim($_POST['descricao'] ?? '');
    $valor = $_POST['valor'] ?? '0';
    $membro_id = intval($_POST['membro_id'] ?? 0);
    $categoria_id = intval($_POST['categoria_id'] ?? 0);
    $competencia = $_POST['competencia'] ?? null;

    // Se competencia for mês (2025-01), converter para primeiro dia do mês
    if (!empty($competencia) && strlen($competencia) == 7) {
        $competencia = $competencia . '-01';
    }

    if (empty($competencia)) {
        $competencia = $data;
    }

    $sqlTipo = "SELECT tipo FROM categorias_financeiras WHERE id = ?";
    $stmtTipo = $conexao->prepare($sqlTipo);
    $stmtTipo->bind_param("i", $categoria_id);
    $stmtTipo->execute();
    $resultTipo = $stmtTipo->get_result();
    $tipoCategoria = $resultTipo->fetch_assoc()['tipo'] ?? null;

    if (empty($competencia) || empty($valor) || $categoria_id == 0) {
        $_SESSION['mensagem'] = "Preencha todos os campos obrigatórios.";
        header("Location: transacoes.php");
        ob_end_flush();
        exit;
    }

    // Se não selecionar membro, salva como NULL ou 0
if ($membro_id <= 0) {
    $membro_id = null;
}

    if ($tipoCategoria == 'despesa') {
        $membro_id = 0;
    }

    $valor = preg_replace('/[^\\d,]/', '', $valor);
    $valor = str_replace('.', '', $valor);
    $valor = str_replace(',', '.', $valor);
    $valor = floatval($valor);

    $sql = "INSERT INTO transacoes_financeiras (data, descricao, valor, membro_id, categoria_id, competencia, igreja_id, nome_usuario)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param(
    "ssdiisis",
    $data,
    $descricao,
    $valor,
    $membro_id,
    $categoria_id,
    $competencia,
    $igreja,
    $nome_usuario
);

    $acao = $_POST['acao'] ?? 'fechar';

if ($stmt->execute()) {

    $_SESSION['mensagem'] =
        ($tipoCategoria == 'receita')
        ? "Receita cadastrada com sucesso."
        : "Despesa cadastrada com sucesso.";

    if ($acao === 'novo') {
        header("Location: transacoes.php?abrirModal=" . $tipoCategoria);
    } else {
        header("Location: transacoes.php");
    }

    exit;
}
}
// =====================================================
// BUSCAR DADOS PARA O FORMULÁRIO
// =====================================================

$sqlMembros = "SELECT id, nome, sobrenome, foto FROM membros WHERE igreja_id = ? ORDER BY nome";
$stmtMembros = $conexao->prepare($sqlMembros);
$stmtMembros->bind_param("i", $igreja);
$stmtMembros->execute();
$resultMembros = $stmtMembros->get_result();

$sqlCategoriasReceita = "SELECT id, nome FROM categorias_financeiras WHERE igreja_id = ? AND tipo = 'receita' ORDER BY nome";
$stmtCategoriasReceita = $conexao->prepare($sqlCategoriasReceita);
$stmtCategoriasReceita->bind_param("i", $igreja);
$stmtCategoriasReceita->execute();
$resultCategoriasReceita = $stmtCategoriasReceita->get_result();

$sqlCategoriasDespesa = "SELECT id, nome FROM categorias_financeiras WHERE igreja_id = ? AND tipo = 'despesa' ORDER BY nome";
$stmtCategoriasDespesa = $conexao->prepare($sqlCategoriasDespesa);
$stmtCategoriasDespesa->bind_param("i", $igreja);
$stmtCategoriasDespesa->execute();
$resultCategoriasDespesa = $stmtCategoriasDespesa->get_result();

$sqlFiltroCategorias = "SELECT id, nome FROM categorias_financeiras WHERE igreja_id = ? ORDER BY nome";
$stmtFiltroCat = $conexao->prepare($sqlFiltroCategorias);
$stmtFiltroCat->bind_param("i", $igreja);
$stmtFiltroCat->execute();
$resultFiltroCategorias = $stmtFiltroCat->get_result();

// =====================================================
// FILTROS POR COMPETÊNCIA
// =====================================================

$competencia_inicio = $_GET['competencia_inicio'] ?? date('Y-m-01');
$competencia_fim = $_GET['competencia_fim'] ?? date('Y-m-t');

// Se veio no formato mês (YYYY-MM), converter para data completa
if (isset($_GET['competencia_inicio']) && strlen($_GET['competencia_inicio']) == 7) {
    $competencia_inicio = $_GET['competencia_inicio'] . '-01';
}
if (isset($_GET['competencia_fim']) && strlen($_GET['competencia_fim']) == 7) {
    $competencia_fim = $_GET['competencia_fim'] . '-' . date('t', strtotime($_GET['competencia_fim'] . '-01'));
}

$categoria_filtro = $_GET['categoria_id'] ?? '';

// =====================================================
// TOTALIZADORES POR COMPETÊNCIA
// =====================================================

$sqlTotais = "SELECT 
    SUM(CASE WHEN c.tipo = 'receita' THEN t.valor ELSE 0 END) AS total_recebido,
    SUM(CASE WHEN c.tipo = 'despesa' THEN t.valor ELSE 0 END) AS total_pago
FROM transacoes_financeiras t
LEFT JOIN categorias_financeiras c ON c.id = t.categoria_id
WHERE t.igreja_id = ? 
AND t.competencia IS NOT NULL
AND t.competencia != '0000-00-00'
AND t.competencia BETWEEN ? AND ?";

$params = [$igreja, $competencia_inicio, $competencia_fim];
$types = "iss";

if (!empty($categoria_filtro)) {
    $sqlTotais .= " AND t.categoria_id = ?";
    $params[] = intval($categoria_filtro);
    $types .= "i";
}

$stmtTotais = $conexao->prepare($sqlTotais);
$stmtTotais->bind_param($types, ...$params);
$stmtTotais->execute();
$resultTotais = $stmtTotais->get_result();
$totais = $resultTotais->fetch_assoc();

$totalRecebido = number_format($totais['total_recebido'] ?? 0, 2, ',', '.');
$totalPago = number_format($totais['total_pago'] ?? 0, 2, ',', '.');

// =====================================================
// LISTAGEM POR COMPETÊNCIA
// =====================================================

$sqlLista = "SELECT 
    t.id,
    t.data,
    t.descricao,
    t.valor,
    t.competencia,
    t.nome_usuario,
    c.nome AS categoria,
    c.tipo,
    CONCAT(m.nome, ' ', m.sobrenome) AS membro
FROM transacoes_financeiras t
LEFT JOIN categorias_financeiras c ON c.id = t.categoria_id
LEFT JOIN membros m ON m.id = t.membro_id
WHERE t.igreja_id = ? 
AND t.competencia IS NOT NULL
AND t.competencia != '0000-00-00'
AND t.competencia BETWEEN ? AND ?";

$paramsLista = [$igreja, $competencia_inicio, $competencia_fim];
$typesLista = "iss";

if (!empty($categoria_filtro)) {
    $sqlLista .= " AND t.categoria_id = ?";
    $paramsLista[] = intval($categoria_filtro);
    $typesLista .= "i";
}

$sqlLista .= " ORDER BY t.competencia DESC, t.id DESC LIMIT 100";

$stmtLista = $conexao->prepare($sqlLista);
$stmtLista->bind_param($typesLista, ...$paramsLista);
$stmtLista->execute();
$resultLista = $stmtLista->get_result();

// =====================================================
// DADOS PARA GRÁFICO (POR MÊS)
// =====================================================

$sqlGrafico = "SELECT 
    DATE_FORMAT(t.competencia, '%Y-%m') AS mes,
    SUM(CASE WHEN c.tipo = 'receita' THEN t.valor ELSE 0 END) AS total_receitas,
    SUM(CASE WHEN c.tipo = 'despesa' THEN t.valor ELSE 0 END) AS total_despesas
FROM transacoes_financeiras t
LEFT JOIN categorias_financeiras c ON c.id = t.categoria_id
WHERE t.igreja_id = ? 
AND t.competencia IS NOT NULL
AND t.competencia != '0000-00-00'
AND t.competencia BETWEEN ? AND ?
GROUP BY DATE_FORMAT(t.competencia, '%Y-%m')
ORDER BY mes DESC
LIMIT 12";

$stmtGrafico = $conexao->prepare($sqlGrafico);
$stmtGrafico->bind_param("iss", $igreja, $competencia_inicio, $competencia_fim);
$stmtGrafico->execute();
$resultGrafico = $stmtGrafico->get_result();

$dadosGrafico = [];
while ($row = $resultGrafico->fetch_assoc()) {
    $dadosGrafico[] = $row;
}

// Função segura para formatar data
function formatarCompetencia($data) {
    if (empty($data) || $data == '0000-00-00' || $data == '0000-00-00 00:00:00') {
        return 'Data não definida';
    }
    $timestamp = strtotime($data);
    if ($timestamp === false || $timestamp < 0) {
        return 'Data inválida';
    }
    return date('m/Y', $timestamp);
}

function formatarData($data) {
    if (empty($data) || $data == '0000-00-00') {
        return '---';
    }
    $timestamp = strtotime($data);
    if ($timestamp === false) {
        return '---';
    }
    return date('d/m/Y', $timestamp);
}

function formatarValor($valor) {
    return number_format($valor, 2, ',', '.');
}

ob_clean();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transações Financeiras - Por Competência</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #eef1f4; padding: 20px; }
        .container { max-width: 1400px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        button { background: #43b39c; color: white; border: none; padding: 12px 25px; border-radius: 25px; cursor: pointer; font-size: 15px; }
        .btn-novo { background: #0d6efd; }
        .btn-cancelar { background: #6c757d; }
        .btn-danger { background: #dc3545; }
        .modal { display: none; position: fixed; z-index: 999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow: auto; }
        .modal-content { background: white; width: 90%; max-width: 650px; margin: 5% auto; padding: 25px; border-radius: 12px; margin-top: 11%;}
        label { font-weight: bold; margin-top: 12px; display: block; }
        input, textarea, select { width: 100%; padding: 9px; border-radius: 8px; border: 1px solid #ccc; margin-top: 5px; }
        .botoes { margin-top: 20px; text-align: right; display: flex; gap: 10px; justify-content: flex-end; }
        .mensagem { text-align: center; padding: 10px; border-radius: 5px; margin: 20px 0; }
        .mensagem.sucesso { background: #d4edda; color: #155724; }
        .mensagem.erro { background: #f8d7da; color: #721c24; }
        .custom-select { position: relative; }
        .dropdown-list { display: none; position: absolute; width: 100%; background: white; border: 1px solid #ccc; border-radius: 10px; max-height: 260px; overflow-y: auto; z-index: 1000; }
        .dropdown-item { display: flex; align-items: center; padding: 10px; cursor: pointer; transition: 0.2s; }
        .dropdown-item:hover { background: #f0f0f0; }
        .avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; margin-right: 10px; border: 2px solid #ddd; }
        .box-resumo { display: flex; gap: 20px; margin: 20px 0; flex-wrap: wrap; }
        .card-resumo { flex: 1; background: #f8f9fa; border-radius: 12px; padding: 20px; min-width: 200px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card-resumo h4 { margin: 0; font-size: 16px; color: #666; }
        .valor-resumo { font-size: 28px; font-weight: bold; margin-top: 10px; }
        .receita { color: #198754; }
        .despesa { color: #dc3545; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #f4f6f9; }
        .filtro-form { background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #dee2e6; }
        .filtro-group { display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end; }
        .filtro-item { flex: 1; min-width: 150px; }
        .botoes-filtro { display: flex; gap: 10px; margin-top: 20px; }
        .chart-container { margin: 30px 0; padding: 20px; background: #f8f9fa; border-radius: 10px; }
        .info-comp { font-size: 12px; color: #666; margin-top: 5px; }
        .badge { padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .badge-receita { background: #d4edda; color: #155724; }
        .badge-despesa { background: #f8d7da; color: #721c24; }
        .total-registros { margin-top: 10px; font-size: 14px; color: #666; }
        @media (max-width: 768px) { table { display: block; overflow-x: auto; } }
        /* Estilos para o dropdown com busca */
.custom-select {
    position: relative;
    width: 100%;
}

.search-box {
    position: sticky;
    top: 0;
    background: white;
    padding: 10px;
    border-bottom: 1px solid #e1e5e9;
    display: flex;
    align-items: center;
    gap: 10px;
    z-index: 1002;
}

.search-box i {
    color: #667eea;
    font-size: 1rem;
}

.search-box input {
    flex: 1;
    padding: 8px 12px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 0.9rem;
    outline: none;
    transition: all 0.3s ease;
}

.search-box input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.dropdown-list {
    display: none;
    position: absolute;
    width: 100%;
    background: white;
    border: 2px solid #e1e5e9;
    border-radius: 12px;
    max-height: 360px;
    overflow-y: auto;
    z-index: 1001;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    top: 100%;
    margin-top: 5px;
}

#membrosList {
    max-height: 300px;
    overflow-y: auto;
}

.dropdown-item {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    cursor: pointer;
    transition: all 0.2s ease;
    gap: 12px;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
}

.dropdown-item.selected {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.dropdown-item.selected span {
    color: white;
}

.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e1e5e9;
}

.dropdown-item span {
    flex: 1;
    font-size: 0.95rem;
    color: #333;
}

/* Mensagem sem resultados */
.no-results {
    padding: 20px;
    text-align: center;
    color: #6c757d;
}

.no-results i {
    font-size: 2rem;
    margin-bottom: 10px;
    opacity: 0.5;
}

/* Scrollbar personalizada */
.dropdown-list::-webkit-scrollbar,
#membrosList::-webkit-scrollbar {
    width: 8px;
}

.dropdown-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.dropdown-list::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 10px;
}

.dropdown-list::-webkit-scrollbar-thumb:hover {
    background: #5a67d8;
}
    </style>
</head>
<body>
        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <?php include("navegacao.php") ?>
    </nav>
<div class="container">
    <h2>📊 Transações Financeiras por Competência</h2>
    
    <div style="display: flex; gap: 10px; margin: 20px 0;">
        <button type="button" id="btnReceita" class="btn-novo">+ Adicionar Receita</button>
        <button type="button" id="btnDespesa" class="btn-danger">+ Adicionar Despesa</button>
    </div>

    <div class="box-resumo">
        <div class="card-resumo">
            <h4>💰 Total Recebido (Receita)</h4>
            <div class="valor-resumo receita">R$ <?php echo $totalRecebido; ?></div>
            <div class="info-comp">Período: <?php echo formatarCompetencia($competencia_inicio); ?> até <?php echo formatarCompetencia($competencia_fim); ?></div>
        </div>
        <div class="card-resumo">
            <h4>💸 Total Pago (Despesa)</h4>
            <div class="valor-resumo despesa">R$ <?php echo $totalPago; ?></div>
            <div class="info-comp">Período: <?php echo formatarCompetencia($competencia_inicio); ?> até <?php echo formatarCompetencia($competencia_fim); ?></div>
        </div>
        <div class="card-resumo">
            <h4>📈 Saldo do Período</h4>
            <div class="valor-resumo <?php echo (($totais['total_recebido'] ?? 0) - ($totais['total_pago'] ?? 0)) >= 0 ? 'receita' : 'despesa'; ?>">
                R$ <?php echo number_format(($totais['total_recebido'] ?? 0) - ($totais['total_pago'] ?? 0), 2, ',', '.'); ?>
            </div>
            <div class="info-comp">Receitas - Despesas</div>
        </div>
    </div>

    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="mensagem <?php echo strpos($_SESSION['mensagem'], 'Erro') !== false ? 'erro' : 'sucesso'; ?>">
            <?php echo htmlspecialchars($_SESSION['mensagem']); unset($_SESSION['mensagem']); ?>
        </div>
    <?php endif; ?>

    <!-- FILTRO POR COMPETÊNCIA -->
    <div class="filtro-form">
        <form method="GET" id="formFiltro">
            <div class="filtro-group">
                <div class="filtro-item">
                    <label>📅 Competência (Início)</label>
                    <input type="month" name="competencia_inicio" value="<?php echo substr($competencia_inicio, 0, 7); ?>">
                </div>
                <div class="filtro-item">
                    <label>📅 Competência (Fim)</label>
                    <input type="month" name="competencia_fim" value="<?php echo substr($competencia_fim, 0, 7); ?>">
                </div>
                <div class="filtro-item">
                    <label>🏷️ Categoria</label>
                    <select name="categoria_id">
                        <option value="">Todas as categorias</option>
                        <?php 
                        $resultFiltroCategorias->data_seek(0);
                        while ($fc = $resultFiltroCategorias->fetch_assoc()): ?>
                            <option value="<?php echo $fc['id']; ?>" <?php echo ($categoria_filtro == $fc['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($fc['nome']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="botoes-filtro">
                <button type="submit">🔍 Filtrar por Competência</button>
                <a href="transacoes.php"><button type="button" class="btn-cancelar">🗑️ Limpar Filtro</button></a>
            </div>
        </form>
    </div>

    <!-- GRÁFICO -->
    <?php if (count($dadosGrafico) > 0): ?>
    <div class="chart-container">
        <h3>📊 Evolução Mensal</h3>
        <canvas id="graficoFinanceiro" width="400" height="200"></canvas>
    </div>
    <?php endif; ?>

    <h3>📋 Transações (Ordenadas por Competência)</h3>
    <p><small>* Listando transações do período: <?php echo formatarCompetencia($competencia_inicio); ?> até <?php echo formatarCompetencia($competencia_fim); ?></small></p>
    
    <div class="total-registros">
        <strong>Total de registros:</strong> <?php echo $resultLista->num_rows; ?> transações encontradas
    </div>

    <table>
        <thead>
            <tr>
                <th>Competência</th>
                <th>Data Lançamento</th>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>Membro</th>
                <th>Valor</th>
                <th>Tipo</th>
                <th>Cadastrado por</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultLista->num_rows > 0): ?>
                <?php while ($t = $resultLista->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <strong><?php echo formatarCompetencia($t['competencia']); ?></strong>
                        </td>
                        <td><?php echo formatarData($t['data']); ?></td>
                        <td><?php echo htmlspecialchars($t['descricao']); ?></td>
                        <td><?php echo htmlspecialchars($t['categoria']); ?></td>
                        <td><?php echo htmlspecialchars($t['membro'] ?? '-'); ?></td>
                        <td><strong>R$ <?php echo formatarValor($t['valor']); ?></strong></td>
                        <td>
                            <?php if ($t['tipo'] == 'receita'): ?>
                                <span class="badge badge-receita">✅ Receita</span>
                            <?php else: ?>
                                <span class="badge badge-despesa">❌ Despesa</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($t['nome_usuario']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align:center; padding: 40px;">
                        📭 Nenhuma transação encontrada para o período de competência selecionado.
                        <br><br>
                        <small>Verifique se existem transações cadastradas no período de <?php echo formatarCompetencia($competencia_inicio); ?> até <?php echo formatarCompetencia($competencia_fim); ?></small>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- MODAL RECEITA -->
<div id="modalReceita" class="modal">
    <div class="modal-content">
        <h3>➕ Nova Receita</h3>
        <form method="POST" id="formReceita">
            <label>📅 Data de Lançamento *</label>
            <input type="date" name="data" required>
            
            <label>📝 Descrição</label>
            <textarea name="descricao" rows="3"></textarea>
            
            <label>💰 Valor *</label>
            <input type="text" name="valor" class="valor-input" placeholder="0,00" required>
            
            <label>👤 Recebido de *</label>
            <div class="custom-select">
                <input type="text" id="membro_busca" placeholder="Digite para buscar membro..." autocomplete="off">
                <input type="hidden" name="membro_id" id="membro_id">
                <div class="dropdown-list" id="dropdownMembros">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchMembro" placeholder="Buscar membro..." autocomplete="off">
                    </div>
                    <div id="membrosList">
                        <?php 
                        $resultMembros->data_seek(0);
                        while ($m = $resultMembros->fetch_assoc()): 
                            $caminho = (!empty($m['foto']) && file_exists("" . $m['foto'])) ? "" . $m['foto'] : "uploads/sem-foto.png";
                        ?>
                            <div class="dropdown-item" data-id="<?php echo $m['id']; ?>" data-nome="<?php echo htmlspecialchars($m['nome'] . ' ' . $m['sobrenome']); ?>" data-foto="<?php echo $caminho; ?>">
                                <img src="<?php echo $caminho; ?>" class="avatar" onerror="this.src='uploads/sem-foto.png'">
                                <span><?php echo htmlspecialchars($m['nome'] . ' ' . $m['sobrenome']); ?></span>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
            
            <label>🏷️ Categoria *</label>
            <select name="categoria_id" required>
                <option value="">Selecione</option>
                <?php 
                $resultCategoriasReceita->data_seek(0);
                while ($c = $resultCategoriasReceita->fetch_assoc()): ?>
                    <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['nome']); ?></option>
                <?php endwhile; ?>
            </select>
            
            <label>📅 Competência (Mês de Referência) *</label>
            <input type="month" name="competencia" required>
            <small>Mês em que a receita será contabilizada</small>
            
            <div class="botoes">
                <button type="submit" name="acao" value="novo" class="btn-novo">💾 Salvar e Novo</button>
                <button type="submit" name="acao" value="fechar">💾 Salvar e Fechar</button>
                <button type="button" class="btn-cancelar fechar-modal">❌ Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DESPESA -->
<div id="modalDespesa" class="modal">
    <div class="modal-content">
        <h3>➖ Nova Despesa</h3>
        <form method="POST" id="formDespesa">
            <label>📅 Data de Lançamento *</label>
            <input type="date" name="data" required>
            
            <label>📝 Descrição</label>
            <textarea name="descricao" rows="3"></textarea>
            
            <label>💰 Valor *</label>
            <input type="text" name="valor" class="valor-input" placeholder="0,00" required>
            
            <label>🏷️ Categoria *</label>
            <select name="categoria_id" required>
                <option value="">Selecione</option>
                <?php 
                $resultCategoriasDespesa->data_seek(0);
                while ($cd = $resultCategoriasDespesa->fetch_assoc()): ?>
                    <option value="<?php echo $cd['id']; ?>"><?php echo htmlspecialchars($cd['nome']); ?></option>
                <?php endwhile; ?>
            </select>
            
            <label>📅 Competência (Mês de Referência) *</label>
            <input type="month" name="competencia" required>
            <small>Mês em que a despesa será contabilizada</small>
            
            <div class="botoes">
                <button type="submit" name="acao" value="novo" class="btn-novo">💾 Salvar e Novo</button>
                <button type="submit" name="acao" value="fechar">💾 Salvar e Fechar</button>
                <button type="button" class="btn-cancelar fechar-modal">❌ Cancelar</button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ===============================
    // MODAIS
    // ===============================

    const modalReceita = document.getElementById('modalReceita');
    const modalDespesa = document.getElementById('modalDespesa');
    const btnReceita = document.getElementById('btnReceita');
    const btnDespesa = document.getElementById('btnDespesa');

    const hoje = new Date().toISOString().split('T')[0];
    const mesAtual = new Date().toISOString().slice(0, 7);

    function abrirModal(modal) {
        if (modal) {

            modal.style.display = 'block';

            const compInput = modal.querySelector('input[name="competencia"]');
            if (compInput && !compInput.value) {
                compInput.value = mesAtual;
            }

            const dataInput = modal.querySelector('input[name="data"]');
            if (dataInput && !dataInput.value) {
                dataInput.value = hoje;
            }

        }
    }

    function fecharModal(modal) {
        if (modal) modal.style.display = 'none';
    }

    if (btnReceita) {
        btnReceita.onclick = () => abrirModal(modalReceita);
    }

    if (btnDespesa) {
        btnDespesa.onclick = () => abrirModal(modalDespesa);
    }

    document.querySelectorAll('.fechar-modal').forEach(btn => {
        btn.onclick = () => {
            fecharModal(modalReceita);
            fecharModal(modalDespesa);
        };
    });

    window.addEventListener('click', function(event) {

        if (event.target === modalReceita) {
            fecharModal(modalReceita);
        }

        if (event.target === modalDespesa) {
            fecharModal(modalDespesa);
        }

    });

    // ===============================
    // REABRIR MODAL APÓS SALVAR
    // ===============================

    const urlParams = new URLSearchParams(window.location.search);
    const abrirModalParam = urlParams.get('abrirModal');

    if (abrirModalParam === 'receita') {
        abrirModal(modalReceita);
    }

    if (abrirModalParam === 'despesa') {
        abrirModal(modalDespesa);
    }

    // ===============================
    // MÁSCARA DE VALOR
    // ===============================

    document.querySelectorAll('.valor-input').forEach(input => {

        input.addEventListener('input', function(e) {

            let v = e.target.value.replace(/\D/g, '');

            if (v.length === 0) {
                e.target.value = '';
                return;
            }

            v = (parseInt(v) / 100).toFixed(2);
            v = v.replace('.', ',');
            v = v.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            e.target.value = v;

        });

    });

    // ===============================
    // DROPDOWN MEMBROS COM BUSCA
    // ===============================

    const campoBusca = document.getElementById('membro_busca');
    const dropdown = document.getElementById('dropdownMembros');
    const campoId = document.getElementById('membro_id');
    const searchInput = document.getElementById('searchMembro');
    const membrosList = document.getElementById('membrosList');

    let membrosData = [];

    if (membrosList) {

        const items = membrosList.querySelectorAll('.dropdown-item');

        items.forEach(item => {

            membrosData.push({
                id: item.getAttribute('data-id'),
                nome: item.getAttribute('data-nome'),
                foto: item.getAttribute('data-foto'),
                element: item.cloneNode(true)
            });

        });

    }

    function selecionarMembro(elemento) {

        const id = elemento.getAttribute('data-id');
        const nome = elemento.getAttribute('data-nome');

        if (campoBusca) campoBusca.value = nome;
        if (campoId) campoId.value = id;

        dropdown.style.display = 'none';

        if (searchInput) {
            searchInput.value = '';
        }

    }

    function aplicarEventosDropdown() {

        document.querySelectorAll('.dropdown-item').forEach(item => {

            item.addEventListener('click', function(e) {

                e.stopPropagation();
                selecionarMembro(this);

            });

        });

    }

    function filtrarMembros(searchTerm) {

        if (!membrosList) return;

        const term = searchTerm.toLowerCase().trim();

        membrosList.innerHTML = '';

        const filtrados = membrosData.filter(membro =>
            membro.nome.toLowerCase().includes(term)
        );

        if (filtrados.length === 0) {

            const noResults = document.createElement('div');

            noResults.className = 'no-results';

            noResults.innerHTML = `
                <i class="fas fa-user-slash"></i>
                <p>Nenhum membro encontrado</p>
                <small>Tente outro termo</small>
            `;

            membrosList.appendChild(noResults);

        } else {

            filtrados.forEach(membro => {

                membrosList.appendChild(
                    membro.element.cloneNode(true)
                );

            });

        }

        aplicarEventosDropdown();

    }

    if (campoBusca) {

        campoBusca.addEventListener('click', function(e) {

            e.stopPropagation();

            dropdown.style.display =
                dropdown.style.display === 'block'
                ? 'none'
                : 'block';

            if (dropdown.style.display === 'block' && searchInput) {

                setTimeout(() => {
                    searchInput.focus();
                }, 100);

            }

        });

        campoBusca.addEventListener('input', function() {

            dropdown.style.display = 'block';

            if (searchInput) {

                searchInput.value = this.value;
                filtrarMembros(this.value);

            }

        });

    }

    if (searchInput) {

        searchInput.addEventListener('input', function() {

            filtrarMembros(this.value);

        });

        searchInput.addEventListener('click', function(e) {

            e.stopPropagation();

        });

    }

    aplicarEventosDropdown();

    window.addEventListener('click', function(e) {

        if (!e.target.closest('.custom-select')) {

            dropdown.style.display = 'none';

        }

    });

    document.addEventListener('keydown', function(e) {

        if (e.key === 'Escape') {

            dropdown.style.display = 'none';

        }

    });

    // ===============================
    // VALIDAÇÃO RECEITA
    // ===============================

    const formReceita = document.getElementById('formReceita');

    if (formReceita) {

        formReceita.addEventListener('submit', function(e) {

            const membroId = document.getElementById('membro_id').value;

            if (!membroId) {

                if (!confirm('Nenhum membro selecionado. Deseja continuar?')) {

                    e.preventDefault();

                }

            }

        });

    }

    // ===============================
    // DESPESA - MEMBRO ZERO
    // ===============================

    const formDespesa = document.getElementById('formDespesa');

    if (formDespesa) {

        formDespesa.addEventListener('submit', function() {

            let hidden = document.createElement('input');

            hidden.type = 'hidden';
            hidden.name = 'membro_id';
            hidden.value = '0';

            this.appendChild(hidden);

        });

    }

    // ===============================
    // GRÁFICO
    // ===============================

    <?php if (count($dadosGrafico) > 0): ?>

    const ctx = document.getElementById('graficoFinanceiro').getContext('2d');

    const meses = <?php echo json_encode(array_reverse(array_column($dadosGrafico, 'mes'))); ?>;

    const receitas = <?php echo json_encode(array_reverse(array_column($dadosGrafico, 'total_receitas'))); ?>;

    const despesas = <?php echo json_encode(array_reverse(array_column($dadosGrafico, 'total_despesas'))); ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [
                {
                    label: 'Receitas',
                    data: receitas,
                    backgroundColor: 'rgba(25, 135, 84, 0.5)',
                    borderColor: '#198754',
                    borderWidth: 2
                },
                {
                    label: 'Despesas',
                    data: despesas,
                    backgroundColor: 'rgba(220, 53, 69, 0.5)',
                    borderColor: '#dc3545',
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label +
                                ': R$ ' +
                                context.raw.toLocaleString(
                                    'pt-BR',
                                    { minimumFractionDigits: 2 }
                                );
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' +
                                value.toLocaleString('pt-BR');
                        }
                    }
                }
            }
        }
    });

    <?php endif; ?>

});
</script>
</body>
</html>
<?php
ob_end_flush();
?>