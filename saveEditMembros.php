<?php 
// Conexão com o banco de dados (ajuste conforme seu ambiente)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$host = 'localhost';
$usuario = 'u188478530_liriomatriz';
$senha = 'Lirio2025@';
$banco = 'u188478530_liriomatriz';
$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Recebendo dados do formulário (incluindo o ID do registro)
$id = $_POST['id'] ?? ''; // Adicione um campo hidden no formulário com o ID
$nome = $_POST['nome'] ?? '';
$sobrenome = $_POST['sobrenome'] ?? '';
$batizado = $_POST['batizado'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$email = $_POST['email'] ?? '';
$voluntario = $_POST['voluntario'] ?? '';
$lider = $_POST['lider'] ?? '';
$departamentos = is_array($_POST['departamentoum'] ?? []) ? $_POST['departamentoum'] : [];
$departamentosString = !empty($departamentos) ? implode(",", $departamentos) : '';
$departamentodois = $_POST['departamentodois'] ?? '';
$departamentotres = $_POST['departamentotres'] ?? '';
$status = $_POST['status'] ?? '';
$responsavel = $_POST['responsavel'] ?? '';
$foto = $_POST['foto'] ?? '';

// Tratamento especial para campos de data
// 1. Obter os valores atuais do banco de dados
$sql_select = "SELECT nascimento, datas FROM membros WHERE id = ?";
$stmt_select = $conn->prepare($sql_select);
$stmt_select->bind_param("i", $id);
$stmt_select->execute();
$stmt_select->bind_result($nascimento_atual, $datas_atual);
$stmt_select->fetch();
$stmt_select->close();

// 2. Definir os valores para atualização
$nascimento = (!empty($_POST['nascimento'])) ? $_POST['nascimento'] : $nascimento_atual;
$datas = (!empty($_POST['datas'])) ? $_POST['datas'] : $datas_atual;

// Query SQL para UPDATE
$sql = "UPDATE membros SET
    nome = ?,
    sobrenome = ?,
    nascimento = ?,
    batizado = ?,
    datas = ?,
    telefone = ?,
    email = ?,
    voluntario = ?,
    lider = ?,
    departamentos = ?,
    departamentodois = ?,
    departamentotres = ?,
    status = ?,
    responsavel = ?,
    foto = ?
WHERE id = ?";

// Prepara a query
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erro na preparação da query: " . $conn->error);
}

// Bind dos parâmetros (incluindo o ID no final)
$stmt->bind_param(
    "sssssssssssssssi",
    $nome, $sobrenome, $nascimento, $batizado, $datas, $telefone, $email,
    $voluntario, $lider, $departamentosString, $departamentodois, $departamentotres,
    $status, $responsavel, $foto, $id
);

// Executa a atualização
if ($stmt->execute()) {
    echo "<script>alert('Membro atualizado com sucesso!'); window.location.href='consulta_membros_busca.php';</script>";
} else {
    echo "Erro ao atualizar: " . $stmt->error;
}

// Fecha a conexão
$stmt->close();
$conn->close();
?>