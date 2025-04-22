<?php
//Autoload do composer
require __DIR__.'/vendor/autoload.php';

// Dependências do projeto
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'liriomatriz';

// Estabelecer a conexão com o banco de dados
$conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

$sql = "SELECT * FROM produtos";

$result = $conexao->query($sql);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Defina o cabeçalho da tabela
$sheet->setCellValue('A1', 'id');
$sheet->setCellValue('B1', 'Código de barras');
$sheet->setCellValue('C1', 'produto');
$sheet->setCellValue('D1', 'modelo');
$sheet->setCellValue('E1', 'tamanho');
$sheet->setCellValue('F1', 'categoria');
$sheet->setCellValue('G1', 'valordevenda');
$sheet->setCellValue('H1', 'estoque');
$sheet->setCellValue('I1', 'valordecompra');
$sheet->setCellValue('J1', 'fornecedor');
$sheet->setCellValue('K1', 'data');
$sheet->setCellValue('L1', 'hora');

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
    $sheet->setCellValue('F' . $row, $row_data['categoria']);
    $sheet->setCellValue('G' . $row, $row_data['valordevenda']);
    $sheet->setCellValue('H' . $row, $row_data['estoque']);
    $sheet->setCellValue('I' . $row, $row_data['valordecompra']);
    $sheet->setCellValue('J' . $row, $row_data['fornecedor']);
    $sheet->setCellValue('K' . $row, $row_data['datas']);
    $sheet->setCellValue('L' . $row, $row_data['hora']);
    $row++;
}
}else {
    echo "Por favor, selecione as datas.";
}

// Defina o cabeçalho do arquivo para download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="estoque_completo.xlsx"');
header('Cache-Control: max-age=0');

// Crie um objeto Writer para salvar o arquivo Excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Encerre a conexão com o banco de dados
$conexao->close();
