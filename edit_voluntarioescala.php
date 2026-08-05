<?php
date_default_timezone_set('America/Sao_Paulo');

include_once('config.php');
session_start();
include("navegacao.php");

if((!isset($_SESSION['usuario']) == true) and ($_SESSION['senha']) == true) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
}

$logado = $_SESSION['usuario'];
$igreja = $_SESSION['igreja_id'];
$usuario_id = $_SESSION['usuario_id'] ?? 0;

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    die("ID inválido");
}

/*
==============================
BUSCAR VOLUNTÁRIO
==============================
*/
$stmt = $conexao->prepare("
    SELECT * FROM voluntarios 
    WHERE id = ? AND igreja_id = ?
");
$stmt->bind_param("ii", $id, $igreja);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    die("Voluntário não encontrado");
}

$voluntario = $res->fetch_assoc();

/*
==============================
BUSCAR FUNÇÕES AGRUPADAS
==============================
*/
$funcoesPorTipo = [];

$stmtFun = $conexao->prepare("
    SELECT id, nome, tipo 
    FROM funcoes 
    WHERE igreja_id = ?
    ORDER BY tipo ASC, nome ASC
");

$stmtFun->bind_param("i", $igreja);
$stmtFun->execute();

$resFun = $stmtFun->get_result();

while ($f = $resFun->fetch_assoc()) {
    $tipo = $f['tipo'] ?: 'Outros';
    $funcoesPorTipo[$tipo][] = $f;
}

/*
==============================
FUNÇÕES DO VOLUNTÁRIO
==============================
*/
$funcoesSelecionadas = [];

$stmt = $conexao->prepare("
    SELECT funcao_id 
    FROM voluntario_funcoes 
    WHERE voluntario_id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$resF = $stmt->get_result();

while ($r = $resF->fetch_assoc()) {
    $funcoesSelecionadas[] = $r['funcao_id'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Editar Voluntário</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body {
    font-family: Arial;
    background: #eef1f4;
}

.container {
    max-width: 700px;
    margin: 40px auto;
    background: white;
    padding: 25px;
    border-radius: 12px;
}

h2 {
    margin-bottom: 20px;
}

input[type=text] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
}

/* 🔥 BLOCO POR DEPARTAMENTO */
.grupo-funcoes {
    margin-bottom: 20px;
    padding: 15px;
    border-radius: 10px;
    background: #f8f9fa;
}

.grupo-funcoes h4 {
    margin-bottom: 10px;
    color: #007bff;
}

/* 🔥 CHECKBOX BONITO */
.funcoes {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
}

.funcoes label {
    background: white;
    padding: 8px;
    border-radius: 6px;
    cursor: pointer;
    border: 1px solid #ddd;
    transition: 0.2s;
}

.funcoes label:hover {
    background: #e9f3ff;
}

.funcoes input {
    margin-right: 6px;
}

.btn {
    padding: 12px;
    border: none;
    cursor: pointer;
    border-radius: 6px;
}

.btn-save {
    background: #28a745;
    color: white;
}

.btn-delete {
    background: #dc3545;
    color: white;
}

.btn-back {
    background: #6c757d;
    color: white;
}
</style>

</head>
<body>

<div class="container">

<h2><i class="fas fa-user-edit"></i> Editar Voluntário</h2>

<form id="formEdit">

<input type="hidden" name="id" value="<?= $voluntario['id'] ?>">

<label>Nome</label>
<input type="text" name="usuario" value="<?= htmlspecialchars($voluntario['usuario']) ?>" required>

<label>Funções</label>

<label>Funções</label>

<?php foreach ($funcoesPorTipo as $tipo => $lista): ?>

<div class="grupo-funcoes">

    <h4>
        <i class="fas fa-layer-group"></i>
        <?= htmlspecialchars($tipo) ?>
    </h4>

    <div class="funcoes">
        <?php foreach ($lista as $f): ?>
            <label>
                <input type="checkbox" 
                       name="funcoes[]" 
                       value="<?= $f['id'] ?>"
                       <?= in_array($f['id'], $funcoesSelecionadas) ? 'checked' : '' ?>>

                <?= htmlspecialchars($f['nome']) ?>
            </label>
        <?php endforeach; ?>
    </div>

</div>

<?php endforeach; ?>

<br>

<button type="submit" class="btn btn-save">
<i class="fas fa-save"></i> Salvar
</button>

<!-- <button type="button" onclick="excluir()" class="btn btn-delete">
<i class="fas fa-trash"></i> Excluir
</button> -->

<a href="lista_voluntarios.php" class="btn btn-back">
<i class="fas fa-arrow-left"></i> Voltar
</a>

</form>

</div>

<script>
document.getElementById('formEdit').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('atualizar_voluntario.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            alert('Atualizado com sucesso!');
            window.location.href = 'lista_voluntarios.php';
        } else {
            alert(res.message);
        }
    });
});

function excluir() {

    if (!confirm('Deseja excluir este voluntário?')) return;

    const id = document.querySelector('[name=id]').value;

    fetch('excluir_voluntario.php', {
        method: 'POST',
        body: new URLSearchParams({ id })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            alert('Excluído com sucesso!');
            window.location.href = 'lista_voluntarios.php';
        } else {
            alert(res.message);
        }
    });
}
</script>

</body>
</html>