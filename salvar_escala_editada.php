<?php
// salvar_escala_editada.php (REFATORADO COMPLETO)

ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

ob_start();
session_start();

date_default_timezone_set('America/Sao_Paulo');

include('config.php');
include('registroslog.php');

// ========================================
// RESPOSTA JSON SEGURA
// ========================================
function respond($data)
{
    if (ob_get_length()) {
        ob_clean();
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// ========================================
// TRATAMENTO GLOBAL DE ERROS
// ========================================
set_error_handler(function ($errno, $errstr, $errfile, $errline) {

    respond([
        'success' => false,
        'message' => 'Erro interno do servidor',
        'erro' => $errstr,
        'linha' => $errline
    ]);
});

register_shutdown_function(function () {

    $error = error_get_last();

    if ($error !== null) {

        respond([
            'success' => false,
            'message' => 'Erro fatal',
            'erro' => $error['message'],
            'linha' => $error['line']
        ]);
    }
});

// ========================================
// VALIDAR SESSÃO
// ========================================
if (!isset($_SESSION['usuario'])) {

    respond([
        'success' => false,
        'message' => 'Sessão expirada'
    ]);
}

$usuario = $_SESSION['usuario'];
$igreja  = intval($_SESSION['igreja_id'] ?? 0);

if ($igreja <= 0) {

    respond([
        'success' => false,
        'message' => 'Igreja não identificada'
    ]);
}

// ========================================
// VALIDAR MÉTODO
// ========================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    respond([
        'success' => false,
        'message' => 'Método inválido'
    ]);
}

// ========================================
// RECEBER DADOS
// ========================================
$raw = file_get_contents('php://input');

if (isset($_POST['dados'])) {

    $dados = json_decode($_POST['dados'], true);

} else {

    $dados = json_decode($raw, true);
}

// ========================================
// VALIDAR JSON
// ========================================
if (!$dados || !is_array($dados)) {

    respond([
        'success' => false,
        'message' => 'JSON inválido'
    ]);
}

// ========================================
// DEFINIR TABELA FIXA
// ========================================
$tabela = 'escalas_louvor';

// ========================================
// RECEBER ID
// ========================================
$id = intval($_POST['id'] ?? ($dados['id'] ?? 0));

if ($id <= 0) {

    respond([
        'success' => false,
        'message' => 'ID inválido'
    ]);
}

// ========================================
// VALIDAR CAMPOS
// ========================================
$nome       = trim($dados['nome'] ?? '');
$descricao  = trim($dados['descricao'] ?? '');

if ($nome === '') {

    respond([
        'success' => false,
        'message' => 'Nome da escala obrigatório'
    ]);
}

if (
    !isset($dados['escala']) ||
    !is_array($dados['escala']) ||
    empty($dados['escala'])
) {

    respond([
        'success' => false,
        'message' => 'Escala vazia'
    ]);
}

// ========================================
// VALIDAR ESTRUTURA DA ESCALA
// ========================================
foreach ($dados['escala'] as $data => $funcoes) {

    if (!strtotime($data)) {

        respond([
            'success' => false,
            'message' => "Data inválida: {$data}"
        ]);
    }

    if (!is_array($funcoes)) {
        continue;
    }

    foreach ($funcoes as $funcao => $info) {

        if (empty($info)) {
            continue;
        }

        if (!is_array($info)) {
            continue;
        }

        if (isset($info['voluntario_id'])) {

            if (!is_numeric($info['voluntario_id'])) {

                respond([
                    'success' => false,
                    'message' => "Voluntário inválido na função {$funcao}"
                ]);
            }
        }

        // Converter nome -> usuario
        if (isset($info['nome'])) {

            $dados['escala'][$data][$funcao]['usuario'] = $info['nome'];

            unset($dados['escala'][$data][$funcao]['nome']);
        }
    }
}

// ========================================
// VERIFICAR EXISTÊNCIA DA ESCALA
// ========================================
$stmt = $conexao->prepare("
    SELECT 
        id,
        pdf_path
    FROM escalas_louvor
    WHERE id = ?
    AND igreja_id = ?
    LIMIT 1
");

if (!$stmt) {

    respond([
        'success' => false,
        'message' => 'Erro SQL SELECT',
        'erro' => $conexao->error
    ]);
}

$stmt->bind_param("ii", $id, $igreja);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows <= 0) {

    respond([
        'success' => false,
        'message' => 'Escala não encontrada'
    ]);
}

$escala = $result->fetch_assoc();

$pdf_antigo = $escala['pdf_path'] ?? '';

$stmt->close();

// ========================================
// PROCESSAR PDF
// ========================================
$pdfPath = $pdf_antigo;

if (
    isset($_FILES['pdf']) &&
    $_FILES['pdf']['error'] === UPLOAD_ERR_OK
) {

    $diretorio = __DIR__ . '/pdf_escalas/';

    if (!file_exists($diretorio)) {

        mkdir($diretorio, 0777, true);
    }

    $nomeLimpo = preg_replace(
        '/[^a-zA-Z0-9_-]/',
        '_',
        strtolower($nome)
    );

    $arquivoPdf = 'escalas_' .
        $id . '_' .
        $nomeLimpo . '_' .
        time() . '.pdf';

    $caminhoCompleto = $diretorio . $arquivoPdf;

    $pdfPath = 'pdf_escalas/' . $arquivoPdf;

    if (
        !move_uploaded_file(
            $_FILES['pdf']['tmp_name'],
            $caminhoCompleto
        )
    ) {

        respond([
            'success' => false,
            'message' => 'Erro ao salvar PDF'
        ]);
    }

    // Remover PDF antigo
    if (!empty($pdf_antigo)) {

        $arquivoAntigo = __DIR__ . '/' . $pdf_antigo;

        if (file_exists($arquivoAntigo)) {

            @unlink($arquivoAntigo);
        }
    }
}

// ========================================
// PREPARAR JSON
// ========================================
$dadosJson = json_encode(
    $dados,
    JSON_UNESCAPED_UNICODE
);

if ($dadosJson === false) {

    respond([
        'success' => false,
        'message' => 'Erro ao converter JSON'
    ]);
}

$dataAtualizacao = date('Y-m-d H:i:s');

// ========================================
// UPDATE
// ========================================
$stmt = $conexao->prepare("
    UPDATE escalas_louvor
    SET
        nome = ?,
        descricao = ?,
        dados_escala = ?,
        pdf_path = ?,
        usuario = ?,
        data_atualizacao = ?
    WHERE id = ?
    AND igreja_id = ?
");

if (!$stmt) {

    respond([
        'success' => false,
        'message' => 'Erro SQL UPDATE',
        'erro' => $conexao->error
    ]);
}

$stmt->bind_param(
    "ssssssii",
    $nome,
    $descricao,
    $dadosJson,
    $pdfPath,
    $usuario,
    $dataAtualizacao,
    $id,
    $igreja
);

if (!$stmt->execute()) {

    respond([
        'success' => false,
        'message' => 'Erro ao atualizar escala',
        'erro' => $stmt->error
    ]);
}

$stmt->close();

// ========================================
// LOG
// ========================================
if (function_exists('registrarLog')) {

    registrarLog(
        $conexao,
        $usuario,
        "Atualizou escala ID {$id}"
    );
}

// ========================================
// SUCESSO
// ========================================
respond([
    'success' => true,
    'message' => 'Escala atualizada com sucesso!',
    'pdf_path' => $pdfPath . '?v=' . time(),
    'id' => $id
]);
?>