<?php
// Conexão com o banco de dados (ajuste conforme seu ambiente)
ini_set('display_errors', 1); // Exibir erros no navegador (para fins de desenvolvimento)
error_reporting(E_ALL); // Relatar todos os tipos de erro (para fins de desenvolvimento)

include_once('config.php');
if ($conexao->connect_error) {
  die("Erro na conexão: " . $conexao->connect_error);
}


  $id = intval($_POST['id']);
  
$departamento   = $_POST['departamento'];
$evento         = $_POST['evento'];
$lideranca      = $_POST['lideranca'];
$data           = $_POST['data'];
$tema           = $_POST['tema'];
$qtdpresentes   = $_POST['qtdpresentes'];
$pessoas        = $_POST['pessoas'];
$atividades     = $_POST['atividades'];
$metas          = $_POST['metas'];
$dificuldades   = $_POST['dificuldades'];
$solucoes       = $_POST['solucoes'];
$materiais       = $_POST['materiais'];
$recursos       = $_POST['recursos'];
$proxatividade  = $_POST['proxatividade'];
$impacto        = $_POST['impacto'];

$sqlUpdate = "UPDATE relatorios SET departamento='$departamento', evento='$evento', lideranca='$lideranca', data='$data', tema='$tema', qtdpresentes='$qtdpresentes', pessoas='$pessoas', atividades='$atividades', metas='$metas', 
dificuldades='$dificuldades', solucoes='$solucoes', solucoes='$solucoes', materiais='$materiais', recursos='$recursos', proxatividade='$proxatividade' WHERE id=$id";

mysqli_query($conexao, "DELETE FROM relatorio_itens WHERE relatorio_id = $id");

foreach ($_POST['produto'] as $index => $produto) {
$valor = $_POST['valor'][$index];
if (!empty($produto) && $valor !== "") {
    $relatorio_id= mysqli_real_escape_string($conexao, $id);
    $produto = mysqli_real_escape_string($conexao, $produto);
    $valor = floatval($valor);
    mysqli_query($conexao, "INSERT INTO relatorio_itens (relatorio_id, nome_produto, valor) VALUES ('$relatorio_id', '$produto', $valor)");
}
}

  mysqli_query($conexao, $sqlUpdate);
  header('Location: listar_relatoriosdep.php');
  exit;
  
  if ($stmtItem->execute()) {
    echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href='relatoriodepartamento.php';</script>";
  } else {
    echo "Erro ao cadastrar: " . $stmt->error;
  }

$stmtItem->close();
$conexao->close();
?>