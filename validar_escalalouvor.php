<?php
// validar_escala.php (REFATORADO COMPLETO)

ini_set('display_errors', 0);
error_reporting(0);

header('Content-Type: application/json; charset=utf-8');

ob_start();
session_start();

date_default_timezone_set('America/Sao_Paulo');

// ==========================
// RESPOSTA SEGURA
// ==========================
function respond($data)
{
    if (ob_get_length()) {
        ob_clean();
    }

    $json = json_encode($data, JSON_UNESCAPED_UNICODE);

    if ($json === false) {
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao gerar JSON: ' . json_last_error_msg()
        ]);
    } else {
        echo $json;
    }

    exit;
}

// ==========================
// SESSÃO
// ==========================
if (!isset($_SESSION['usuario'])) {
    respond([
        'success' => false,
        'message' => 'Sessão expirada. Faça login novamente.'
    ]);
}

$igreja = intval($_SESSION['igreja_id'] ?? 0);

// ==========================
// INCLUDES
// ==========================
include_once("config.php");

// ==========================
// VALIDAR MÉTODO
// ==========================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond([
        'success' => false,
        'message' => 'Apenas POST permitido'
    ]);
}

// ==========================
// RECEBER DADOS
// ==========================
$raw = file_get_contents('php://input');

if (isset($_POST['dados'])) {
    $dados = json_decode($_POST['dados'], true);
} else {
    $dados = json_decode($raw, true);
}

if (!$dados || !isset($dados['escala'])) {

    respond([
        'success' => false,
        'message' => 'JSON inválido ou formato incorreto',
        'debug' => substr($raw, 0, 300)
    ]);
}

$escalaNova = $dados['escala'];
$currentId  = intval($dados['id'] ?? 0);

// ==========================
// FUNÇÕES AUXILIARES
// ==========================
function table_exists($con, $table)
{
    $table = $con->real_escape_string($table);

    $res = $con->query("SHOW TABLES LIKE '{$table}'");

    return ($res && $res->num_rows > 0);
}

function getNomeVoluntario($conexao, $id)
{
    static $cache = [];

    if (isset($cache[$id])) {
        return $cache[$id];
    }

    $stmt = $conexao->prepare("
        SELECT usuario
        FROM voluntarios
        WHERE id = ?
        LIMIT 1
    ");

    if ($stmt) {

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {

            $cache[$id] = $row['usuario'];

            $stmt->close();

            return $cache[$id];
        }

        $stmt->close();
    }

    return "ID {$id}";
}

// ==========================
// NORMALIZAR NOVA ESCALA
// ==========================
$mapaNova = [];
$warnings = [];

foreach ($escalaNova as $data => $funcoes) {

    if (!is_array($funcoes)) {
        continue;
    }

    foreach ($funcoes as $funcao => $info) {

        $id = intval($info['voluntario_id'] ?? 0);

        if (!$id) {
            continue;
        }

        // Verifica múltiplas funções no mesmo dia
        if (isset($mapaNova[$data][$id])) {

            $nome = getNomeVoluntario($conexao, $id);

            $warnings[] =
                "Voluntário \"{$nome}\" está em múltiplas funções no dia "
                . date('d/m/Y', strtotime($data));
        }

        $mapaNova[$data][$id] = [
            'funcao' => $funcao
        ];
    }
}

// ==========================
// VALIDAR CONFLITOS
// ==========================
$conflicts = [];

$table = 'escalas_louvor';

if (table_exists($conexao, $table)) {

    $sql = "
        SELECT
            id,
            usuario,
            tipo,
            dados_escala
        FROM {$table}
        WHERE igreja_id = ?
    ";

    // Ignorar a própria escala em edição
    if ($currentId > 0) {
        $sql .= " AND id != ?";
    }

    $stmt = $conexao->prepare($sql);

    if ($stmt) {

        if ($currentId > 0) {
            $stmt->bind_param("ii", $igreja, $currentId);
        } else {
            $stmt->bind_param("i", $igreja);
        }

        $stmt->execute();

        $result = $stmt->get_result();

 while ($row = $result->fetch_assoc()) {

    $dadosExist = json_decode($row['dados_escala'], true);

    if (
        !$dadosExist ||
        !isset($dadosExist['escala']) ||
        !is_array($dadosExist['escala'])
    ) {
        continue;
    }

    // ==========================
    // NORMALIZAR ESCALA EXISTENTE
    // ==========================
    $mapaExistente = [];

    foreach ($dadosExist['escala'] as $dataExist => $funcoesExist) {

        if (!is_array($funcoesExist)) {
            continue;
        }

        foreach ($funcoesExist as $funcaoExist => $infoExist) {

            $idExist = 0;

            // FORMATO NOVO
            if (is_array($infoExist)) {

                $idExist = intval(
                    $infoExist['voluntario_id']
                    ?? $infoExist['cadastroadm_id']
                    ?? 0
                );

            } else {

                // FORMATO ANTIGO
                $idExist = intval($infoExist);
            }

            if (!$idExist) {
                continue;
            }

            $mapaExistente[$dataExist][$idExist] = [
                'funcao' => $funcaoExist
            ];
        }
    }

    // ==========================
    // COMPARAR CONFLITOS
    // ==========================
    foreach ($mapaExistente as $dataExist => $voluntarios) {

        foreach ($voluntarios as $idExist => $dadosFuncao) {

            // Existe na nova escala?
            if (isset($mapaNova[$dataExist][$idExist])) {

                $nome = getNomeVoluntario(
                    $conexao,
                    $idExist
                );

                $dataBr = date(
                    'd/m/Y',
                    strtotime($dataExist)
                );

                $tipoEscala = ucfirst(
                    $row['tipo'] ?? 'Sem tipo'
                );

                $funcaoAtual =
                    $mapaNova[$dataExist][$idExist]['funcao']
                    ?? 'Sem função';

                $funcaoExistente =
                    $dadosFuncao['funcao']
                    ?? 'Sem função';

                $conflicts[] = [
                    'nome' => $nome,
                    'data' => $dataBr,
                    'escala' => $row['usuario'],
                    'tipo' => $tipoEscala,
                    'funcao_atual' => $funcaoAtual,
                    'funcao_existente' => $funcaoExistente
                ];
            }
        }
    }
}
        $stmt->close();
    }
}

// ==========================
// LIMPAR DUPLICADOS
// ==========================
$conflicts = array_map(
    "unserialize",
    array_unique(
        array_map("serialize", $conflicts)
    )
);

$warnings = array_values(
    array_unique($warnings)
);

// ==========================
// RESPOSTA FINAL
// ==========================
if (!empty($conflicts)) {

    $mensagem = "⚠️ CONFLITOS ENCONTRADOS:\n\n";

    foreach ($conflicts as $c) {

       $mensagem .=
    "• {$c['nome']} já está escalado em "
    . "\"{$c['escala']}\" "
    . "[{$c['tipo']}]\n";

$mensagem .=
    "  📅 Data: {$c['data']}\n";

$mensagem .=
    "  🎵 Função atual: {$c['funcao_atual']}\n";

$mensagem .=
    "  🔁 Já escalado em: {$c['funcao_existente']}\n\n";
        }

    if (!empty($warnings)) {

        $mensagem .= "\n⚠️ AVISOS:\n";

        foreach ($warnings as $w) {
            $mensagem .= "• {$w}\n";
        }
    }

    respond([
        'success'   => false,
        'message'   => $mensagem,
        'conflicts' => $conflicts,
        'warnings'  => $warnings
    ]);
}

// ==========================
// SUCESSO
// ==========================
respond([
    'success' => true,
    'message' => '✅ Escala válida! Nenhum conflito encontrado.',
    'warnings' => $warnings
]);