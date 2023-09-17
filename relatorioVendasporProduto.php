<?php
//Autoload do composer
require __DIR__.'/vendor/autoload.php';

// Dependências do projeto
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'lojalirio';

// Estabelecer a conexão com o banco de dados
$conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if (isset($_POST['data_inicio']) && isset($_POST['data_fim'])) {
    $inicio = $_POST['data_inicio'];
    $fim = $_POST['data_fim'];

$sql = "SELECT * FROM vendas WHERE datas BETWEEN '$inicio' AND '$fim'";
$result = $conexao->query($sql);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Defina o cabeçalho da tabela
$sheet->setCellValue('A1', 'id');
$sheet->setCellValue('B1', 'barra');
$sheet->setCellValue('C1', 'produto');
$sheet->setCellValue('D1', 'modelo');
$sheet->setCellValue('E1', 'tamanho');
$sheet->setCellValue('F1', 'valordevenda');
$sheet->setCellValue('G1', 'valordecompra');
$sheet->setCellValue('H1', 'usuario');
$sheet->setCellValue('I1', 'categoria');
$sheet->setCellValue('J1', 'datas');
$sheet->setCellValue('K1', 'hora');
$sheet->setCellValue('M1', 'Total Compras');
$sheet->setCellValue('N1', 'Total Vendas');
$sheet->setCellValue('O1', 'Lucro');
$sheet->setCellValue('P1', 'Caixa');
$sheet->setCellValue('M2', '=SUM(G2:G376)');
$sheet->setCellValue('N2', '=SUM(F2:F376)');
$sheet->setCellValue('O2', '=N2-M2');
$sheet->setCellValue('Q2', '=P2*35/100');



//Estilo da celula
$styles = [
    'font' => [
        'bold' => true,
        'color' => [
            'rgb' => ''
        ],
        'size' => 10,
        'name' => 'Caimbra'
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
        'rotation' => 90,
        'startColor' => [
            'argb' => 'FFA0A0A0',
        ],
        'endColor' => [
            'argb' => 'FFFFFFFF',
        ],
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
    ],
    'borders' => [
        'top' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
    
];

//Define o estilo da Celula cabeçalho
$sheet->getStyle('A1:S1')->applyFromArray($styles);

// Preencha os valores da tabela
if ($result) {
    $row = 2;
    while ($row_data = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $row_data['id']);
    $sheet->setCellValue('B' . $row, $row_data['barra']);
    $sheet->setCellValue('C' . $row, $row_data['produto']);
    $sheet->setCellValue('D' . $row, $row_data['modelo']);
    $sheet->setCellValue('E' . $row, $row_data['tamanho']);
    $sheet->setCellValue('F' . $row, $row_data['valordevenda']);
    $sheet->setCellValue('G' . $row, $row_data['valordecompra']);
    $sheet->setCellValue('H' . $row, $row_data['usuario']);
    $sheet->setCellValue('I' . $row, $row_data['categoria']);
    $sheet->setCellValue('J' . $row, $row_data['datas']);
    $sheet->setCellValue('k' . $row, $row_data['hora']);
    $row++;
}
}else {
    echo "Por favor, selecione as datas.";
}

// Defina o cabeçalho do arquivo para download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="produtos_vendidosPorDia.xlsx"');
header('Cache-Control: max-age=0');

// Crie um objeto Writer para salvar o arquivo Excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Encerre a conexão com o banco de dados
$conexao->close();
}
