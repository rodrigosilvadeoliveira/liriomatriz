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
/*LOCAL
==============================
BUSCAR VOLUNTÁRIOS + FUNÇÕES
==============================
*/

$sql = "
SELECT 
    v.id,
    v.usuario,
    v.foto,
    GROUP_CONCAT(f.nome SEPARATOR ', ') as funcoes
FROM voluntarios v
LEFT JOIN voluntario_funcoes vf ON vf.voluntario_id = v.id
LEFT JOIN funcoes f ON f.id = vf.funcao_id
WHERE v.igreja_id = ?
GROUP BY v.id
ORDER BY v.usuario ASC
";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $igreja);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Voluntários</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body {
    font-family: Arial;
    background: #eef1f4;
}

.container {
    max-width: 1000px;
    margin: 30px auto;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

h2 {
    margin: 0;
}

.btn {
    padding: 10px 15px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
}

.btn-add {
    background: #28a745;
    color: white;
}

.btn-edit {
    background: #007bff;
    color: white;
}

.table {
    width: 100%;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    margin-top: 20px;
}

.table th, .table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    text-align: left;
}

.table th {
    background: #f8f9fa;
}

.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.search {
    margin-top: 15px;
}

.search input {
    width: 100%;
    padding: 10px;
}
</style>

</head>
<body>

<div class="container">

<div class="header">
    <h2><i class="fas fa-users"></i> Lista de Voluntários</h2>

    <!-- <a href="cadastro_voluntario.php" class="btn btn-add">
        <i class="fas fa-plus"></i> Novo
    </a> -->
</div>

<div class="search">
    <input type="text" id="search" placeholder="Buscar voluntário...">
</div>

<table class="table" id="tabela">
<thead>
<tr>
    <th>Foto</th>
    <th>Nome</th>
    <th>Funções</th>
    <th>Ações</th>
</tr>
</thead>

<tbody>

<?php while($row = $result->fetch_assoc()): ?>

<tr>
    <td>
        <img 
            src="<?= $row['foto'] ? $row['foto'] : 'assets/avatar-default.png' ?>" 
            class="avatar"
        >
    </td>

    <td><?= htmlspecialchars($row['usuario']) ?></td>

    <td><?= $row['funcoes'] ?: '-' ?></td>

    <td>
        <a href="edit_voluntarioescala.php?id=<?= $row['id'] ?>" class="btn btn-edit">
            <i class="fas fa-edit"></i>
        </a>
    </td>
</tr>

<?php endwhile; ?>

</tbody>
</table>

</div>

<script>
/*
==============================
BUSCA EM TEMPO REAL
==============================
*/
document.getElementById('search').addEventListener('keyup', function() {
    const value = this.value.toLowerCase();
    const rows = document.querySelectorAll('#tabela tbody tr');

    rows.forEach(row => {
        const nome = row.children[1].textContent.toLowerCase();
        row.style.display = nome.includes(value) ? '' : 'none';
    });
});
</script>

</body>
</html>