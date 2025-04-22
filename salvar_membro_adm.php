<?php
// Conexão com o banco de dados (ajuste conforme seu ambiente)
$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'liriomatriz';
$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica a conexão
if ($conn->connect_error) {
  die("Erro de conexão: " . $conn->connect_error);
}

// Recebendo dados do formulário
$nome             = $_POST['nome'] ?? '';
$sobrenome        = $_POST['sobrenome'] ?? '';
$nascimento       = $_POST['nascimento'] ?? '';
$batizado         = $_POST['batizado'] ?? '';
$datas            = $_POST['datas'] ?? '';
$telefone         = $_POST['telefone'] ?? '';
$email            = $_POST['email'] ?? '';
$voluntario       = $_POST['voluntario'] ?? '';
$lider            = $_POST['lider'] ?? '';
$departamentoum   = $_POST['departamentoum'] ?? '';
$departamentodois = $_POST['departamentodois'] ?? '';
$departamentotres = $_POST['departamentotres'] ?? '';
$status           = $_POST['status'] ?? '';
$responsavel      = $_POST['responsavel'] ?? '';
$foto_crop        = $_POST['foto_crop'] ?? '';

// Tratando a imagem recortada (base64)
$foto_nome = null;
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

// Inserindo no banco
$sql = "INSERT INTO membros (
    nome, sobrenome, nascimento, batizado, datas, telefone, email, 
    voluntario, lider, departamentoum, departamentodois, departamentotres, 
    status, responsavel, foto
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
  "sssssssssssssss",
  $nome, $sobrenome, $nascimento, $batizado, $datas, $telefone, $email,
  $voluntario, $lider, $departamentoum, $departamentodois, $departamentotres,
  $status, $responsavel, $foto_nome
);

if ($stmt->execute()) {
  echo "<script>alert('Membro cadastrado com sucesso!'); window.location.href='cadastroMembrosAdm.php';</script>";
} else {
  echo "Erro ao cadastrar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
