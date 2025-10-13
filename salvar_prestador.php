<?php
// Conexão com o banco de dados
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('config.php');

// Verifica a conexão
if ($conexao->connect_error) {
  die("Erro de conexão: " . $conexao->connect_error);
}

// Recebendo dados do formulário de prestador de serviços
$nome             = $_POST['nome'] ?? '';
$nome_empresa     = $_POST['nome_empresa'] ?? '';
$cpf_cnpj         = $_POST['cpf_cnpj'] ?? '';
$telefone         = $_POST['telefone'] ?? '';
$email            = $_POST['email'] ?? '';
$instagram        = $_POST['instagram'] ?? '';  // Novo campo
$cidade           = $_POST['cidade'] ?? '';
$estado           = $_POST['estado'] ?? '';
$experiencia      = $_POST['experiencia'] ?? '';
$valor_medio      = $_POST['valor_medio'] ?? '';
$descricao        = $_POST['descricao'] ?? '';
$disponibilidade  = $_POST['disponibilidade'] ?? '';
$raio_atendimento = $_POST['raio_atendimento'] ?? '';

// Tratando os serviços selecionados
$servicos = is_array($_POST['servicos'] ?? []) ? $_POST['servicos'] : [];
$servicosString = !empty($servicos) ? implode(",", $servicos) : '';

// Tratando a imagem recortada (base64)
$foto_nome = null;
$foto_crop = $_POST['foto_crop'] ?? '';

if (!empty($foto_crop)) {
  $dados_base64 = explode(',', $foto_crop);
  $imagem_base64 = base64_decode($dados_base64[1]);

  // Cria nome único para a imagem
  $foto_nome = uniqid('foto_') . '.jpg';
  $caminho = 'uploads/' . $foto_nome;

  // Garante que a pasta exista
  if (!file_exists('uploads')) {
    mkdir('uploads', 0755, true);
  }

  // Salva a imagem no servidor
  file_put_contents($caminho, $imagem_base64);
}

// Inserindo no banco de dados na tabela prestador
$sql = "INSERT INTO prestador (
    nome, 
    nome_empresa, 
    cpf_cnpj, 
    telefone, 
    email,
    instagram,  -- Novo campo
    cidade, 
    estado, 
    experiencia, 
    valor_medio, 
    servicos, 
    descricao, 
    disponibilidade, 
    raio_atendimento, 
    foto,
    data_cadastro,
    status
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'ativo')";

$stmt = $conexao->prepare($sql);

if ($stmt === false) {
    die("Erro na preparação da query: " . $conexao->error);
}

// Convertendo valor_medio para formato numérico do banco
$valor_medio_numerico = null;
if (!empty($valor_medio)) {
    $valor_medio_numerico = str_replace(['R$', '.', ','], ['', '', '.'], $valor_medio);
    $valor_medio_numerico = floatval($valor_medio_numerico);
}

// CORREÇÃO: String de tipos com 15 caracteres (era 14)
$stmt->bind_param(
  "sssssssisssssss",
  $nome, 
  $nome_empresa, 
  $cpf_cnpj, 
  $telefone, 
  $email, 
  $instagram,  // Novo campo
  $cidade, 
  $estado, 
  $experiencia, 
  $valor_medio_numerico, 
  $servicosString, 
  $descricao, 
  $disponibilidade, 
  $raio_atendimento, 
  $foto_nome
);

if ($stmt->execute()) {
  echo "<script>alert('Prestador de serviço cadastrado com sucesso!'); window.location.href='cadastroPrestador.php';</script>";
} else {
  echo "Erro ao cadastrar: " . $stmt->error;
}

$stmt->close();
$conexao->close();
?>