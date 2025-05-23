<?php
require __DIR__.'/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

include_once('config.php');

$sql = "SELECT * FROM membros";
$result = $conexao->query($sql);

$sqlmembros = "SELECT * FROM totalmembros";
$resultmembros = $conexao->query($sqlmembros);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Cabeçalho
$sheet->setCellValue('A1', 'id');
$sheet->setCellValue('B1', 'Nome');
$sheet->setCellValue('C1', 'Sobrenome');
$sheet->setCellValue('D1', 'Data Nascimento');
$sheet->setCellValue('E1', 'Membro Desde');
$sheet->setCellValue('F1', 'Telefone');
$sheet->setCellValue('G1', 'Email');
$sheet->setCellValue('H1', 'Voluntário');
$sheet->setCellValue('I1', 'Lider');
$sheet->setCellValue('J1', 'departamentos');
$sheet->setCellValue('M1', 'Status');
$sheet->setCellValue('N1', 'Idade');
$sheet->setCellValue('O1', 'Responsavel');
$sheet->setCellValue('P1', 'Batizado');
$sheet->setCellValue('Q1', 'Foto');
$sheet->setCellValue('R1', 'Total até 12 anos');
$sheet->setCellValue('S1', 'Total a partir de 13 anos');


// Estilo
$styles = [
    'font' => ['bold' => true, 'size' => 10, 'name' => 'Calibri'],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
        'rotation' => 90,
        'startColor' => ['argb' => 'FFA0A0A0'],
        'endColor' => ['argb' => 'FFFFFFFF'],
    ],
    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT],
    'borders' => ['top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
];
$sheet->getStyle('A1:S1')->applyFromArray($styles);

// Inserir dados dos membros
$row = 2;
if ($result) {
    while ($row_data = $result->fetch_assoc()) {
        // Dados texto
        $sheet->setCellValue('A' . $row, $row_data['id']);
        $sheet->setCellValue('B' . $row, $row_data['nome']);
        $sheet->setCellValue('C' . $row, $row_data['sobrenome']);
        $sheet->setCellValue('D' . $row, $row_data['nascimento']);
        $sheet->setCellValue('E' . $row, $row_data['datas']);
        $sheet->setCellValue('F' . $row, $row_data['telefone']);
        $sheet->setCellValue('G' . $row, $row_data['email']);
        $sheet->setCellValue('H' . $row, $row_data['voluntario']);
        $sheet->setCellValue('I' . $row, $row_data['lider']);
        $sheet->setCellValue('J' . $row, $row_data['departamentos']);
        $sheet->setCellValue('M' . $row, $row_data['status']);
        $sheet->setCellValue('N' . $row, $row_data['idade']);
        $sheet->setCellValue('O' . $row, $row_data['responsavel']);
        $sheet->setCellValue('P' . $row, $row_data['batizado']);

        // Imagem
        $imageFile = $row_data['foto'];

if (!empty($imageFile)) {
    $imagePath = __DIR__ . '/uploads/' . $imageFile;

    if (file_exists($imagePath)) {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Foto');
        $drawing->setDescription('Foto do membro');
        $drawing->setPath($imagePath);
        $drawing->setHeight(80);
        $drawing->setCoordinates('Q' . $row);
        $drawing->setOffsetX(5);
        $drawing->setOffsetY(5);
        $drawing->setWorksheet($sheet);
        $sheet->getRowDimension($row)->setRowHeight(80);
    } else {
        $sheet->setCellValue('S' . $row, 'Imagem não encontrada');
    }
} else {
    $sheet->setCellValue('S' . $row, 'Sem imagem');
}



        $row++;
    }
} else {
    echo "Erro ao carregar membros.";
}

// Inserir total de membros
$row = 2;
if ($resultmembros) {
    while ($row_data = $resultmembros->fetch_assoc()) {
        $sheet->setCellValue('R' . $row, $row_data['idademenor']);
        $sheet->setCellValue('S' . $row, $row_data['idademaior']);
        $row++;
    }
} else {
    echo "Erro ao carregar totais.";
}

// Cabeçalhos HTTP para download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="lista_membros.xlsx"');
header('Cache-Control: max-age=0');

// Salvar
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Fechar conexão
$conexao->close();
