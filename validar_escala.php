<?php
// validar_escala.php
// Sem BOM no arquivo! (salvar sem BOM UTF-8)
// Proteções básicas para não imprimir HTML acidentalmente:
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json; charset=utf-8');

// Buffer para garantir que nenhum HTML prévio atue na resposta
ob_start();

include_once('config.php'); // espera $conexao (mysqli)

function respond($data) {
    // limpa buffer e envia JSON
    if (ob_get_length()) ob_clean();
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(['success' => false, 'message' => 'Apenas POST permitido.']);
}

// Recebe dados (pode vir via form-encoded 'dados' ou body raw)
$dadosRaw = $_POST['dados'] ?? file_get_contents('php://input');
$currentId = isset($_POST['current_id']) ? intval($_POST['current_id']) : null;

if (!$dadosRaw) {
    respond(['success' => false, 'message' => 'Nenhum dado recebido (campo "dados").']);
}

$dados = json_decode($dadosRaw, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    respond(['success' => false, 'message' => 'JSON inválido: ' . json_last_error_msg(), 'raw' => substr($dadosRaw,0,200)]);
}

$datas = $dados['datas'] ?? [];
$escalas = $dados['escalas'] ?? [];

if (empty($datas) || empty($escalas)) {
    respond(['success' => false, 'message' => 'Formato inválido: espere "datas" e "escalas".']);
}

$conflicts = [];

/** util: verificar existência de tabela */
function table_exists($con, $table) {
    $t = $con->real_escape_string($table);
    $res = $con->query("SHOW TABLES LIKE '{$t}'");
    return ($res && $res->num_rows > 0);
}

/* 1) Se existir tabela normalizada 'escalas_membros' -> usar consulta direta */
if (table_exists($conexao, 'escalas_membros')) {
    $stmt = $conexao->prepare(
        "SELECT es.nome AS escala_nome, em.escala_id 
         FROM escalas_membros em
         JOIN escalas_louvor es ON em.escala_id = es.id
         WHERE em.musico_nome = ? AND em.data = ?"
    );
    if (!$stmt) {
        respond(['success' => false, 'message' => 'Erro preparar consulta: '.$conexao->error]);
    }

    foreach ($escalas as $funcao => $map) {
        foreach ($map as $iso => $musico) {
            $musico = trim($musico);
            if ($musico === '') continue;
            $stmt->bind_param('ss', $musico, $iso);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                while ($r = $res->fetch_assoc()) {
                    if ($currentId && intval($r['escala_id']) === $currentId) continue;
                    $conflicts[] = "O músico \"{$musico}\" já está em '{$r['escala_nome']}' no dia {$iso}";
                }
            }
        }
    }
    $stmt->close();
} else {
    /* 2) Fallback: varrer tabelas com coluna dados_escala JSON */
    $tablesToCheck = ['escalas_midias','escalas_som', 'escalas_louvor', 'escalas_danca', 'escalas_criativo', 'escalas_staff', 'escalas_kids'];
    foreach ($tablesToCheck as $table) {
        if (!table_exists($conexao, $table)) continue;

        $sql = "SELECT id, nome, dados_escala FROM {$table}";
        if ($currentId) $sql .= " WHERE id != ".intval($currentId);

        $res = $conexao->query($sql);
        if (!$res) continue;

        while ($row = $res->fetch_assoc()) {
            $dadosExist = json_decode($row['dados_escala'], true);
            if (!is_array($dadosExist) || empty($dadosExist['escalas'])) continue;
            $esExist = $dadosExist['escalas'];

            // comparar: para cada músico/data da escala enviada, verificar se aparece aqui
            foreach ($escalas as $funcao => $map) {
                foreach ($map as $iso => $musico) {
                    $musico = trim($musico);
                    if ($musico === '') continue;
                    // varrer funções existentes
                    foreach ($esExist as $fExist => $mapExist) {
                        if (isset($mapExist[$iso]) && $mapExist[$iso] == $musico) {
                            $dataFormatada = date('d/m/Y', strtotime($iso));
                            $conflicts[] = "Voluntatio(a) \"{$musico}\" está em '{$row['nome']}' ({$table}) no dia {$dataFormatada}";
                        }
                    }
                }
            }
        }
    }
}

// eliminar duplicados
$conflicts = array_values(array_unique($conflicts));

if (!empty($conflicts)) {
    // mensagem formatada apenas com \n
    $formattedMessage = "⚠️ Foram encontrados os seguintes conflitos:\n";
    $formattedMessage .= "• " . implode("\n• ", $conflicts);

    respond([
        'success'   => false,
        'message'   => $formattedMessage, // texto cru com \n
        'conflicts' => $conflicts
    ]);
}

// RESPOSTA PARA SUCESSO (FALTANTE NO CÓDIGO ORIGINAL)
// Esta é a linha que estava faltando:
respond([
    'success' => true,
    'message' => 'Escala validada com sucesso! Nenhum conflito encontrado.'
]);

// Nota: O exit já está dentro da função respond(), então não precisa chamar exit novamente
?>