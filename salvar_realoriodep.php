<?php
// Conexão com o banco de dados (ajuste conforme seu ambiente)
ini_set('display_errors', 1); // Exibir erros no navegador (para fins de desenvolvimento)
error_reporting(E_ALL); // Relatar todos os tipos de erro (para fins de desenvolvimento)

include_once('config.php');
if ($conexao->connect_error) {
  die("Erro na conexão: " . $conexao->connect_error);
}

// 1. Pega os dados principais do relatório
$departamento   = $_POST['departamento'];
$evento         = $_POST['evento'];
$data           = $_POST['data'];
$tema           = $_POST['tema'];
$qtdpresentes   = $_POST['qtdpresentes'];
$atividades     = $_POST['atividades'];
$metas          = $_POST['metas'];
$dificuldades   = $_POST['dificuldades'];
$solucoes       = $_POST['solucoes'];
$recursos       = $_POST['recursos'];
$proxatividade  = $_POST['proxatividade'];
$impacto        = $_POST['impacto'];

// 2. Insere os dados principais
$stmt = $conexao->prepare("INSERT INTO relatorios (departamento, evento, data, tema, qtdpresentes, atividades, metas, dificuldades, solucoes, recursos, proxatividade, impacto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssisssssss", $departamento, $evento, $data, $tema, $qtdpresentes, $atividades, $metas, $dificuldades, $solucoes, $recursos, $proxatividade, $impacto);
$stmt->execute();

// 3. Pega o ID do relatório recém-inserido
$relatorio_id = $stmt->insert_id;
$stmt->close();

// 4. Salva os produtos e valores financeiros
$produtos = $_POST['produto'];
$valores = $_POST['valor'];

$stmtItem = $conexao->prepare("INSERT INTO relatorio_itens (relatorio_id, nome_produto, valor) VALUES (?, ?, ?)");
$stmtItem->bind_param("isd", $relatorio_id, $produto, $valor);

for ($i = 0; $i < count($produtos); $i++) {
  $produto = $produtos[$i];
  $valor = floatval($valores[$i]);
  
  if ($stmtItem->execute()) {
    echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href='relatorioDepartamento.php';</script>";
  } else {
    echo "Erro ao cadastrar: " . $stmt->error;
  }
}
$stmtItem->close();
$conexao->close();
?>