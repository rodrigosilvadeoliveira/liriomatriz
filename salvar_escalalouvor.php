<?php
date_default_timezone_set('America/Sao_Paulo');

ob_start();
header('Content-Type: application/json; charset=utf-8');

include('verificarLogin.php');
include_once("config.php");
require_once('registroslog.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
==============================
VALIDAR SESSÃO
==============================
*/
if (!isset($_SESSION['usuario']) || !isset($_SESSION['igreja_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Sessão expirada'
    ]);
    exit;
}

$logado = $_SESSION['usuario'];
$igreja = $_SESSION['igreja_id'];

$response = [
    'success' => false,
    'message' => ''
];

try {

    $conexao->begin_transaction();

    /*
    ==============================
    VALIDAR POST
    ==============================
    */
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método inválido');
    }

    if (!isset($_POST['dados'])) {
        throw new Exception('Dados não enviados');
    }

    $dados = json_decode($_POST['dados'], true);

    if (!$dados || !isset($dados['nome'])) {
        throw new Exception('Dados inválidos');
    }

    $nome = trim($dados['nome']);
    $tipo = trim($_POST['tipo'] ?? '');

if ($tipo == '') {
    throw new Exception('Tipo de escala não informado');
}


// ==============================
// VALIDAR TIPO NO BANCO
// ==============================
$stmtTipo = $conexao->prepare("
    SELECT id
    FROM categoria_escala
    WHERE tipo = ?
    AND igreja_id = ?
    LIMIT 1
");

$stmtTipo->bind_param("si", $tipo, $igreja);
$stmtTipo->execute();

$resTipo = $stmtTipo->get_result();

if ($resTipo->num_rows === 0) {
    throw new Exception('Tipo de escala inválido');
}

$stmtTipo->close();

    if ($nome == '') {
        throw new Exception('Informe o nome da escala');
    }

    /*
    ==============================
    ENRIQUECER ESCALA
    ==============================
    */
    if (isset($dados['escala'])) {

        $novaEscala = [];

        $stmtVol = $conexao->prepare("
            SELECT id, usuario, cadastroadm_id
            FROM voluntarios
            WHERE id = ? AND igreja_id = ?
            LIMIT 1
        ");

        foreach ($dados['escala'] as $data => $funcoes) {

            foreach ($funcoes as $funcao => $voluntario_id) {

                if (!$voluntario_id) {
                    $novaEscala[$data][$funcao] = null;
                    continue;
                }

                $voluntario_id = intval($voluntario_id);

                $stmtVol->bind_param("ii", $voluntario_id, $igreja);
                $stmtVol->execute();
                $resVol = $stmtVol->get_result();

                if ($resVol->num_rows === 0) {
                    throw new Exception("Voluntário inválido ID: $voluntario_id");
                }

                $vol = $resVol->fetch_assoc();

                // 🔥 CORREÇÃO AQUI
                $novaEscala[$data][$funcao] = [
                    'voluntario_id' => (int)$vol['id'],
                    'usuario' => $vol['usuario'], // ← antes era nome
                    'cadastroadm_id' => (int)$vol['cadastroadm_id']
                ];
            }
        }

        $stmtVol->close();

        $dados['escala'] = $novaEscala;
    }

    /*
    ==============================
    VERIFICAR DUPLICIDADE
    ==============================
    */
    $stmtCheck = $conexao->prepare("
       SELECT id FROM escalas_louvor
        WHERE nome = ?
        AND igreja_id = ?
    AND tipo = ?
    ");
    $stmtCheck->bind_param("sis", $nome, $igreja, $tipo);
    $stmtCheck->execute();
    $check = $stmtCheck->get_result();

    if ($check->num_rows > 0) {
        throw new Exception('Já existe uma escala com este nome.');
    }

    $stmtCheck->close();

    /*
    ==============================
    PDF
    ==============================
    */
    $pdfPath = '';

    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {

        $uploadDir = 'pdf_escalas/';

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = $_POST['fileName'] ?? uniqid('escala_') . '.pdf';
        $fileName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $fileName);

        if (!preg_match('/\.pdf$/i', $fileName)) {
            $fileName .= '.pdf';
        }

        $pdfPath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['pdf']['tmp_name'], $pdfPath)) {
            throw new Exception('Erro ao salvar PDF');
        }
    }

    /*
    ==============================
    INSERT
    ==============================
    */
    $descricao = $dados['descricao'] ?? '';
    $dadosEscala = json_encode($dados, JSON_UNESCAPED_UNICODE);

    $stmt = $conexao->prepare("
        INSERT INTO escalas_louvor
(
    igreja_id,
    tipo,
    nome,
    descricao,
    dados_escala,
    pdf_path,
    data_criacao
)
VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");

    $stmt->bind_param(
    "isssss",
    $igreja,
    $tipo,
    $nome,
    $descricao,
    $dadosEscala,
    $pdfPath
);

    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }

    $escala_id = $conexao->insert_id;

    $stmt->close();

    $conexao->commit();

    /*
    ==============================
    LOG
    ==============================
    */
    if (function_exists('registrarLog')) {
        registrarLog($conexao, $logado, 'Escala criada ID: ' . $escala_id);
    }

    $response['success'] = true;
    $response['message'] = 'Escala salva com sucesso';

} catch (Exception $e) {

    $conexao->rollback();

    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

ob_clean();
echo json_encode($response);
exit;