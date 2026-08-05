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

<title>Cadastro de Departamentos</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body{
    background: #f4f6f9;
}

.card{
    border-radius: 12px;
}

.btn i{
    pointer-events: none;
}

.bg-primary{
    --bs-bg-opacity: 1;
    background-color: #212529 !important;
    font-size: 15px;
}

.btn-edit{
    background: #007bff;
    color: #fff;
}
</style>
</head>

<body>

<div class="container py-5">

    <!-- CADASTRO -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h4 class="mb-4">
                <i class="fas fa-layer-group"></i>
                Novo Departamento
            </h4>

            <form id="formDepartamento" class="row g-3">

                <input type="hidden" name="igreja_id" value="<?= $igreja ?>">

                <div class="col-md-12">
                    <label class="form-label">Nome do Departamento</label>

                    <input
                        type="text"
                        name="tipo"
                        class="form-control"
                        placeholder="Ex: Louvor, Mídia, Recepção..."
                        required
                    >
                </div>

                <div class="col-12">
                    <button class="btn btn-success w-100">
                        <i class="fas fa-save"></i>
                        Salvar Departamento
                    </button>
                </div>

            </form>

        </div>
    </div>

    <!-- LISTA -->
    <div class="card shadow-sm">
        <div class="card-body">

            <h4 class="mb-3">
                <i class="fas fa-list"></i>
                Departamentos cadastrados
            </h4>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Departamento</th>
                            <th width="140">Ações</th>
                        </tr>
                    </thead>

                    <tbody id="listaDepartamentos">
                        <tr>
                            <td colspan="2">Carregando...</td>
                        </tr>
                    </tbody>

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

                <h5 class="modal-title">
                    Editar Departamento
                </h5>

                <button class="btn-close" data-bs-dismiss="modal"></button>

            </div>

            <div class="modal-body">

                <input type="hidden" id="edit_id">

                <div class="mb-3">

                    <label class="form-label">
                        Nome do Departamento
                    </label>

                    <input
                        type="text"
                        id="edit_tipo"
                        class="form-control"
                    >

                </div>

            </div>

            <div class="modal-footer">

                <button
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancelar
                </button>

                <button
                    class="btn btn-primary"
                    onclick="salvarEdicao()"
                >
                    Salvar
                </button>

            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>

// =======================
// CADASTRAR
// =======================
document
.getElementById('formDepartamento')
.addEventListener('submit', function(e){

    e.preventDefault();

    const formData = new FormData(this);

    fetch('salvar_departamento.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(res => {

        if(res.success){

            this.reset();

            carregarDepartamentos();

        }else{

            alert(res.message);
        }

    })
    .catch(() => {
        alert('Erro ao cadastrar departamento');
    });

});

// =======================
// LISTAR
// =======================
function carregarDepartamentos(){

    fetch('listar_departamentos.php')

    .then(r => r.json())

    .then(res => {

        const tbody = document.getElementById('listaDepartamentos');

        tbody.innerHTML = '';

        if(!res.success || res.data.length === 0){

            tbody.innerHTML = `
                <tr>
                    <td colspan="2">
                        Nenhum departamento cadastrado
                    </td>
                </tr>
            `;

            return;
        }

        res.data.forEach(dep => {

            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>
                    <span class="badge bg-primary p-2">
                        ${dep.tipo}
                    </span>
                </td>

                <td>

                    <button
                        class="btn btn-sm btn-edit"
                        onclick="editar(${dep.id}, '${dep.tipo}')"
                    >
                        <i class="fas fa-edit"></i>
                    </button>

                    <button
                        class="btn btn-sm btn-danger"
                        onclick="excluir(${dep.id})"
                    >
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
function editar(id, tipo){

    document.getElementById('edit_id').value = id;
    document.getElementById('edit_tipo').value = tipo;

    new bootstrap.Modal(
        document.getElementById('modalEditar')
    ).show();

}

// =======================
// SALVAR EDIÇÃO
// =======================
function salvarEdicao(){

    const formData = new FormData();

    formData.append(
        'id',
        document.getElementById('edit_id').value
    );

    formData.append(
        'tipo',
        document.getElementById('edit_tipo').value
    );

    fetch('editar_departamento.php', {
        method: 'POST',
        body: formData
    })

    .then(r => r.json())

    .then(res => {

        if(res.success){

            carregarDepartamentos();

            bootstrap.Modal
            .getInstance(
                document.getElementById('modalEditar')
            )
            .hide();

        }else{

            alert(res.message);
        }

    });

}

// =======================
// EXCLUIR
// =======================
function excluir(id){

    if(!confirm('Deseja excluir este departamento?')){
        return;
    }

    fetch('excluir_departamento.php', {
        method: 'POST',
        body: new URLSearchParams({ id })
    })

    .then(r => r.json())

    .then(res => {

        if(res.success){

            carregarDepartamentos();

        }else{

            alert(res.message);
        }

    });

}

// =======================
// INIT
// =======================
window.onload = carregarDepartamentos;

</script>

</body>
</html>