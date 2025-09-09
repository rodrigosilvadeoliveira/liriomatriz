<?php 
// Conexão com o banco de dados
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once('config.php');
if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

// Recebendo dados do formulário
$id = $_POST['id'] ?? '';
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
$foto_crop = $_POST['foto_crop'] ?? '';
$imagem_atual = $_POST['imagem_atual'] ?? ''; // campo hidden com a imagem antiga

// Buscar dados atuais do banco (inclusive imagem, nascimento e datas)
$sql_select = "SELECT nascimento, datas, foto FROM membros WHERE id = ?";
$stmt_select = $conexao->prepare($sql_select);
$stmt_select->bind_param("i", $id);
$stmt_select->execute();
$stmt_select->bind_result($nascimento_atual, $datas_atual, $foto_atual_banco);
$stmt_select->fetch();
$stmt_select->close();

// Usa os dados existentes se não vierem no POST
$nascimento = !empty($_POST['nascimento']) ? $_POST['nascimento'] : $nascimento_atual;
$datas = !empty($_POST['datas']) ? $_POST['datas'] : $datas_atual;

// Lógica de imagem
$foto_nome = $foto_atual_banco; // assume que manterá a atual
if (!empty($foto_crop)) {
    $dados_base64 = explode(',', $foto_crop);
    if (count($dados_base64) == 2) {
        $imagem_base64 = base64_decode($dados_base64[1]);
        $foto_nome = uniqid('foto_') . '.jpg';
        $caminho = 'uploads/' . $foto_nome;

        if (!file_exists('uploads')) {
            mkdir('uploads', 0755, true);
        }

        file_put_contents($caminho, $imagem_base64);
    }
}

// Atualização dos dados
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

$stmt = $conexao->prepare($sql);
if (!$stmt) {
    die("Erro na preparação da query: " . $conexao->error);
}

$stmt->bind_param(
    "sssssssssssssssi",
    $nome, $sobrenome, $nascimento, $batizado, $datas, $telefone, $email,
    $voluntario, $lider, $departamentosString, $departamentodois, $departamentotres,
    $status, $responsavel, $foto_nome, $id
);

if ($stmt->execute()) {
    echo "<script>alert('Membro atualizado com sucesso!'); window.location.href='loginMembro.php';</script>";
} else {
    echo "Erro ao atualizar: " . $stmt->error;
}

$stmt->close();
$conexao->close();
?>
