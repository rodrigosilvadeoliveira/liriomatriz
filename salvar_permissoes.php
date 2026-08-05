<?php
// salvar_permissoes.php - VERSÃO CORRIGIDA
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('America/Sao_Paulo');

include('verificarLogin.php');
verificarLogin();
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: tela_parametros_permissoes.php');
    exit();
}

if (!isset($_POST['igreja_id']) || empty($_POST['igreja_id'])) {
    header('Location: tela_parametros_permissoes.php?erro=igreja');
    exit();
}

$igreja_id = intval($_POST['igreja_id']);
$permissoes = $_POST['permissoes'] ?? [];

$conexao->begin_transaction();

try {
    // SQL com REPLACE INTO
    $sql = "REPLACE INTO parametros_permissoes (
        igreja_id, perfil, 
        pode_editar, pode_excluir, ocultar_opcoes,
        ocultar_administracao, ocultar_membros, ocultar_membros1,
        ocultar_voluntario, ocultar_criativo, ocultar_criativo1,
        ocultar_danca, ocultar_danca1, ocultar_kids, ocultar_kids1,
        ocultar_louvor, ocultar_louvor1, ocultar_midias, ocultar_midias1,
        ocultar_som, ocultar_som1, ocultar_staff, ocultar_staff1,
        ocultar_relatorios, ocultar_recepcao, ocultar_recepcao1,
        ocultar_configuracao
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
    )";
    
    $stmt = $conexao->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Erro ao preparar: ' . $conexao->error);
    }
    
    foreach ($permissoes as $perfil => $dados) {
        $perfil = trim($perfil);
        
        // Converter checkboxes para 0 ou 1
        $editar = isset($dados['editar']) ? 1 : 0;
        $excluir = isset($dados['excluir']) ? 1 : 0;
        $ocultar = isset($dados['ocultar']) ? 1 : 0;
        $administracao = isset($dados['administracao']) ? 1 : 0;
        $membros = isset($dados['membros']) ? 1 : 0;
        $membros1 = isset($dados['membros1']) ? 1 : 0;
        $voluntario = isset($dados['voluntario']) ? 1 : 0;
        $criativo = isset($dados['criativo']) ? 1 : 0;
        $criativo1 = isset($dados['criativo1']) ? 1 : 0;
        $danca = isset($dados['danca']) ? 1 : 0;
        $danca1 = isset($dados['danca1']) ? 1 : 0;
        $kids = isset($dados['kids']) ? 1 : 0;
        $kids1 = isset($dados['kids1']) ? 1 : 0;
        $louvor = isset($dados['louvor']) ? 1 : 0;
        $louvor1 = isset($dados['louvor1']) ? 1 : 0;
        $midias = isset($dados['midias']) ? 1 : 0;
        $midias1 = isset($dados['midias1']) ? 1 : 0;
        $som = isset($dados['som']) ? 1 : 0;
        $som1 = isset($dados['som1']) ? 1 : 0;
        $staff = isset($dados['staff']) ? 1 : 0;
        $staff1 = isset($dados['staff1']) ? 1 : 0;
        $relatorios = isset($dados['relatorios']) ? 1 : 0;
        $recepcao = isset($dados['recepcao']) ? 1 : 0;
        $recepcao1 = isset($dados['recepcao1']) ? 1 : 0;
        $configuracao = isset($dados['configuracao']) ? 1 : 0;
        
        // bind_param: "is" + 25x "i" = 27 caracteres para 27 variáveis
        $stmt->bind_param(
            "isiiiiiiiiiiiiiiiiiiiiiiiii", // 1 string + 26 inteiros = 27 caracteres
            $igreja_id,     // i (1)
            $perfil,        // s (2)
            $editar,        // i (3)
            $excluir,       // i (4)
            $ocultar,       // i (5)
            $administracao, // i (6)
            $membros,       // i (7)
            $membros1,      // i (8)
            $voluntario,    // i (9)
            $criativo,      // i (10)
            $criativo1,     // i (11)
            $danca,         // i (12)
            $danca1,        // i (13)
            $kids,          // i (14)
            $kids1,         // i (15)
            $louvor,        // i (16)
            $louvor1,       // i (17)
            $midias,        // i (18)
            $midias1,       // i (19)
            $som,           // i (20)
            $som1,          // i (21)
            $staff,         // i (22)
            $staff1,        // i (23)
            $relatorios,    // i (24)
            $recepcao,      // i (25)
            $recepcao1,     // i (26)
            $configuracao   // i (27)
        );
        
        if (!$stmt->execute()) {
            throw new Exception('Erro no perfil ' . $perfil . ': ' . $stmt->error);
        }
    }
    
    $conexao->commit();
    header('Location: tela_parametros_permissoes.php?igreja=' . $igreja_id . '&sucesso=1');
    exit();
    
} catch (Exception $e) {
    $conexao->rollback();
    die('ERRO: ' . $e->getMessage());
}
?>