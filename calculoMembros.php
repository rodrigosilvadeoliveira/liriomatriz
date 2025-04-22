<?php
// Conectar ao banco de dados
$pdo = new PDO("mysql:host=localhost;dbname=liriomatriz", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Consulta para obter as somas das idades
$sql = "SELECT 
            COUNT(CASE WHEN idade <= 12 AND status = 'Ativo' THEN 1 END) AS qtd_idades_menor,
            COUNT(CASE WHEN idade >= 13 AND status = 'Ativo' THEN 1 END) AS qtd_idades_maior
        FROM membros";
$stmt = $pdo->query($sql);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Atualizar os valores na tabela total
if ($result) {
    $sqlUpdate = "UPDATE totalmembros SET idademenor = :qtd_idades_menor, idademaior = :qtd_idades_maior";
    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->execute([
        ':qtd_idades_menor' => $result['qtd_idades_menor'],
        ':qtd_idades_maior' => $result['qtd_idades_maior']
    ]);

    echo "Dados atualizados com sucesso!";
} else {
    echo "Erro ao calcular os totais.";
}
?>
