<?php
include_once('config.php');

// Pega o mês da URL
$mes = isset($_GET['mes']) ? intval($_GET['mes']) : date('n');

// Consulta aniversariantes
$sql = "SELECT nome, sobrenome, nascimento, email, telefone, status 
        FROM membros 
        WHERE MONTH(nascimento) = $mes
        ORDER BY DAY(nascimento) ASC";
$result = $conexao->query($sql);

// Define headers para exportar em Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=aniversariantes_mes_$mes.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Cabeçalho da tabela
echo "<table border='1'>";
echo "<tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Telefone</th>
        <th>Data de Nascimento</th>
        <th>Status</th>
      </tr>";

// Linhas com os dados
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>".utf8_decode($row['nome']." ".$row['sobrenome'])."</td>";
        echo "<td>".utf8_decode($row['email'])."</td>";
        echo "<td>".utf8_decode($row['telefone'])."</td>";
        echo "<td>".date('d/m/Y', strtotime($row['nascimento']))."</td>";
        echo "<td>".utf8_decode($row['status'])."</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>Nenhum aniversariante encontrado.</td></tr>";
}
echo "</table>";
exit;
?>
