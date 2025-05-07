
<?php
ini_set('display_errors', 1); // Exibir erros no navegador (para fins de desenvolvimento)
error_reporting(E_ALL); // Relatar todos os tipos de erro (para fins de desenvolvimento)

require 'vendor/autoload.php'; // se usa Composer

use Dompdf\Dompdf;
use Dompdf\Options;

include_once('config.php');
$logoPath = 'file://' . realpath('lirioMatrizlogo.jpeg');
if ($conexao->connect_error) {
    die("Erro: " . $conexao->connect_error);
}

$relatorio_id = intval($_GET['id']);

// Busca dados do relatório
$rel = $conexao->query("SELECT * FROM relatorios WHERE id = $relatorio_id")->fetch_assoc();

// Busca itens financeiros
$itens = $conexao->query("SELECT * FROM relatorio_itens WHERE relatorio_id = $relatorio_id");


$imgPath = 'lirioMatrizlogo.jpeg';
if (file_exists($imgPath)) {
    $logoData = base64_encode(file_get_contents($imgPath));
    $logoSrc = 'data:image/jpeg;base64,' . $logoData;
} else {
    $logoSrc = ''; // ou imagem alternativa/base64 vazia
}

$html = "
<img src='$logoSrc' style='width: 150px;'><br>
<h2>Relatório ID #{$rel['id']}</h2>
<p><strong>Departamento:</strong> {$rel['departamento']}</p>
<p><strong>Liderança:</strong> {$rel['lideranca']}</p>
<p><strong>Evento:</strong> {$rel['evento']}</p>
<p><strong>Data:</strong> {$rel['data']}</p>
<p><strong>Tema:</strong> {$rel['tema']}</p>
<p><strong>Qtd. Presentes:</strong> {$rel['qtdpresentes']}</p>
<p><strong>Pessoas/Casais:</strong> {$rel['pessoas']}</p>
<p><strong>Descrição das atividades: (Relato das atividades ou eventos realizados pelo g durante 
o encontro):</strong> {$rel['atividades']}</p>
<p><strong>Metas alcançadas: (Quais objetivos ou metas foram atingidos.):</strong> {$rel['metas']}</p>
<p><strong>Dificuldades enfrentadas: (Quais desafios ou obstáculos surgiram durante a 
execução das atividades.):</strong> {$rel['dificuldades']}</p>
<p><strong>Soluções implementadas: (Estratégias adotadas para superar os problemas 
encontrados/ Junto com a Pastora):</strong> {$rel['solucoes']}</p>
<p><strong>Recursos materiais: Materiais necessários (como equipamentos, espaço, materiais gráficos, 
alimentos, etc.):</strong> {$rel['materiais']}</p>
<p><strong>Recursos humanos: (Número de voluntários ou membros envolvidos no ministério.):</strong> {$rel['recursos']}</p>
<p><strong>Próximas atividades: (O que está planejado para o próximo período.):</strong> {$rel['proxatividade']}</p>
<p><strong>mpacto no ministério e na igreja: (Como as atividades influenciaram a vida espiritual 
dos membros, a comunidade ou o crescimento do ministério.):</strong> {$rel['impacto']}</p>

<h3>Recursos Financeiros:</h3>
<table border='1' cellspacing='0' cellpadding='5' width='100%'>
  <tr><th>Produto</th><th>Valor (R$)</th></tr>";


while ($item = $itens->fetch_assoc()) {
    $html .= "<tr><td>{$item['nome_produto']}</td><td>R$ " . number_format($item['valor'], 2, ',', '.') . "</td></tr>";
}

$html .= "</table><p>Lirio Matris: Rua Amanari, 629 - Vila Santa Teresinha, São Paulo - SP, 08247-060 - Brasil

+55 (11) 2071-3218 - liriomatrizorganizacao@gmail.com WhatssApp: 11 - 94860-4083</p>";

$conexao->close();

// Configura o PDF
$options = new Options();
$options->set('defaultFont', 'Arial');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Envia o PDF para o navegador
$dompdf->stream("relatorio_{$rel['id']}.pdf", ["Attachment" => false]);
exit;
?>
