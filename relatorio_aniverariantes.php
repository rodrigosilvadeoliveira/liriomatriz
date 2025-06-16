<?php
if (!isset($_GET['mes'])) {
    die("Mês não informado.");
}

$mes = (int) $_GET['mes'];

// Conexão com banco de dados
include_once('config.php');

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=aniversariantes_mes_$mes.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='1'>";
echo "<tr>
        <th>#</th>
        <th>Nome</th>
        <th>Sobrenome</th>
        <th>Nascimento</th>
        <th>Membro desde</th>
        <th>Telefone</th>
      </tr>";

$sql = "SELECT nome, nascimento, sobrenome, datas, telefone FROM membros WHERE MONTH(nascimento) = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $mes);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $contador = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $contador++ . "</td>";
        echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
        echo "<td>" . htmlspecialchars($row['sobrenome']) . "</td>";
        echo "<td>" . date('d/m/Y', strtotime($row['nascimento'])) . "</td>";
        echo "<td>" . date('d/m/Y', strtotime($row['datas'])) . "</td>";
        echo "<td>" . htmlspecialchars($row['telefone']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>Nenhum aniversariante encontrado para este mês.</td></tr>";
}
$imageFile = $row['foto'];

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
  
echo "</table>";
$stmt->close();
$conexao->close();
?>
