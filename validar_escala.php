<?php
// validar_escala.php
// Sem BOM no arquivo! (salvar sem BOM UTF-8)
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json; charset=utf-8');

ob_start();

date_default_timezone_set('America/Sao_Paulo');
include('verificarLogin.php');
verificarLogin();
include_once("config.php");

// Verifica sessão
if((!isset($_SESSION['usuario']) == true) && ($_SESSION['senha']) == true) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
}
$logado = $_SESSION['usuario'];
$igreja = $_SESSION['igreja_id'];

include('registroslog.php');

function respond($data) {
    if (ob_get_length()) ob_clean();
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(['success' => false, 'message' => 'Apenas POST permitido.']);
}

// Recebe dados (suporta POST raw ou form-encoded)
$dadosRaw = $_POST['dados'] ?? file_get_contents('php://input');
$currentId = isset($_POST['current_id']) ? intval($_POST['current_id']) : null;

if (!$dadosRaw) {
    respond(['success' => false, 'message' => 'Nenhum dado recebido.']);
}

$dados = json_decode($dadosRaw, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    respond(['success' => false, 'message' => 'JSON inválido: ' . json_last_error_msg()]);
}

// ============================================
// NORMALIZAR DADOS - SUPORTAR AMBOS OS FORMATOS
// ============================================
$datas = [];
$escalas = [];

// FORMATO NOVO: { nome, descricao, escala: { data: { funcao: id } } }
if (isset($dados['escala']) && !isset($dados['datas'])) {
    $escalasRaw = $dados['escala'];
    $datas = array_keys($escalasRaw);
    
    // Converter para formato de validação { funcao: { data: musico_id } }
    foreach ($datas as $data) {
        foreach ($escalasRaw[$data] as $funcao => $musicoId) {
            if (!empty($musicoId)) {
                $escalas[$funcao][$data] = $musicoId;
            }
        }
    }
}
// FORMATO ANTIGO: { datas: [], escalas: { funcao: { data: musico_id } } }
elseif (isset($dados['datas']) && isset($dados['escalas'])) {
    $datas = $dados['datas'];
    $escalas = $dados['escalas'];
}
// FORMATO MISTO: tem datas mas escalas está como objeto aninhado
elseif (isset($dados['datas']) && isset($dados['escala'])) {
    $datas = $dados['datas'];
    $escalasRaw = $dados['escala'];
    
    foreach ($datas as $data) {
        if (isset($escalasRaw[$data])) {
            foreach ($escalasRaw[$data] as $funcao => $musicoId) {
                if (!empty($musicoId)) {
                    $escalas[$funcao][$data] = $musicoId;
                }
            }
        }
    }
}
else {
    respond(['success' => false, 'message' => 'Formato inválido. Esperado "escala" ou "datas/escalas".']);
}

if (empty($datas) || empty($escalas)) {
    respond(['success' => false, 'message' => 'Nenhuma data ou escala para validar.']);
}

$conflicts = [];
$warnings = [];

/** Função para buscar nome do voluntário pelo ID */
function getVoluntarioNome($conexao, $id, $igreja) {
    static $cache = [];
    $key = $id . '_' . $igreja;
    
    if (isset($cache[$key])) {
        return $cache[$key];
    }
    
    $stmt = $conexao->prepare("SELECT usuario FROM voluntarios WHERE id = ? AND igreja_id = ?");
    if ($stmt) {
        $stmt->bind_param("ii", $id, $igreja);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $cache[$key] = $row['usuario'];
            $stmt->close();
            return $cache[$key];
        }
        $stmt->close();
    }
    $cache[$key] = "ID:{$id}";
    return $cache[$key];
}

/** Verificar se tabela existe */
function table_exists($con, $table) {
    $t = $con->real_escape_string($table);
    $res = $con->query("SHOW TABLES LIKE '{$t}'");
    return ($res && $res->num_rows > 0);
}

// ============================================
// VALIDAÇÃO 1: Verificar conflitos em escalas existentes
// ============================================

// ============================================
// VALIDAÇÃO 1: Verificar conflitos em escalas existentes
// ============================================

$table = 'escalas_louvor';

if (table_exists($conexao, $table)) {

    $sql = "
        SELECT 
            id,
            usuario,
            tipo,
            dados_escala
        FROM {$table}
        WHERE igreja_id = {$igreja}
    ";

    // Ignorar a própria escala em edição
    if ($currentId) {
        $sql .= " AND id != " . intval($currentId);
    }

    $res = $conexao->query($sql);

    if ($res) {

        while ($row = $res->fetch_assoc()) {

            $dadosExist = json_decode($row['dados_escala'], true);

            if (!is_array($dadosExist)) {
                continue;
            }

            // Extrair escalas existentes
            $escalasExist = [];

            // Formato novo
            if (isset($dadosExist['escala']) && is_array($dadosExist['escala'])) {

                foreach ($dadosExist['escala'] as $data => $funcoes) {

                    foreach ($funcoes as $funcao => $registro) {

                        $musicoId = $registro['voluntario_id']
                            ?? $registro['cadastroadm_id']
                            ?? null;

                        if ($musicoId) {
                            $escalasExist[$funcao][$data] = $musicoId;
                        }
                    }
                }
            }

            // Formato antigo
            elseif (isset($dadosExist['escalas']) && is_array($dadosExist['escalas'])) {

                $escalasExist = $dadosExist['escalas'];
            }

            // Comparar conflitos
            foreach ($escalas as $funcao => $mapDatas) {

                foreach ($mapDatas as $iso => $musicoId) {

                    if (empty($musicoId)) {
                        continue;
                    }

                    foreach ($escalasExist as $fExist => $mapExist) {

                        if (
                            isset($mapExist[$iso]) &&
                            $mapExist[$iso] == $musicoId
                        ) {

                            $nomeVoluntario = getVoluntarioNome(
                                $conexao,
                                $musicoId,
                                $igreja
                            );

                            $dataFormatada = date(
                                'd/m/Y',
                                strtotime($iso)
                            );

                            $tipoEscala = ucfirst(
                                $row['tipo'] ?? 'Sem tipo'
                            );

                            $conflicts[] =
                                "Voluntário \"{$nomeVoluntario}\" já está escalado em "
                                . "'{$row['usuario']}' "
                                . "[{$tipoEscala}] "
                                . "na função '{$fExist}' "
                                . "no dia {$dataFormatada}";

                            break;
                        }
                    }
                }
            }
        }
    }
}
// ============================================
// VALIDAÇÃO 2: Verificar duplicidade dentro da mesma escala
// ============================================

$musicosPorData = [];

foreach ($escalas as $funcao => $mapDatas) {
    foreach ($mapDatas as $iso => $musicoId) {
        if (empty($musicoId)) continue;
        
        $dataFormatada = date('d/m/Y', strtotime($iso));
        
        // Verificar se o mesmo músico aparece em múltiplas funções na mesma data
        if (isset($musicosPorData[$iso][$musicoId])) {
            $nomeVoluntario = getVoluntarioNome($conexao, $musicoId, $igreja);
            $funcaoAnterior = $musicosPorData[$iso][$musicoId];
            $warnings[] = "Voluntário \"{$nomeVoluntario}\" está escalado em múltiplas funções no dia {$dataFormatada}: '{$funcaoAnterior}' e '{$funcao}'";
        } else {
            $musicosPorData[$iso][$musicoId] = $funcao;
        }
    }
}

// ============================================
// VALIDAÇÃO 3: Verificar disponibilidade (opcional - ajuste conforme sua tabela)
// ============================================

// Se houver tabela de disponibilidade, verificar aqui
if (table_exists($conexao, 'voluntario_disponibilidade')) {
    foreach ($escalas as $funcao => $mapDatas) {
        foreach ($mapDatas as $iso => $musicoId) {
            if (empty($musicoId)) continue;
            
            $stmt = $conexao->prepare("
                SELECT disponivel FROM voluntario_disponibilidade 
                WHERE voluntario_id = ? AND data = ? AND igreja_id = ?
            ");
            if ($stmt) {
                $stmt->bind_param("isi", $musicoId, $iso, $igreja);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($row = $result->fetch_assoc()) {
                    if (!$row['disponivel']) {
                        $nomeVoluntario = getVoluntarioNome($conexao, $musicoId, $igreja);
                        $dataFormatada = date('d/m/Y', strtotime($iso));
                        $warnings[] = "Voluntário \"{$nomeVoluntario}\" marcado como INDISPONÍVEL no dia {$dataFormatada}";
                    }
                }
                $stmt->close();
            }
        }
    }
}

// ============================================
// ESTATÍSTICAS DA ESCALA
// ============================================
$estatisticas = [];
foreach ($datas as $data) {
    $total = 0;
    foreach ($escalas as $funcao => $mapDatas) {
        if (isset($mapDatas[$data]) && !empty($mapDatas[$data])) {
            $total++;
        }
    }
    $estatisticas[date('d/m/Y', strtotime($data))] = $total;
}

// ============================================
// RESPOSTA FINAL
// ============================================

$conflicts = array_values(array_unique($conflicts));
$warnings = array_values(array_unique($warnings));

if (!empty($conflicts)) {
    $formattedMessage = "⚠️ CONFLITOS ENCONTRADOS:\n\n";
    foreach ($conflicts as $conflict) {
        $formattedMessage .= "• " . $conflict . "\n";
    }
    if (!empty($warnings)) {
        $formattedMessage .= "\n⚠️ AVISOS:\n";
        foreach ($warnings as $warning) {
            $formattedMessage .= "• " . $warning . "\n";
        }
    }
    
    respond([
        'success' => false,
        'message' => $formattedMessage,
        'conflicts' => $conflicts,
        'warnings' => $warnings,
        'estatisticas' => $estatisticas
    ]);
}

// Sucesso - sem conflitos
$successMessage = "✅ ESCALA VÁLIDA!\n\n";
$successMessage .= "📅 Total de datas: " . count($datas) . "\n";
$successMessage .= "🎵 Funções: " . count($escalas) . "\n\n";
$successMessage .= "📊 POR DATA:\n";
foreach ($estatisticas as $data => $total) {
    $successMessage .= "  • {$data}: {$total} pessoa(s)\n";
}
if (!empty($warnings)) {
    $successMessage .= "\n⚠️ AVISOS:\n";
    foreach ($warnings as $warning) {
        $successMessage .= "  • " . $warning . "\n";
    }
}

respond([
    'success' => true,
    'message' => $successMessage,
    'estatisticas' => $estatisticas,
    'warnings' => $warnings
]);