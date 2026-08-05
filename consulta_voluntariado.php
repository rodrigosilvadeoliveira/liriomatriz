<?php
date_default_timezone_set('America/Sao_Paulo');
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');
include_once('config.php');

if ((!isset($_SESSION['usuario']) == true) && ($_SESSION['senha']) == true) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
}

$logado = $_SESSION['usuario'];
$resultlist = null;

// Processamento das consultas
if (!empty($_GET['search'])) {
    $data = $_GET['search'];
    $sql = "SELECT * FROM musicos 
            WHERE id LIKE '%$data%' 
            OR nome LIKE '%$data%' 
            ORDER BY nome ASC";
    $resultlist = $conexao->query($sql);
} else if (isset($_GET['filtro'])) {
    $filtro = $_GET['filtro'];

    if ($filtro == "nomeAZ") {
        $sql = "SELECT * FROM musicos ORDER BY nome ASC";
    } elseif ($filtro == "nomeZA") {
        $sql = "SELECT * FROM musicos ORDER BY nome DESC";
    } else {
        $sql = "SELECT * FROM musicos ORDER BY id DESC";
    }
    $resultlist = $conexao->query($sql);
}

if ($resultlist === null) {
    $sql = "SELECT * FROM musicos ORDER BY id DESC";
    $resultlist = $conexao->query($sql);
}

// Consulta para estatísticas
$sqlTotal = "SELECT COUNT(*) as total FROM musicos";
$resultTotal = $conexao->query($sqlTotal);
$rowTotal = $resultTotal->fetch_assoc();

// Lista de instrumentos/funções para verificar
$instrumentos = ['bateria', 'violao', 'teclado', 'baixo', 'ministro', 'vocal1', 'vocal2', 'vocal3', 'talckback', 'igreja', 'live', 'somkids', 'ct', 'c1', 'c2', 'lt', 'lz', 'ph', 'danca', 'real_time', 'real_time_kids', 'recap', 'real_time_treinamento', 'recap_treinamento', 'real_time_adolescentes', 'real_time_homens', 'real_time_mulheres', 'real_time_jovens', 'staff1', 'staff2'];
$contagemInstrumentos = [];

// Verificar quais colunas existem na tabela e têm valores preenchidos
foreach ($instrumentos as $instrumento) {
    // Primeiro verifica se a coluna existe na tabela
    $sqlCheckColumn = "SHOW COLUMNS FROM musicos LIKE '$instrumento'";
    $resultCheck = $conexao->query($sqlCheckColumn);

    if ($resultCheck->num_rows > 0) {
        // Agora conta os registros onde esta coluna tem algum valor preenchido (não vazio e não nulo)
        $sqlInstrumento = "SELECT COUNT(*) as total FROM musicos WHERE $instrumento IS NOT NULL AND $instrumento != ''";
        $resultInstrumento = $conexao->query($sqlInstrumento);

        if ($resultInstrumento) {
            $rowInstrumento = $resultInstrumento->fetch_assoc();
            $contagemInstrumentos[$instrumento] = $rowInstrumento['total'];
        } else {
            $contagemInstrumentos[$instrumento] = 0;
        }
    } else {
        $contagemInstrumentos[$instrumento] = 0;
    }
}

// Agora vamos obter os músicos que tocam cada instrumento (onde o valor é 'sim')
$musicosPorInstrumento = [];
foreach ($instrumentos as $instrumento) {
    if (isset($contagemInstrumentos[$instrumento]) && $contagemInstrumentos[$instrumento] > 0) {
        $sqlMusicos = "SELECT nome FROM musicos WHERE $instrumento = 'sim'";
        $resultMusicos = $conexao->query($sqlMusicos);
        $nomes = [];
        while ($row = $resultMusicos->fetch_assoc()) {
            $nomes[] = $row['nome'];
        }
        $musicosPorInstrumento[$instrumento] = $nomes;
    }
}
include('registroslog.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Músicos</title>

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
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --light-bg: #f8f9fc;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-custom {
            background-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

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

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
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

        .bg-info-light {
            background-color: #e6f4ff;
            color: var(--info-color);
        }

        .bg-warning-light {
            background-color: #fef5e6;
            color: var(--warning-color);
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

        .instrument-badge {
            padding: 0.3em 0.6em;
            border-radius: 15px;
            font-size: 0.7em;
            margin: 2px;
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: #6e707e;
            vertical-align: middle;
            text-align: center;
            font-size: 0.85rem;
        }

        .table td {
            vertical-align: middle;
            text-align: center;
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

        .instrument-col {
            text-align: center;
        }

        .instrument-icon {
            font-size: 1.2rem;
        }

        .table-responsive-container {
            overflow-x: auto;
            max-width: 100%;
        }

        .instrument-list {
            max-height: 120px;
            overflow-y: auto;
            font-size: 0.9rem;
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
                font-size: 0.75rem;
            }

            .member-img {
                width: 35px;
                height: 35px;
            }

            .instrument-icon {
                font-size: 1rem;
            }

            .table th {
                font-size: 0.7rem;
                padding: 0.5rem;
            }

            .table td {
                padding: 0.5rem;
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
                    <?php include("navegacao.php") ?>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-lg-10 col-md-9 ms-sm-auto px-4 py-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4 border-bottom">
                    <h1 class="h3"><i class="fas fa-music me-2"></i>Consulta de Voluntarios para escalas</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a href="cadastro_musicos.php" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i>Novo Músico
                            </a>
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
                                            Total de Músicos</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php echo $rowTotal['total']; ?>
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
                                            Ministros</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php echo $contagemInstrumentos['ministro']; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stats-icon bg-success-light">
                                            <i class="fas fa-microphone fa-2x"></i>
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
                                            Instrumentistas</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php
                                            $totalInstrumentistas = $contagemInstrumentos['bateria'] + $contagemInstrumentos['violao'] +
                                                $contagemInstrumentos['teclado'] + $contagemInstrumentos['baixo'];
                                            echo $totalInstrumentistas;
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stats-icon bg-info-light">
                                            <i class="fas fa-guitar fa-2x"></i>
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
                                            Vocalistas</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php
                                            $totalVocalistas = $contagemInstrumentos['vocal1'] + $contagemInstrumentos['vocal2'] + $contagemInstrumentos['vocal3'];
                                            echo $totalVocalistas;
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stats-icon bg-warning-light">
                                            <i class="fas fa-microphone-alt fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalhamento por Instrumento -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card card-dashboard">
                            <div class="card-header bg-white py-3">
                                <h5 class="card-title mb-0"><i class="fas fa-sliders-h me-2"></i>Detalhamento por
                                    Função/Instrumento</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php
                                    $instrumentLabels = [
                                        'bateria' => 'Bateria',
                                        'violao' => 'Violão',
                                        'teclado' => 'Teclado',
                                        'baixo' => 'Baixo',
                                        'ministro' => 'Ministro',
                                        'vocal1' => 'Vocal 1',
                                        'vocal2' => 'Vocal 2',
                                        'vocal3' => 'Vocal 3',
                                        'talckback' => 'Talckback',
                                        'igreja' => 'Igreja',
                                        'live' => 'Live',
                                        'somkids' => 'Som Kids',
                                        'ct' => 'CT',
                                        'c1' => 'C1',
                                        'c2' => 'C2',
                                        'lt' => 'LT',
                                        'lz' => 'LZ',
                                        'ph' => 'PH',
                                        'danca' => 'Dança',
                                        'real_time' => 'Real Time',
                                        'real_time_kids' => 'Real Time Kids',
                                        'recap' => 'Recap',
                                        'real_time_treinamento' => 'Real Time Treinamento',
                                        'recap_treinamento' => 'Recap Treinamento',
                                        'real_time_adolescentes' => 'Real Time Adolescentes',
                                        'real_time_homens' => 'Real Time Homens',
                                        'real_time_mulheres' => 'Real Time Mulheres',
                                        'real_time_jovens' => 'Real Time Jovens',
                                        'staff1' => 'Staff 1',
                                        'staff2' => 'Staff 2',
                                    ];

                                    foreach ($instrumentos as $instrumento):
                                        if ($contagemInstrumentos[$instrumento] > 0):
                                            ?>
                                            <div class="col-md-3 mb-3">
                                                <div class="card bg-light">
                                                    <div class="card-body p-3">
                                                        <h6 class="card-title"><?php echo $instrumentLabels[$instrumento]; ?>
                                                        </h6>
                                                        <p class="card-text mb-1">Quantidade:
                                                            <strong><?php echo $contagemInstrumentos[$instrumento]; ?></strong>
                                                        </p>
                                                        <?php if (!empty($musicosPorInstrumento[$instrumento])): ?>
                                                            <div class="instrument-list mt-2">
                                                                <small class="text-muted">Músicos:</small>
                                                                <?php foreach ($musicosPorInstrumento[$instrumento] as $musico): ?>
                                                                    <div class="text-truncate">• <?php echo $musico; ?></div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        endif;
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="filter-section mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <form id="filtroMusicos" method="GET" action="" class="mb-3">
                                <label for="filtro" class="form-label"><strong>Filtrar músicos:</strong></label>
                                <div class="d-flex">
                                    <select name="filtro" id="filtro" class="form-select me-2"
                                        onchange="document.getElementById('filtroMusicos').submit()">
                                        <option value="" disabled selected>-- Selecione um filtro --</option>
                                        <option value="todos" <?php if (isset($_GET['filtro']) && $_GET['filtro'] == "todos")
                                            echo "selected"; ?>>Todos músicos</option>
                                        <option value="nomeAZ" <?php if (isset($_GET['filtro']) && $_GET['filtro'] == "nomeAZ")
                                            echo "selected"; ?>>Nome A-Z</option>
                                        <option value="nomeZA" <?php if (isset($_GET['filtro']) && $_GET['filtro'] == "nomeZA")
                                            echo "selected"; ?>>Nome Z-A</option>
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
                                    <input type="text" class="form-control search-input" name="search"
                                        placeholder="Pesquisar por nome..."
                                        value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="mt-3">
                        <form method="GET" action="relatorio_musicos.php">
                            <input type="hidden" name="filtro"
                                value="<?php echo isset($_GET['filtro']) ? $_GET['filtro'] : ''; ?>">
                            <button type="submit" class="btn btn-success export-btn">
                                <i class="fas fa-file-export me-2"></i>Exportar para Excel
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Musicians Table -->
                <div class="card card-dashboard">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0">Lista de Músicos</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-container">
                            <table id="tabelaMusicos" class="table table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Foto</th>
                                        <th>Nome</th>
                                        <?php foreach ($instrumentos as $instrumento):
                                            if ($contagemInstrumentos[$instrumento] > 0): ?>
                                                <th><?php echo $instrumentLabels[$instrumento]; ?></th>
                                            <?php endif;
                                        endforeach; ?>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($resultlist && $resultlist->num_rows > 0) {
                                        while ($user_data = mysqli_fetch_assoc($resultlist)) {
                                            echo "<tr>";
                                            echo "<td><img class='member-img' src='" . $user_data['foto'] . "' onerror=\"this.src='https://via.placeholder.com/45?text=Sem+Imagem'\"></td>";
                                            echo "<td class='text-start'>" . $user_data['nome'] . "</td>";

                                            // Mostrar apenas colunas que têm valores
                                            foreach ($instrumentos as $instrumento) {
                                                if ($contagemInstrumentos[$instrumento] > 0) {
                                                    $valor = $user_data[$instrumento];
                                                    $badge_class = $valor == 'sim' ? 'bg-success' : 'bg-secondary';
                                                    echo "<td><span class='badge $badge_class'>$valor</span></td>";
                                                }
                                            }

                                            echo "<td>
                                                <div class='btn-group'>
                                                    <a class='btn btn-sm btn-primary action-btn' href='edit_voluntarioescala.php?id=$user_data[id]' title='Editar'>
                                                        <i class='fas fa-edit'></i>
                                                    </a>
                                                    <button class='btn btn-sm btn-info action-btn view-musician' data-id='$user_data[id]' title='Visualizar'>
                                                        <i class='fas fa-eye'></i>
                                                    </button>
                                                </div>
                                            </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        $colspan = count(array_filter($contagemInstrumentos)) + 3; // +3 para foto, nome e ações
                                        echo "<tr><td colspan='$colspan' class='text-center py-4'>Nenhum músico encontrado. Tente alterar os filtros de pesquisa.</td></tr>";
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

    <!-- Musician Detail Modal -->
    <div class="modal fade" id="musicianModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalhes do Músico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="musicianDetails">
                    <!-- Details will be loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <a href="#" id="editMusicianBtn" class="btn btn-primary">Editar</a>
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
        $(document).ready(function () {
            // Initialize DataTable
            $('#tabelaMusicos').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
                },
                order: [[1, 'asc']],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                scrollX: true
            });

            // View musician details
            $(document).on('click', '.view-musician', function () {
                var musicianId = $(this).data('id');

                // Show loading spinner
                $('#musicianDetails').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                        <p class="mt-2">Carregando informações do músico...</p>
                    </div>
                `);

                // Set the edit link
                $('#editMusicianBtn').attr('href', 'edit_voluntarioescala.php?id=' + musicianId);

                // Show the modal
                var modal = new bootstrap.Modal(document.getElementById('musicianModal'));
                modal.show();

                // AJAX call to get musician details
                $.ajax({
                    url: 'get_musician_details.php',
                    type: 'GET',
                    data: { id: musicianId },
                    success: function (response) {
                        $('#musicianDetails').html(response);
                    },
                    error: function () {
                        $('#musicianDetails').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Erro ao carregar os dados do músico. Tente novamente.
                            </div>
                        `);
                    }
                });
            });

            // Auto logout after 1 hour
            setTimeout(() => {
                window.location.href = "sistema.php?timeout=1";
            }, 3600000);
        });
    </script>
</body>

</html>