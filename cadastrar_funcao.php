<?php
date_default_timezone_set('America/Sao_Paulo');

require_once('verificarLogin.php');
verificarLogin();
require_once('config.php');
include("navegacao.php");

$igreja = $_SESSION['igreja_id'] ?? 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastrar Função</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body {
    background: #f4f6f9;
}
.card {
    border-radius: 12px;
}
.btn i {
    pointer-events: none;
}
.bg-primary {
    --bs-bg-opacity: 1;
    background-color: #212529 !important;
    font-size: 18px;
}
.btn-edit {
    background: #007bff;
    color: white;
}
</style>
</head>

<body>

<div class="container py-5">

    <!-- CADASTRO -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h4 class="mb-3">
                <i class="fas fa-plus-circle"></i> Nova Função
            </h4>

            <form id="formFuncao" class="row g-3">

                <input type="hidden" name="igreja_id" value="<?= $igreja ?>">

                <div class="col-md-6">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" required>
                </div>

                <div class="col-md-6">
    <label class="form-label">Tipo</label>

    <select name="tipo" class="form-select" required>

        <option value="">Selecione</option>

        <?php

        // igreja da sessão
        $igreja = $_SESSION['igreja_id'];

        $sqlCategorias = "
            SELECT DISTINCT tipo
            FROM categoria_escala
            WHERE igreja_id = ?
            ORDER BY tipo ASC
        ";

        $stmtCategorias = $conexao->prepare($sqlCategorias);
        $stmtCategorias->bind_param("i", $igreja);
        $stmtCategorias->execute();

        $resultCategorias = $stmtCategorias->get_result();

        if ($resultCategorias && $resultCategorias->num_rows > 0) {

            while ($categoria = $resultCategorias->fetch_assoc()) {

                $tipo = trim($categoria['tipo']);

                // primeira letra maiúscula
                $tipoFormatado = mb_convert_case(
                    $tipo,
                    MB_CASE_TITLE,
                    "UTF-8"
                );

                echo '
                    <option value="'.$tipo.'">
                        '.$tipoFormatado.'
                    </option>
                ';
            }
        }

        $stmtCategorias->close();

        ?>

    </select>
</div>

                <div class="col-12">
                    <button class="btn btn-success w-100">
                        <i class="fas fa-save"></i> Salvar Função
                    </button>
                </div>

            </form>

        </div>
    </div>

    <!-- LISTA -->
    <div class="card shadow-sm">
        <div class="card-body">

            <h4 class="mb-3">
                <i class="fas fa-list"></i> Funções cadastradas
            </h4>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>Departamento</th>
                            <th width="140">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="listaFuncoes"></tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditar" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Editar Função</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="edit_id">

        <div class="mb-3">
            <label>Nome</label>
            <input type="text" id="edit_nome" class="form-control">
        </div>

        <div class="mb-3">
            <label>Tipo</label>
            <select id="edit_tipo" class="form-select" required>

        <option value="">Selecione</option>

        <?php

        // igreja da sessão
        $igreja = $_SESSION['igreja_id'];

        $sqlCategorias = "
            SELECT DISTINCT tipo
            FROM categoria_escala
            WHERE igreja_id = ?
            ORDER BY tipo ASC
        ";

        $stmtCategorias = $conexao->prepare($sqlCategorias);
        $stmtCategorias->bind_param("i", $igreja);
        $stmtCategorias->execute();

        $resultCategorias = $stmtCategorias->get_result();

        if ($resultCategorias && $resultCategorias->num_rows > 0) {

            while ($categoria = $resultCategorias->fetch_assoc()) {

                $tipo = trim($categoria['tipo']);

                // primeira letra maiúscula
                $tipoFormatado = mb_convert_case(
                    $tipo,
                    MB_CASE_TITLE,
                    "UTF-8"
                );

                echo '
                    <option value="'.$tipo.'">
                        '.$tipoFormatado.'
                    </option>
                ';
            }
        }

        $stmtCategorias->close();

        ?>

    </select>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary" onclick="salvarEdicao()">Salvar</button>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// =======================
// CADASTRAR
// =======================
document.getElementById('formFuncao').addEventListener('submit', function(e){
    e.preventDefault();

    const formData = new FormData(this);

    fetch('salvar_funcao.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        if(res.success){
            this.reset();
            carregarFuncoes();
        } else {
            alert(res.message);
        }
    })
    .catch(() => alert('Erro ao cadastrar'));
});

// =======================
// LISTAR
// =======================
function carregarFuncoes(){
    fetch('listar_funcoes.php')
    .then(r => r.json())
    .then(res => {
        const tbody = document.getElementById('listaFuncoes');
        tbody.innerHTML = '';

        if(!res.success || res.data.length === 0){
            tbody.innerHTML = '<tr><td colspan="3">Nenhuma função cadastrada</td></tr>';
            return;
        }

        res.data.forEach(f => {
            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>${f.nome}</td>
                <td><span class="badge bg-primary">${f.tipo}</span></td>
                <td>
                    <button class="btn btn-sm btn-edit" onclick="editar(${f.id}, '${f.nome}', '${f.tipo}')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="excluir(${f.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;

            tbody.appendChild(tr);
        });
    });
}

// =======================
// ABRIR MODAL
// =======================
function editar(id, nome, tipo){
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nome').value = nome;
    document.getElementById('edit_tipo').value = tipo;

    new bootstrap.Modal(document.getElementById('modalEditar')).show();
}

// =======================
// SALVAR EDIÇÃO
// =======================
function salvarEdicao(){
    const formData = new FormData();
    formData.append('id', document.getElementById('edit_id').value);
    formData.append('nome', document.getElementById('edit_nome').value);
    formData.append('tipo', document.getElementById('edit_tipo').value);

    fetch('editar_funcao.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        if(res.success){
            carregarFuncoes();
            bootstrap.Modal.getInstance(document.getElementById('modalEditar')).hide();
        } else {
            alert(res.message);
        }
    });
}

// =======================
// EXCLUIR
// =======================
function excluir(id){
    if(!confirm('Deseja excluir esta função?')) return;

    fetch('excluir_funcao.php', {
        method: 'POST',
        body: new URLSearchParams({ id })
    })
    .then(r => r.json())
    .then(res => {
        if(res.success){
            carregarFuncoes();
        } else {
            alert(res.message);
        }
    });
}

// carregar ao abrir
window.onload = carregarFuncoes;
</script>

</body>
</html>