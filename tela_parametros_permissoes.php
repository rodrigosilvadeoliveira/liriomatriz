<?php
// tela_parametros_permissoes.php - versão completa com todas as permissões

date_default_timezone_set('America/Sao_Paulo');
include('verificarLogin.php');
verificarLogin();
include_once('config.php');
include("navegacao.php");

// Buscar igrejas
$sqlIgrejas = "SELECT id, nome FROM igrejas ORDER BY nome";
$resultIgrejas = $conexao->query($sqlIgrejas);

// Definir igreja selecionada
$igrejaSelecionada = $_GET['igreja'] ?? 1;

// Perfis padrão
$perfis = ['master', 'lider', 'consulta', 'ministro', 'secretaria'];

// Buscar permissões existentes
$permissoes = [];

$sql = "SELECT * FROM parametros_permissoes WHERE igreja_id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $igrejaSelecionada);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $permissoes[$row['perfil']] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Parâmetros de Permissões</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background-color: #f8f9fa;
}

.card {
    border-radius: 12px;
}

.table th {
    text-align: center;
    font-size: 13px;
}

.table td {
    text-align: center;
    vertical-align: middle;
}
</style>

</head>
<body>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header bg-primary text-white">
<h4 class="mb-0">⚙️ Parâmetros de Permissões por Igreja</h4>
</div>
<h4 class="mb-0">Selecione opções que deseja ocultar de acordo com o perfil</h4>
<div class="card-body">

<form method="GET">

<div class="row mb-3">

<div class="col-md-4">

<label class="form-label">Selecionar Igreja</label>

<select name="igreja" class="form-select" onchange="this.form.submit()">

<?php while ($igreja = $resultIgrejas->fetch_assoc()): ?>

<option value="<?= $igreja['id'] ?>"
<?= ($igrejaSelecionada == $igreja['id']) ? 'selected' : '' ?>>

<?= $igreja['id'] ?> - <?= $igreja['nome'] ?>

</option>

<?php endwhile; ?>

</select>

</div>

</div>

</form>

<form method="POST" action="salvar_permissoes.php">

<input type="hidden" name="igreja_id" value="<?= $igrejaSelecionada ?>">

<div class="table-responsive">

<table class="table table-bordered table-striped">

<thead class="table-light">
<tr>
<th>Perfil</th>
<th>Editar</th>
<th>Excluir</th>
<th>Repertório</th>
<th>Administração</th>
<th>Membros (Menu)</th>
<th>Membros (Aniversario,Novo)</th>
<th>Voluntário</th>
<th>Menu Criativo</th>
<th>Criativo (Escala)</th>
<th>Menu Dança</th>
<th>Dança (Escala)</th>
<th>Menu Kids</th>
<th>Kids (Escala)</th>
<th>Menu Louvor</th>
<th>Louvor (Escala)</th>
<th>Menu Mídias</th>
<th>Mídias (Escala)</th>
<th>Menu Som</th>
<th>Som (Escala)</th>
<th>Menu Staff</th>
<th>Staff (Escala)</th>
<th>Relatórios</th>
<th>Menu Recepção</th>
<th>Recepção (Escala)</th>
<th>Permissões</th>
</tr>
</thead>

<tbody>

<?php foreach ($perfis as $perfil): 

$editar = $permissoes[$perfil]['pode_editar'] ?? 0;
$excluir = $permissoes[$perfil]['pode_excluir'] ?? 0;
$ocultar = $permissoes[$perfil]['ocultar_opcoes'] ?? 0;
$administracao = $permissoes[$perfil]['ocultar_administracao'] ?? 0;
$membros = $permissoes[$perfil]['ocultar_membros'] ?? 0;
$membros1 = $permissoes[$perfil]['ocultar_membros1'] ?? 0;
$voluntario = $permissoes[$perfil]['ocultar_voluntario'] ?? 0;
$criativo = $permissoes[$perfil]['ocultar_criativo'] ?? 0;
$criativo1 = $permissoes[$perfil]['ocultar_criativo1'] ?? 0;
$danca = $permissoes[$perfil]['ocultar_danca'] ?? 0;
$danca1 = $permissoes[$perfil]['ocultar_danca1'] ?? 0;
$kids = $permissoes[$perfil]['ocultar_kids'] ?? 0;
$kids1 = $permissoes[$perfil]['ocultar_kids1'] ?? 0;
$louvor = $permissoes[$perfil]['ocultar_louvor'] ?? 0;
$louvor1 = $permissoes[$perfil]['ocultar_louvor1'] ?? 0;
$midias = $permissoes[$perfil]['ocultar_midias'] ?? 0;
$midias1 = $permissoes[$perfil]['ocultar_midias1'] ?? 0;
$som = $permissoes[$perfil]['ocultar_som'] ?? 0;
$som1 = $permissoes[$perfil]['ocultar_som1'] ?? 0;
$staff = $permissoes[$perfil]['ocultar_staff'] ?? 0;
$staff1 = $permissoes[$perfil]['ocultar_staff1'] ?? 0;
$relatorios = $permissoes[$perfil]['ocultar_relatorios'] ?? 0;
$recepcao = $permissoes[$perfil]['ocultar_recepcao'] ?? 0;
$recepcao1 = $permissoes[$perfil]['ocultar_recepcao1'] ?? 0;

$configuracao = $permissoes[$perfil]['ocultar_configuracao'] ?? 0;


?>

<tr>

<td>
<strong><?= ucfirst($perfil) ?></strong>
</td>

<td><input type="checkbox" name="permissoes[<?= $perfil ?>][editar]" value="1" <?= $editar ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][excluir]" value="1" <?= $excluir ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][ocultar]" value="1" <?= $ocultar ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][administracao]" value="1" <?= $administracao ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][membros]" value="1" <?= $membros ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][membros1]" value="1" <?= $membros1 ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][voluntario]" value="1" <?= $voluntario ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][criativo]" value="1" <?= $criativo ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][criativo1]" value="1" <?= $criativo1 ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][danca]" value="1" <?= $danca ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][danca1]" value="1" <?= $danca1 ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][kids]" value="1" <?= $kids ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][kids1]" value="1" <?= $kids1 ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][louvor]" value="1" <?= $louvor ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][louvor1]" value="1" <?= $louvor1 ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][midias]" value="1" <?= $midias ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][midias1]" value="1" <?= $midias1 ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][som]" value="1" <?= $som ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][som1]" value="1" <?= $som1 ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][staff]" value="1" <?= $staff ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][staff1]" value="1" <?= $staff1 ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][relatorios]" value="1" <?= $relatorios ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][recepcao]" value="1" <?= $recepcao ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][recepcao1]" value="1" <?= $recepcao1 ? 'checked' : '' ?>></td>
<td><input type="checkbox" name="permissoes[<?= $perfil ?>][configuracao]" value="1" <?= $configuracao ? 'checked' : '' ?>></td>



</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<div class="mt-3">

<button type="submit" class="btn btn-success">
💾 Salvar Permissões
</button>

<a href="paginainicial.php" class="btn btn-secondary">
↩️ Voltar
</a>

</div>

</form>

</div>

</div>

</div>

</body>
</html>