<?php
// Conexão com o banco de dados (ajuste conforme seu ambiente)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('config.php');

// Verifica a conexão
if ($conexao->connect_error) {
  die("Erro de conexão: " . $conexao->connect_error);
}

// Recebendo dados do formulário
$nome       = $_POST['nome'] ?? '';
$bateria    = $_POST['bateria'] ?? '';
$violao     = $_POST['violao'] ?? '';
$teclado    = $_POST['teclado'] ?? '';
$baixo      = $_POST['baixo'] ?? '';
$ministro   = $_POST['ministro'] ?? '';
$vocal1     = $_POST['vocal1'] ?? '';
$vocal2     = $_POST['vocal2'] ?? '';
$vocal3     = $_POST['vocal3'] ?? '';
$talckback  = $_POST['talckback'] ?? '';
$igreja     = $_POST['igreja'] ?? '';
$live       = $_POST['live'] ?? '';
$somkids    = $_POST['somkids'] ?? '';
$ct         = $_POST['ct'] ?? '';
$c1         = $_POST['c1'] ?? '';
$c2         = $_POST['c2'] ?? '';
$lt         = $_POST['lt'] ?? '';
$lz         = $_POST['lz'] ?? '';
$ph         = $_POST['ph'] ?? '';
$foto_crop  = $_POST['foto_crop'] ?? '';

// Tratando a imagem recortada (base64)
$foto_nome = null;
if (!empty($foto_crop)) {
  $dados_base64 = explode(',', $foto_crop);
  $imagem_base64 = base64_decode($dados_base64[1]);

  // Cria nome único para a imagem
  $nome_arquivo = uniqid('foto_') . '.png';

  // Caminho absoluto da pasta img/fotoescala
  $pasta_destino = __DIR__ . '/img/fotoescala/';

  // Garante que a pasta exista
  if (!file_exists($pasta_destino)) {
    mkdir($pasta_destino, 0755, true);
  }

  // Caminho completo para salvar no servidor
  $caminho_completo = $pasta_destino . $nome_arquivo;

  // Salva a imagem no servidor
  file_put_contents($caminho_completo, $imagem_base64);

  // Caminho relativo para salvar no banco (como você pediu)
  $foto_nome = './img/fotoescala/' . $nome_arquivo;
}

// Inserindo no banco
$sql = "INSERT INTO musicos (
    nome, bateria, violao, teclado, baixo, ministro, vocal1, 
    vocal2, vocal3, talckback, igreja, live, somkids, ct, c1, c2, lt, lz, ph, foto
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexao->prepare($sql);
$stmt->bind_param(
  "ssssssssssssssssssss",
  $nome, $bateria, $violao, $teclado, $baixo, $ministro, $vocal1,
  $vocal2, $vocal3, $talckback, $igreja, $live, $somkids,
  $ct, $c1, $c2, $lt, $lz, $ph, $foto_nome
);

if ($stmt->execute()) {
  echo "<script>alert('Voluntariado cadastrado com sucesso!'); window.location.href='cadastrovoluntariadoescala.php';</script>";
} else {
  echo "Erro ao cadastrar: " . $stmt->error;
}

$stmt->close();
$conexao->close();
?>
