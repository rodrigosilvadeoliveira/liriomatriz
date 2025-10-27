<?php
// Inclui os arquivos de configuração e segurança
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

include('registroslog.php');


// Inclui a biblioteca FPDI
require_once('vendor/autoload.php');
use setasign\Fpdi\Fpdi;

// Verifica se a requisição foi um POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verifica campos obrigatórios
    if (empty($_POST['data_repertorio']) || empty($_POST['musicasSelecionadasFinal']) || empty($_POST['periodo'])) {
        header('Location: musicas.php?error=dados_ausentes');
        exit;
    }

    $dataRepertorio = $_POST['data_repertorio'];
    $periodo = $_POST['periodo']; // <-- NOVO CAMPO
    $pares = explode(',', $_POST['musicasSelecionadasFinal']);

    // Quebra cada par id:ordem
    $musicasSelecionadas = [];
    foreach ($pares as $par) {
        list($id, $ordem) = explode(':', $par);
        $musicasSelecionadas[] = [
            'id' => (int)$id,
            'ordem' => (int)$ordem
        ];
    }

    // Ordena pelo campo ordem
    usort($musicasSelecionadas, function($a, $b) {
        return $a['ordem'] <=> $b['ordem'];
    });

    // 2. Preparação dos arquivos e variáveis
    $dataFormatada = date('d_m_Y', strtotime($dataRepertorio));
    $nomeArquivoFinal = "repertorio_{$dataFormatada}.pdf";
    $caminhoArquivoFinal = 'uploads/repertorios/' . $nomeArquivoFinal;
    $caminhoBanco = 'repertorios/' . $nomeArquivoFinal;

    // Garante que a pasta exista
    if (!is_dir('uploads/repertorios/')) {
        mkdir('uploads/repertorios/', 0777, true);
    }

    $pdf = new Fpdi();
    $nomesMusicas = [];

    // 3. Busca músicas em ordem e compila PDF
    foreach ($musicasSelecionadas as $musica) {
        $id = $musica['id'];
        $sql = "SELECT nome, arquivo FROM musicas WHERE id = $id LIMIT 1";
        $result = $conexao->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $filePath = 'uploads/musicas/' . $row['arquivo'];
            $nomesMusicas[] = $row['nome'];

            if (file_exists($filePath)) {
                try {
                    $pageCount = $pdf->setSourceFile($filePath);
                    for ($i = 1; $i <= $pageCount; $i++) {
                        $templateId = $pdf->importPage($i);
                        $size = $pdf->getTemplateSize($templateId);
                        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                        $pdf->useTemplate($templateId);
                    }
                } catch (\Exception $e) {
                    // Logar erro se necessário
                }
            }
        }
    }

    $nomesMusicasStr = implode(', ', $nomesMusicas);

    // 4. Salva o PDF no servidor
    $pdf->Output('F', $caminhoArquivoFinal);

    // 5. Salva no banco — AGORA COM O CAMPO "periodo"
    $sqlInsert = "INSERT INTO repertorio (data_repertorio, periodo, arquivo_repertorio, nome_musicas, criado_em) 
                  VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conexao->prepare($sqlInsert);
    $stmt->bind_param("ssss", $dataRepertorio, $periodo, $caminhoBanco, $nomesMusicasStr);

    if ($stmt->execute()) {
        header('Location: musicas.php?success=repertorio_created');
    } else {
        header('Location: musicas.php?error=db_error');
    }

    $stmt->close();
    $conexao->close();

} else {
    header('Location: musicas.php');
    exit;
}
?>
