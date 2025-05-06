<?php
ini_set('display_errors', 1); // Exibir erros no navegador (para fins de desenvolvimento)
error_reporting(E_ALL); // Relatar todos os tipos de erro (para fins de desenvolvimento)

require 'vendor/autoload.php'; // se usa Composer

use Dompdf\Dompdf;
use Dompdf\Options;

include_once('config.php');

if ($conexao->connect_error) {
    die("Erro: " . $conexao->connect_error);
}

$relatorio_id = intval($_GET['id']);

// Busca dados do relatório
$rel = $conexao->query("SELECT * FROM relatorios WHERE id = $relatorio_id")->fetch_assoc();

// Busca itens financeiros
$itens = $conexao->query("SELECT * FROM relatorio_itens WHERE relatorio_id = $relatorio_id");

$html = "
<h2>Relatório ID #{$rel['id']}</h2>
<p><strong>Departamento:</strong> {$rel['departamento']}</p>
<p><strong>Evento:</strong> {$rel['evento']}</p>
<p><strong>Data:</strong> {$rel['data']}</p>
<p><strong>Tema:</strong> {$rel['tema']}</p>
<p><strong>Qtd. Presentes:</strong> {$rel['qtdpresentes']}</p>
<p><strong>Atividades:</strong> {$rel['atividades']}</p>
<p><strong>Metas:</strong> {$rel['metas']}</p>
<p><strong>Dificuldades:</strong> {$rel['dificuldades']}</p>
<p><strong>Soluções:</strong> {$rel['solucoes']}</p>
<p><strong>Recursos:</strong> {$rel['recursos']}</p>
<p><strong>Próximas Atividades:</strong> {$rel['proxatividade']}</p>
<p><strong>Impacto:</strong> {$rel['impacto']}</p>

<h3>Recursos Financeiros:</h3>
<table border='1' cellspacing='0' cellpadding='5' width='100%'>
  <tr><th>Produto</th><th>Valor (R$)</th></tr>";

while ($item = $itens->fetch_assoc()) {
    $html .= "<tr><td>{$item['nome_produto']}</td><td>R$ " . number_format($item['valor'], 2, ',', '.') . "</td></tr>";
}

$html .= "</table>";

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
