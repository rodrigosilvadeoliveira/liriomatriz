<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liro Matriz Loja</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<img id="logo" src ="lirioMatriz_preto.png">
    <h1 id="lirio">Sistema Lirio Matriz</h1>
    <div class="boxlogin">
    <a href="loginAdmin.php"><button class="buttonlogin">Acesso Admin - Loja</button></a>
    <a href="loginVoluntario.php"><button class="buttonlogin">Acesso Voluntário - Loja</button></a>
    <a href="loginIgreja.php"><button class="buttonlogin">Igreja</button></a>
    <a href="index.php"><button class="buttonlogin">Site</button></a>
    </div>
    <!-- <div class="boxlogin">
        <a href="loginAdmin.php" id="botaoadm">Administrador</a>
</div>
        <div class="boxloginVol">
        <a href="loginVoluntario.php" id="botaovol">Voluntário</a>
</div>
<div class="boxloginIgr">
        <a href="loginAdmin.php" id="botaoigr">Igreja</a>
</div> -->
        <!--
<a href="formulario.php" id="cadastre">Cadastre-se</a>"
//-->
<script>
    // Tempo de inatividade em milissegundos (1 hora = 3600000 ms)
    const tempoLimite = 3600000;

    // Redireciona para logout após o tempo limite
    setTimeout(() => {
        window.location.href = "sistema.php?timeout=1"; 
    }, tempoLimite);
</script>

</body>
</html>


-----------------
<?php
// Inclui os arquivos de configuração e segurança
include('verificarLogin.php');
verificarLogin();

include_once('config.php');

// Inclui a biblioteca FPDI, responsável por manipular e unificar PDFs
require_once('vendor/autoload.php');
use setasign\Fpdi\Fpdi;

// Verifica se a requisição foi um POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Validação dos dados
    // Verifica se a data e a lista de músicas foram enviadas
    if (empty($_POST['data_repertorio']) || empty($_POST['musicasSelecionadasFinal'])) {
        header('Location: musicas.php?error=dados_ausentes');
        exit;
    }

    $dataRepertorio = $_POST['data_repertorio'];
    $musicasIDs = explode(',', $_POST['musicasSelecionadasFinal']);

    // 2. Preparação dos arquivos e variáveis
    // Formata a data para o nome do arquivo (DD_MM_AAAA)
    $dataFormatada = date('d_m_Y', strtotime($dataRepertorio));
    $nomeArquivoFinal = $dataFormatada . '.pdf';
    $caminhoArquivoFinal = 'uploads/repertorios/' . $nomeArquivoFinal;
    $caminhoBanco = 'repertorios/' . $nomeArquivoFinal; // Caminho para salvar no banco

    // Garante que a pasta de destino exista
    $pastaDestino = 'uploads/repertorios/';
    if (!is_dir($pastaDestino)) {
        mkdir($pastaDestino, 0777, true);
    }

    // Inicializa o objeto FPDI e os arrays para os dados
    $pdf = new Fpdi();
    $nomesMusicas = [];
    $idsIn = implode(',', array_map('intval', $musicasIDs));

    // 3. Busca e ordenação das músicas no banco de dados
    // A função FIELD() ordena os resultados da consulta na mesma ordem dos IDs que foram enviados.
    $sql = "SELECT nome, arquivo FROM musicas WHERE id IN ($idsIn) ORDER BY FIELD(id, $idsIn)";
    $result = $conexao->query($sql);

    // 4. Compilação dos PDFs
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $filePath = 'uploads/musicas/' . $row['arquivo'];
            $nomesMusicas[] = $row['nome']; // Armazena o nome da música para o registro no banco
            
            if (file_exists($filePath)) {
                try {
                    // Adiciona as páginas do PDF atual ao arquivo final
                    $pageCount = $pdf->setSourceFile($filePath);
                    for ($i = 1; $i <= $pageCount; $i++) {
                        $templateId = $pdf->importPage($i);
                        $size = $pdf->getTemplateSize($templateId);
                        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                        $pdf->useTemplate($templateId);
                    }
                } catch (\Exception $e) {
                    // Trata erros ao processar PDFs inválidos
                    // Você pode logar o erro aqui para debug se necessário
                }
            }
        }
    }
    
    // Converte o array de nomes para uma string separada por vírgula
    $nomesMusicasStr = implode(', ', $nomesMusicas);

    // 5. Salva o PDF no servidor
    // 'F' significa que o arquivo será salvo em um arquivo local
    $pdf->Output('F', $caminhoArquivoFinal);

    // 6. Salva os dados no banco de dados
    $sqlInsert = "INSERT INTO repertorio (data_repertorio, arquivo_repertorio, nome_musicas, criado_em) VALUES (?, ?, ?, NOW())";
    $stmt = $conexao->prepare($sqlInsert);
    
    // "sss" indica que os 3 parâmetros são strings
    $stmt->bind_param("sss", $dataRepertorio, $caminhoBanco, $nomesMusicasStr);
    
    if ($stmt->execute()) {
        header('Location: musicas.php?success=repertorio_created');
    } else {
        header('Location: musicas.php?error=db_error');
    }

    $stmt->close();
    $conexao->close();

} else {
    // Redireciona se a requisição não for POST
    header('Location: musicas.php');
    exit;
}
?>
//composer update e atualizar composer.json "setasign/fpdf": "^1.8.6",