<?php
date_default_timezone_set('America/Sao_Paulo');
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');
include_once('config.php');

if((!isset($_SESSION['usuario']) == true) && ($_SESSION['senha']) == true) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
}

$logado = $_SESSION['usuario'];
$resultlist = null;

// Processamento das consultas (mantido igual)
if(!empty($_GET['search'])) {
    $data = $_GET['search'];
    $sql = "SELECT * FROM membros 
            WHERE id LIKE '%$data%' 
            OR nome LIKE '%$data%' 
            OR email LIKE '%$data%' 
            OR departamentos LIKE '%$data%'
            ORDER BY nome ASC";
    $resultlist = $conexao->query($sql);
} else if(isset($_GET['filtro'])) {
    $filtro = $_GET['filtro'];
    
    if($filtro == "ate1ano") {
        $sql = "SELECT * FROM membros 
                WHERE DATEDIFF(NOW(), datas) <= 365 
                ORDER BY nome ASC";
    } elseif($filtro == "ate5anos") {
        $sql = "SELECT * FROM membros 
                WHERE DATEDIFF(NOW(), datas) <= 365*5 
                ORDER BY nome ASC";
    } elseif($filtro == "mais5anos") {
        $sql = "SELECT * FROM membros 
                WHERE DATEDIFF(NOW(), datas) > 365*5 
                ORDER BY nome ASC";
    } elseif($filtro == "nomeAZ") {
        $sql = "SELECT * FROM membros 
                ORDER BY nome ASC";
    } elseif($filtro == "nomeZA") {
        $sql = "SELECT * FROM membros 
                ORDER BY nome DESC";
    } else {
        $sql = "SELECT * FROM membros ORDER BY id DESC";
    }
    $resultlist = $conexao->query($sql);
}
if ($resultlist === null) {
    $sql = "SELECT * FROM membros ORDER BY id DESC";
    $resultlist = $conexao->query($sql);
}
include('calculoMembros.php');

// Consulta para estatísticas
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';
$sqlMembros = "
    SELECT
        SUM(CASE WHEN idade <= 12 THEN 1 ELSE 0 END) AS idademenor,
        SUM(CASE WHEN idade >= 13 THEN 1 ELSE 0 END) AS idademaior
    FROM membros
    WHERE status = 'ativo'
";

if ($filtro == "ate1ano") {
    $sqlMembros .= " AND DATEDIFF(NOW(), datas) <= 365";
} elseif ($filtro == "ate5anos") {
    $sqlMembros .= " AND DATEDIFF(NOW(), datas) <= 1825";
} elseif ($filtro == "mais5anos") {
    $sqlMembros .= " AND DATEDIFF(NOW(), datas) > 1825";
}

$resultmembros = $conexao->query($sqlMembros);
?>
     
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Membros</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #6f42c1;
            --success-color: #1cc88a;
            --light-bg: #f8f9fc;
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* .navbar-custom {
            background-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        } */
        
        .sidebar {
            min-height: calc(100vh - 73px);
            background: linear-gradient(180deg, var(--primary-color) 10%, var(--secondary-color) 100%);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem;
            margin: 5px 0;
            border-radius: 5px;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        
        .card-dashboard {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }
        
        .card-dashboard:hover {
            transform: translateY(-5px);
        }
        
        .card-dashboard .card-body {
            padding: 1.5rem;
        }
        
        .stats-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .bg-primary-light {
            background-color: #e8eefa;
            color: var(--primary-color);
        }
        
        .bg-success-light {
            background-color: #e6faf3;
            color: var(--success-color);
        }
        
        .search-container {
            position: relative;
        }
        
        .search-container .search-input {
            padding-left: 40px;
            border-radius: 20px;
        }
        
        .search-container i {
            position: absolute;
            left: 15px;
            top: 12px;
            color: #6c757d;
        }
        
        .filter-section {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        
        .member-img {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 50%;
        }
        
        .badge-status {
            padding: 0.5em 0.8em;
            border-radius: 20px;
            font-size: 0.75em;
        }
        
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: #6e707e;
        }
        
        .action-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .export-btn {
            background-color: var(--success-color);
            border: none;
            border-radius: 20px;
            padding: 0.5rem 1.5rem;
        }
        
        .export-btn:hover {
            background-color: #17a673;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
            
            .stats-number {
                font-size: 1.5rem;
            }
            
            .search-form {
                margin-top: 15px;
            }
            
            .table-responsive {
                font-size: 0.875rem;
            }
            
            .member-img {
                width: 35px;
                height: 35px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <?php include("navegacao.php") ?>
    </nav>

    <div class="container-fluid" style="margin-top: 73px;">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 col-md-3 d-md-block sidebar collapse p-0">
                <div class="position-sticky pt-3">
                   
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-lg-10 col-md-9 ms-sm-auto px-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4 border-bottom">
                    <h1 class="h3">Consulta de Membros</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">Compartilhar</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">Exportar</button>
                        </div>
                    </div>
                </div>

                <!-- Welcome Message -->
                <div class="alert alert-primary mb-4">
                    <i class="fas fa-user me-2"></i> Bem-vindo, <strong><?php echo $logado; ?></strong>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card card-dashboard h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total de Membros</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php
                                            $sqlTotal = "SELECT COUNT(*) as total FROM membros";
                                            $resultTotal = $conexao->query($sqlTotal);
                                            $rowTotal = $resultTotal->fetch_assoc();
                                            echo $rowTotal['total'];
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stats-icon bg-primary-light">
                                            <i class="fas fa-users fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card card-dashboard h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Membros Ativos</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php
                                            $sqlAtivos = "SELECT COUNT(*) as ativos FROM membros WHERE status = 'ativo'";
                                            $resultAtivos = $conexao->query($sqlAtivos);
                                            $rowAtivos = $resultAtivos->fetch_assoc();
                                            echo $rowAtivos['ativos'];
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stats-icon bg-success-light">
                                            <i class="fas fa-user-check fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card card-dashboard h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Até 12 anos</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php
                                            $user_membros = mysqli_fetch_assoc($resultmembros);
                                            echo $user_membros['idademenor'];
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stats-icon bg-info-light">
                                            <i class="fas fa-child fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card card-dashboard h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Acima de 13 anos</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php echo $user_membros['idademaior']; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stats-icon bg-warning-light">
                                            <i class="fas fa-user fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="filter-section mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <form id="filtroMembros" method="GET" action="" class="mb-3">
                                <label for="filtro" class="form-label"><strong>Filtrar membros:</strong></label>
                                <div class="d-flex">
                                    <select name="filtro" id="filtro" class="form-select me-2" onchange="document.getElementById('filtroMembros').submit()">
                                        <option value="" disabled selected>-- Selecione um filtro --</option>    
                                        <option value="todos" <?php if(isset($_GET['filtro']) && $_GET['filtro']=="todos") echo "selected"; ?>>Todos membros</option>
                                        <option value="ate1ano" <?php if(isset($_GET['filtro']) && $_GET['filtro']=="ate1ano") echo "selected"; ?>>Membros até 1 ano</option>
                                        <option value="ate5anos" <?php if(isset($_GET['filtro']) && $_GET['filtro']=="ate5anos") echo "selected"; ?>>Membros até 5 anos</option>
                                        <option value="mais5anos" <?php if(isset($_GET['filtro']) && $_GET['filtro']=="mais5anos") echo "selected"; ?>>Membros acima de 5 anos</option>
                                        <option value="nomeAZ" <?php if(isset($_GET['filtro']) && $_GET['filtro']=="nomeAZ") echo "selected"; ?>>Nome A-Z</option>
                                        <option value="nomeZA" <?php if(isset($_GET['filtro']) && $_GET['filtro']=="nomeZA") echo "selected"; ?>>Nome Z-A</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Aplicar</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" action="" class="search-form">
                                <label for="search" class="form-label"><strong>Pesquisar:</strong></label>
                                <div class="search-container">
                                    <i class="fas fa-search"></i>
                                    <input type="text" class="form-control search-input" name="search" placeholder="Pesquisar por nome, email ou ID..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <form method="GET" action="relatorio_membros.php">
                            <input type="hidden" name="filtro" value="<?php echo isset($_GET['filtro']) ? $_GET['filtro'] : ''; ?>">
                            <button type="submit" class="btn btn-success export-btn">
                                <i class="fas fa-file-export me-2"></i>Exportar para Excel
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Members Table -->
                <div class="card card-dashboard">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0">Lista de Membros</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabelaMembros" class="table table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Foto</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Telefone</th>
                                        <th>Membro desde</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($resultlist && $resultlist->num_rows > 0) {
                                        while($user_data = mysqli_fetch_assoc($resultlist)) {
                                            echo "<tr>";
                                            echo "<td><img class='member-img' src='uploads/".$user_data['foto']."' onerror=\"this.src='https://via.placeholder.com/45?text=Sem+Imagem'\"></td>";
                                            echo "<td>".$user_data['nome']." ".$user_data['sobrenome']."</td>";
                                            echo "<td>".$user_data['email']."</td>";
                                            echo "<td>".$user_data['telefone']."</td>";
                                            echo "<td>".date('d/m/Y', strtotime($user_data['datas']))."</td>";
                                            
                                            // Status badge
                                            $status_class = $user_data['status'] == 'ativo' ? 'bg-success' : 'bg-secondary';
                                            echo "<td><span class='badge $status_class badge-status'>".$user_data['status']."</span></td>";
                                            
                                            echo "<td>
                                                <div class='btn-group'>
                                                    <a class='btn btn-sm btn-primary action-btn' href='edit_formularioMembros.php?id=$user_data[id]' title='Editar'>
                                                        <i class='fas fa-edit'></i>
                                                    </a>
                                                    <button class='btn btn-sm btn-info action-btn view-member' data-id='$user_data[id]' title='Visualizar'>
                                                        <i class='fas fa-eye'></i>
                                                    </button>
                                                </div>
                                            </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center py-4'>Nenhum membro encontrado. Tente alterar os filtros de pesquisa.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Member Detail Modal -->
    <div class="modal fade" id="memberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalhes do Membro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="memberDetails">
                    <!-- Details will be loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <a href="#" id="editMemberBtn" class="btn btn-primary">Editar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap & jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#tabelaMembros').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
                },
                order: [[1, 'asc']],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]]
            });
            
          // View member details
$(document).on('click', '.view-member', function() {
    var memberId = $(this).data('id');

    // Mostra carregando
    $('#memberDetails').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
            <p class="mt-2">Carregando informações do membro...</p>
        </div>
    `);

    // Atualiza link de edição
    $('#editMemberBtn').attr('href', 'edit_formularioMembros.php?id=' + memberId);

    // Abre modal com Bootstrap 5
    var modal = new bootstrap.Modal(document.getElementById('memberModal'));
    modal.show();

    // Requisição AJAX para buscar os dados do membro
    $.ajax({
        url: 'getMemberDetails.php',
        type: 'GET',
        data: { id: memberId },
        dataType: 'json',
        success: function(data) {
            if (data.error) {
                $('#memberDetails').html(`<p class="text-danger">${data.error}</p>`);
            } else {
                $('#memberDetails').html(`
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img id="modalMemberImg" src="uploads/${data.foto}" 
                                 class="img-fluid rounded-circle mb-3" 
                                 style="max-width: 150px;" 
                                 onerror="this.src='https://via.placeholder.com/150?text=Sem+Imagem'">
                            <h4 id="modalMemberName">${data.nome} ${data.sobrenome}</h4>
                            <p id="modalMemberStatus" class="badge ${data.status === 'ativo' ? 'bg-success' : 'bg-secondary'}">${data.status}</p>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-sm-6">
                                    <p><strong>Email:</strong> ${data.email}</p>
                                    <p><strong>Telefone:</strong> ${data.telefone}</p>
                                    <p><strong>Data de Nascimento:</strong> ${data.nascimento ?? ''}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p><strong>Batizado:</strong> ${data.batizado == 1 ? 'Sim' : 'Não'}</p>
                                    <p><strong>Membro desde:</strong> ${data.datas ?? ''}</p>
                                    <p><strong>Idade:</strong> ${data.idade ?? ''}</p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <p><strong>Voluntário:</strong> ${data.voluntario ?? ''}</p>
                                    <p><strong>Líder:</strong> ${data.lider ?? ''}</p>
                                    <p><strong>Departamentos:</strong> ${data.departamentos ?? ''}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
        },
        error: function() {
            $('#memberDetails').html(`<p class="text-danger">Erro ao carregar informações do membro.</p>`);
        }
    });
});

----------------
            // Auto logout after 1 hour
            setTimeout(() => {
                window.location.href = "sistema.php?timeout=1"; 
            }, 3600000);
        });
    </script>
</body>
</html>